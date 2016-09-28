<?php

namespace samples;

use command\AbstractCommand;
use command\commandInterface;

/**
 * @package samples
 * @property \Accounts $receiver
 */
class editAccountCommand extends AbstractCommand implements commandInterface
{

    public function execute()
    {
        $user = $this->receiver->findFirst( $this->parameters[ 'uid' ] );
        $user && $user->update( $this->parameters[ 'newState' ] );
	}

    public function undo()
    {
        $user = $this->receiver->findFirst( $this->parameters[ 'uid' ] );
        $user && $user->update( $this->parameters[ 'prevState' ] );
	}

	/**
	 * Inserts the used modules & other classes that will be used by execute() and undo() methods
	 */
    public function wakeup()
    {
        $this->receiver = new \Accounts();
	}

}