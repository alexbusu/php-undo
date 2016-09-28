<?php

namespace command;


abstract class AbstractCommandPacker implements commandPackerInterface
{
	/**
	 * @param commandInterface $command
	 * @return string
	 */
	public function pack( commandInterface $command ){
		return base64_encode( serialize( $command ) );
	}

	/**
	 * @param string $command
	 * @return commandInterface
	 */
	public function unpack( $command ){
		return unserialize( base64_decode( $command ) );
	}

}