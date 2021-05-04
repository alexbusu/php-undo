<?php

namespace command;

interface commandDbInterface
{
    /**
     * @return mixed
     */
    public function persist();

    public function getById($commandId);

    public function delete();
}
