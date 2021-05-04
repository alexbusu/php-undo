<?php

namespace command;

interface commandInterface extends commandDbInterface, commandPackerInterface
{
    /**
     * Provisions the command with custom data (used in commandStack)
     * @param array $params
     * @return mixed
     */
    public function provision(array $params);

    public function execute();

    public function undo();
}
