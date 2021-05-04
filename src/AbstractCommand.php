<?php

namespace command;

/**
 * Class commandTrait
 * Common methods that will be used by Commands
 *
 * @package command
 */
abstract class AbstractCommand extends AbstractDbAdapter implements commandInterface
{
    protected $receiver = null;
    /**
     * @var array $parameters The command parameters
     */
    protected $parameters = [];
    /**
     * @var int $uid The user ID which initiates the command
     */
    private $uid = null;

    /**
     * Command constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->__wakeup();
    }

    public function provision(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function __sleep()
    {
        return ['uid', 'id', 'parameters'];
    }

    public function __wakeup()
    {
        $this->secure();
        $this->wakeup();
    }

    public function wakeup()
    {
        // command specific method
    }

    private function secure()
    {
        $current_user_id = class_exists(\SE::class) && !empty(\SE::singleton()->user->id) ? (int)\SE::singleton()->user->id : 0;
        if (is_null($this->uid)) {
            // no UID is set yet // Will set now
            $this->uid = $current_user_id;
        } else {
            // a user id is set // Will compare to current one // Will throw Exception if different IDs
            if ($this->uid != $current_user_id)
                throw new \Exception('You are not authorized to perform this action');
        }
    }
}
