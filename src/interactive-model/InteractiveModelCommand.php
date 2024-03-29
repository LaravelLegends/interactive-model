<?php

namespace LaravelLegends\InteractiveModel;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

/**
 * This command helps to insert a model data interactively with "php artisan" command
 *
 * @author Wallace Maxters <wallacemaxters@gmail.com>
 */

class InteractiveModelCommand extends Command
{
    protected $signature = 'model:interactive {model}';

    protected $description = 'Insert data in your model interactively';

    /**
     * Handlers the command
     * @return void
     */
    public function handle()
    {
        $instance = $this->getModelInstance();

        foreach ($instance->getFillable() as $field) {
            $value = $this->getFieldValue($instance, $field);

            $instance->setAttribute($field, $value);
        }

        $instance->save();

        $this->showInsertedModel($instance);
    }


    /**
     * Builds the name of the given model
     *
     * @return string
     */
    protected function getModelName(): string
    {
        $model = $this->argument('model');

        if (strpos($model, '\\') === false) {
            $model = 'App\\Models\\' . $model;
        }

        return $model;
    }

    /**
     * Retrieves the model instance
     *
     * @throws \UnexpectedValueException
     * @return Model
    */
    protected function getModelInstance(): Model
    {
        $model = $this->getModelName();

        if (!is_subclass_of($model, Model::class)) {
            throw new \UnexpectedvalueException("The class $model is not valid model");
        }

        return new $model;
    }


    /**
     * Retrieve input data from command line basead on field
     *
     * @param Model $model
     * @param string $field
     * @return mixed
     */
    protected function getFieldValue(Model $model, string $field)
    {
        $castType = $model->getCasts()[$field] ?? 'string';

        $value = $this->getByCastType($model, $castType, $field);

        return $value;
    }

    /**
     * Shows formatted output for the inserted model
     *
     * @param Model $model
     * @return void
     */
    protected function showInsertedModel(Model $model): void
    {
        $this->line("Data inserted in table <info>{$model->getTable()}</info>");

        $attributes = $model->getAttributes();

        ksort($attributes);

        $this->table(array_keys($attributes), [$attributes]);
    }


    protected function getByCastType(Model $model, string $castType, string $field)
    {
        if (in_array($castType, ['datetime', 'date', 'custom_datetime'])) {

            $value = $this->ask($this->getAskForField($field));

            try {
                $value = Carbon::parse($value);
            } catch (InvalidFormatException $e) {
                $value = $this->getByCastType($model, $castType, $field);
            }

            return $value;

        } elseif (in_array($castType, ['json', 'array'])) {
            $value = $this->ask($this->getAskForField($field));

            $value = json_decode($value, true);

            if (json_last_error() > 0) {
                $value = $this->getByCastType($model, $castType, $field);
            }

            return $value;

        } elseif (in_array($castType, ['bool', 'boolean'])) {

            $value = strtolower(
                $this->ask($this->getAskForField($field))
            );

            if (! in_array($value, ['true', 'false'])) {
                $value = strtolower(
                    $this->ask($this->getAskForField($field))
                );
            }

            return $value === 'true';

        }

        $method = in_array($field, $model->getHidden()) ? 'secret' : 'ask';

        $value = $this->$method($this->getAskForField($field));

        return $value === 'NULL' ? null : $value;
        
    }

    protected function getAskForField(string $field)
    {
        return "Type the \"$field\" value";
    }
}
