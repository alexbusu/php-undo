<?php

namespace samples;

use command\AbstractCommand;
use command\commandInterface;

/**
 * Class overwriteCustomPeriodCommand
 * @package samples
 * @property \Phalcon\Session\AdapterInterface $receiver
 */
class overwriteCustomPeriodCommand extends AbstractCommand implements commandInterface
{

	public function execute(){
		$this->receiver->set( 'filter' , $this->parameters[ 'newState' ] );
	}

	public function undo(){
		$this->receiver->set( 'filter' , $this->parameters[ 'prevState' ] );
	}

	/**
	 * Inserts the used modules & other classes that will be used by execute() and undo() methods
	 */
	public function wakeup(){
		$this->receiver = \Phalcon\Di::getDefault()->get('session');
	}

}