<?php

namespace LaravelCommode\BladedEditors\Commands;

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

    /**
     * @return IManager
     */
    private function getManager()
    {
        if ($this->manager === null) {
            $this->manager = $this->getApplication()->make(IManager::class);
        }

        return $this->manager;
    }

    private function constructArguments($model, array $data = [], $isNew = true)
    {
        return array_merge(
            $data,
            ['model' => $model, 'isNew' => $isNew],
            $this->getEnvironment()->getShared()
        );
    }

    public function editor($model, array $data = [], $isNew = true)
    {
        return $this->getEnvironment()->make(
            $this->getManager()->guessEditor($model),
            $this->constructArguments($model, $data, $isNew)
        );
    }

    public function display($model, array $data = [], $isNew = true)
    {
        return $this->getEnvironment()->make(
            $this->getManager()->guessDisplay($model),
            $this->constructArguments($model, $data, $isNew)
        );
    }
}
