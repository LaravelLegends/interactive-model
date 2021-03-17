<?php

namespace LaravelLegends\InteractiveModel;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class InteractiveModelCommand extends Command
{
    protected $signature = 'll:interative-model {model}';

    protected $description = 'Insert data in models interactively';

    public function handle()
    {
	$model = $this->argument('model');

	if (strpos($model, '\\') === false) {
	   $model = 'App\\Model\\' . $model;
	}

        $this->line('The model is <info>' . $model . '</info>');

        if (! is_subclass_of($model, Model::class)) {
            return $this->error('Model not found');
        }

        $instance = new $model;

        foreach ($instance->getFillable() as $field) {

            $method = in_array($field, $instance->getHidden()) ? 'secret' : 'ask';

            $value = $this->$method("Type the \"$field\" value");

            $instance->setAttribute($field, $value);
        }

        $this->line("The user {$instance->getKey()} was created!");

        $this->line($instance->toJson());
    }
}
