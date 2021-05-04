<?php

namespace samples;

use command\AbstractDbAdapter;

/**
 * Command loader (from DB)
 * @package samples
 */
class command extends AbstractDbAdapter
{
    /** @var \command\commandInterface $command */
    private $command;

    function __construct($commandId = 0)
    {
        if (is_numeric($commandId) && $commandId) {
            $this->command = $this->getById($commandId);
        }
    }

    public function getCommand()
    {
        return $this->command;
    }
}
