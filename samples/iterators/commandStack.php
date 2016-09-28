<?php

namespace samples;

use command\AbstractCommand;
use command\commandInterface;
use command\commandIteratorInterface;

class commandStack extends AbstractCommand implements commandIteratorInterface, commandInterface
{

	/**
	 * @var int
	 */
	private $position = 0;

	private $rollback = false;

	function __construct( array $commands )
	{
		/**
		 * For command collections we will store in 'parameters' property the list of commandInterface() commands
		 */
		$this->parameters = array_values( $commands );
	}

	/**
	 * @return int
	 */
	public function key()
	{
		return $this->position;
	}

	/**
	 * Start the transaction
	 */
	public function execute()
	{
		try {
			$this->rewind();
			/* using last output we can pass parameters from one command to the next one (e.g. insert id) */
			$commands_output = [ ];
			while( $this->valid() ) {
				$command = $this->current();
				if( count( $commands_output ) ) {
					$command->provision( $commands_output );
				}
				$command_output = $command->execute();
				is_array( $command_output ) && ( $commands_output = array_merge( $commands_output, $command_output ) );
				$this->next();
			}
			return $commands_output;
		} catch( \Exception $e ) {
			$this->rollback();
			throw $e; // pass to next level
		}
	}

	public function rewind()
	{
		$this->position = 0;
	}

	/**
	 * @return bool
	 */
	public function valid()
	{
		return isset( $this->parameters[ $this->position ] );
	}

	/**
	 * @return commandInterface
	 */
	public function current()
	{
		return $this->parameters[ $this->position ];
	}

	public function next()
	{
		++$this->position;
	}

	private function rollback()
	{
		// move cursor to previous entry (current command just raised an Exception and did not complete)
		$this->prev();
		$this->rollback = true;
		$this->undo();
	}

	public function prev()
	{
		--$this->position;
	}

	/**
	 * Rollback transaction (run commands in the reverse order)
	 */
	public function undo()
	{
		if( !$this->rollback && (
				/* is first element */
				$this->position < 1
				/*OR*/ || /*not a valid element/offset*/
				!$this->valid()
			)
		)
			/* then scroll to last element */
			$this->fastForward();
		while( $this->valid() ) {
			$command = $this->current();
			$command->undo();
			$this->prev();
		}
	}

	public function fastForward()
	{
		$this->position = count( $this->parameters ) - 1;
	}

}