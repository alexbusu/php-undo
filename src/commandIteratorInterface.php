<?php

namespace command;

interface commandIteratorInterface extends \Iterator
{
    /**
     * Move backward, to previous element
     * @return void
     */
    public function prev();

    /**
     * Fasts Forward the Iterator to the last element
     * @return void
     */
    public function fastForward();
}
