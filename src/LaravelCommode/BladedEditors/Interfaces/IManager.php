<?php
    namespace LaravelCommode\BladedEditors\Interfaces;


    interface IManager
    {
        public function addBinding($namespace, $viewNamespace);

        public function guessEditor($class);
    }