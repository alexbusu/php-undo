<?php

namespace command;

interface commandPackerInterface
{
	/**
	 * @param commandInterface $command
	 * @return string
	 */
	public function pack( commandInterface $command );

	/**
	 * @param string $command
	 * @return commandInterface
	 */
	public function unpack( $command );
}
