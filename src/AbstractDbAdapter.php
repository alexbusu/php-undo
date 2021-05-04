<?php

namespace command;

use Phalcon\Mvc\Model\MessageInterface;

abstract class AbstractDbAdapter extends AbstractCommandPacker implements commandDbInterface
{
    /**
     * @var int $id The command DB ID
     */
    private $id;

    /**
     * Persists the Command to DB.
     * Serilizing the Command object will trigger __sleep() magic method in which
     * we will point the allowed properties for serialization
     */
    public function persist()
    {
        $command = new \Commands();
        $command->setDateAdded(new \Phalcon\Db\RawValue('NOW()'));
        $command->save();
        if (($messages = $command->getMessages())) {
            throw new \Exception(sprintf(
                'Unable to save \\%s model: %s', get_class($command), implode(', ', array_map(function ($v) {
                    /** @var MessageInterface $v */
                    return $v->getMessage();
                }, $messages))
            ));
        }
        // $this IS the Command so it MUST HAVE the self id set BEFORE packing
        $this->id = $command->getId();
        $command->setCmd($this->pack($this->getCommand()));
        $command->save();
        return $this->id;
    }

    /**
     * @param int $id
     * @return commandInterface|null
     */
    public function getById($id)
    {
        $command = \Commands::findFirst($id);
        if ($command) {
            return $this->unpack($command->getCmd());
        } else {
            return null;
        }
    }

    public function delete()
    {
        return is_numeric($this->id) && ($command = \Commands::findFirst($this->id)) && $command->delete();
    }

    /**
     * @return commandInterface
     * @throws \Exception
     */
    public function getCommand()
    {
        if (!($this instanceof commandInterface)) {
            throw new \Exception(
                sprintf(
                    'Expected instance of %s object, got %s', commandInterface::class, get_class($this)
                )
            );
        }
        return $this;
    }
}
