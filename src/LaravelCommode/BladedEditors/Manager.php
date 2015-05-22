<?php

namespace LaravelCommode\BladedEditors;

use Illuminate\View\Factory;
use LaravelCommode\BladedEditors\Interfaces\IManager;

class Manager implements IManager
{
    private $bindings = [];

    /**
     * Registers view namespace binding for classes' namespace.
     *
     * @param   string $namespace Classes namespace.
     * @param   string $viewNamespace View namespace.
     * @return  mixed
     */
    public function addBinding($namespace, $viewNamespace)
    {
        $this->bindings[$namespace] = $viewNamespace;
    }

    /**
     * Returns editor view name by given class instance.
     *
     * @param mixed $class
     * @return string
     */
    public function guessEditor($class)
    {
        $reflection = new \ReflectionClass($class);

        if (array_key_exists($namespace = $reflection->getNamespaceName(), $this->bindings)) {
            return $this->bindings[$namespace].'::'.$reflection->getShortName();
        }

        return '??::'.$reflection->getShortName();
    }
}
