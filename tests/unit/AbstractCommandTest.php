<?php

namespace command;

use PHPUnit\Framework\TestCase;
use samples\command;

class AbstractCommandTest extends TestCase
{
    private $parameters;
    private $command;

    protected function setUp()
    {
        $this->parameters = [
            'old' => 'state',
            'new' => 'state',
        ];
        $this->command = new class($this->parameters) extends AbstractCommand {
            public $executeWasCalled = false;
            public $undoWasCalled = false;
            public $serializeWakeupWasCalled = false;
            public $wakeupWasCalled = false;

            public function execute()
            {
                $this->executeWasCalled = true;
            }

            public function undo()
            {
                $this->undoWasCalled = true;
            }

            public function __wakeup()
            {
                parent::__wakeup();
                $this->serializeWakeupWasCalled = true;
            }

            public function wakeup()
            {
                $this->wakeupWasCalled = true;
            }

            public function getInputParameters()
            {
                return $this->parameters;
            }
        };
    }

    public function testCommandWasInitialized()
    {
        self::assertTrue($this->command->serializeWakeupWasCalled, '::__wakeup() was not called');
        self::assertTrue($this->command->wakeupWasCalled, '::wakeup() was not called');
        self::assertSame($this->parameters, $this->command->getInputParameters());
    }

    public function testCommandExecute()
    {
        self::assertFalse($this->command->executeWasCalled, 'execute should not be called yet');
        $this->command->execute();
        self::assertTrue($this->command->executeWasCalled, 'execute should be called');
    }

    public function testCommandUndo()
    {
        self::assertFalse($this->command->undoWasCalled, 'undo should not be called yet');
        $this->command->undo();
        self::assertTrue($this->command->undoWasCalled, 'undo should be called');
    }
}
