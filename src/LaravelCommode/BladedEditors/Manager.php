<?php
    namespace LaravelCommode\BladedEditors;


    use Illuminate\View\Factory;
    use LaravelCommode\BladedEditors\Interfaces\IManager;

    class Manager implements IManager
    {
        private $bindings = [];

        public function addBinding($namespace, $viewNamespace)
        {
            $this->bindings[$namespace] = $viewNamespace;
        }

        public function guessEditor($class)
        {
            $reflection = new \ReflectionClass($class);

            if (array_key_exists($namespace = $reflection->getNamespaceName(), $this->bindings)) {
                return $this->bindings[$namespace]."::".$reflection->getShortName();
            }

            return '??::'.$reflection->getShortName();
        }
    }