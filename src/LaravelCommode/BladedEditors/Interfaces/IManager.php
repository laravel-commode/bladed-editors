<?php

namespace LaravelCommode\BladedEditors\Interfaces;

/**
 * Interface IManager.
 *
 * An interface for binding manager.
 */
interface IManager
{
    /**
     * Registers view namespace binding for classes' namespace.
     *
     * @param   string  $namespace  Classes namespace.
     * @param   string  $viewNamespace  View namespace.
     * @return  mixed
     */
    public function addEditorBinding($namespace, $viewNamespace);

    /**
     * Registers view namespace binding for classes' namespace.
     *
     * @param   string $namespace Classes namespace.
     * @param   string $viewNamespace View namespace.
     * @return  mixed
     */
    public function addDisplayBinding($namespace, $viewNamespace);

    /**
     * Returns editor view name by given class instance.
     *
     * @param mixed $class
     * @return string
     */
    public function guessEditor($class);

    /**
     * Returns display view name by given class instance.
     *
     * @param mixed $class
     * @return string
     */
    public function guessDisplay($class);
}
