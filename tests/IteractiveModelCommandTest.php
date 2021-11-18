<?php

use Illuminate\Console\Command;
use LaravelLegends\InteractiveModel\InteractiveModelCommand;
use LaravelLegends\InteractiveModel\InteractiveModelServiceProvider;
use Models\User;

class IteractiveModelCommandTest extends Orchestra\Testbench\TestCase
{

    public function testInstance()
    {
        $cmd = new InteractiveModelCommand();

        $this->assertInstanceOf(Command::class, $cmd);
    }

    public function testConsoleCommand()
    {
        $command = $this->artisan(
            'model:interactive', 
            ['model' => User::class]
        )
        ->expectsQuestion('Type the "name" value', 'Wallace')
        ->expectsQuestion('Type the "email" value', 'wallacemaxters@gmail.com')
        ->expectsQuestion('Type the "password" value', 'NULL')
        ->expectsQuestion('Type the "last_login" value', 'invalid_date') // erro proposital pra repetir
        ->expectsQuestion('Type the "last_login" value', '2021-11-12')
        ->expectsQuestion('Type the "metadata" value', '[') // erro proposital
        ->expectsQuestion('Type the "metadata" value', '{"open_menu" : true}')
        ->expectsQuestion('Type the "active" value', '123')
        ->expectsQuestion('Type the "active" value', 'True');
    }

    protected function getPackageProviders($app)
    {
        return [InteractiveModelServiceProvider::class];
    }
}