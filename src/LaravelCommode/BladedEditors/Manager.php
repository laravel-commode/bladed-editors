<?php

namespace LaravelCommode\BladedEditors;

use Illuminate\View\Factory;
use InvalidArgumentException;
use LaravelCommode\BladedEditors\Interfaces\IManager;
use ReflectionClass;

class Manager implements IManager
{
    const EDITOR = 'editor';
    const DISPLAY = 'display';
    const GRIDVIEW = 'grid';

    private $bindings = [];

    public function __construct()
    {
        $this->bindings[self::EDITOR] = [];
        $this->bindings[self::DISPLAY] = [];
    }

    private function appendBinding($type, $namespace, $viewNamespace)
    {
        return $this->bindings[$type][$namespace] = $viewNamespace;
    }

    private function fail($type, $match)
    {
        $match = is_object($match) ? get_class($match) : $match;

        throw new InvalidArgumentException(
            strtr('Unable to match {type} for {match}', ['{type}' => $type, '{match}' => $match])
        );
    }

    private function checkExistence($type, $class)
    {
        $reflection = new ReflectionClass($class);

        if (array_key_exists($namespace = $reflection->getNamespaceName(), $this->bindings[$type])) {
            return "{$this->bindings[$type][$namespace]}::{$reflection->getShortName()}";
        }

        return false;
    }

    private function serve($type, $class)
    {
        $viewName = $this->checkExistence($type, $class);

        if (!$viewName) {
            return $this->fail($type, $class);
        }

        return $viewName;
    }

    /**
     * Registers view namespace binding for classes' namespace.
     *
     * @param   string $namespace Classes namespace.
     * @param   string $viewNamespace View namespace.
     * @return  mixed
     */
    public function addEditorBinding($namespace, $viewNamespace)
    {
        return $this->appendBinding(self::EDITOR, $namespace, $viewNamespace);
    }

    /**
     * Registers view namespace binding for classes' namespace.
     *
     * @param   string $namespace Classes namespace.
     * @param   string $viewNamespace View namespace.
     * @return  mixed
     */
    public function addDisplayBinding($namespace, $viewNamespace)
    {
        return $this->appendBinding(self::DISPLAY, $namespace, $viewNamespace);
    }

    /**
     * Returns editor view name by given class instance.
     *
     * @param mixed $class
     * @return string
     */
    public function guessEditor($class)
    {
        return $this->serve(self::EDITOR, $class);
    }

    /**
     * Returns display view name by given class instance.
     *
     * @param mixed $class
     * @return string
     */
    public function guessDisplay($class)
    {
        return $this->serve(self::DISPLAY, $class);
    }
}
