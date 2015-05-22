<?php

namespace LaravelCommode\BladedEditors\Commands;

use Illuminate\Foundation\Application;
use LaravelCommode\Bladed\Commands\ABladedCommand;
use LaravelCommode\BladedEditors\Interfaces\IManager;

/**
 * Class Editor.
 *
 * A command set for blade-extender.
 */
class Editor extends ABladedCommand
{
    /**
     * @var IManager
     */
    private $manager;

    public function __construct(Application $application)
    {
        parent::__construct($application);
        $this->manager = $application->make('LaravelCommode\BladedEditors\Interfaces\IManager');
    }

    /**
     * Returns view for provided model.
     *
     * @param mixed $model
     * @param array $data
     * @param bool $isNew
     * @return \Illuminate\View\View
     */
    public function model($model, array $data = [], $isNew = true)
    {
        return $this->getEnvironment()->make(
            $this->manager->guessEditor($model),
            array_merge($data, ['model' => $model], $this->getEnvironment()->getShared()),
            ['isNew' => $isNew]
        );
    }
}