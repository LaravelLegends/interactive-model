<?php

use Illuminate\Console\Command;
use LaravelLegends\InteractiveModel\InteractiveModelCommand;

class IteractiveModelCommandTest extends Orchestra\Testbench\TestCase
{
    public function testInstance()
    {
        $cmd = new InteractiveModelCommand();

        $this->assertInstanceOf(Command::class, $cmd);
    }
}