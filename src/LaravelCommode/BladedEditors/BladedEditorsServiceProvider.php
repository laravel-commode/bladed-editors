<?php
namespace LaravelCommode\BladedEditors;

use Illuminate\Contracts\Foundation\Application;

use LaravelCommode\Bladed\BladedServiceProvider;
use LaravelCommode\Bladed\Interfaces\IBladedManager;

use LaravelCommode\BladedEditors\Commands\Editor;
use LaravelCommode\BladedEditors\Interfaces\IManager;

use LaravelCommode\SilentService\SilentService;

class BladedEditorsServiceProvider extends SilentService
{
    const PROVIDES_SERVICE = 'laravel-commode.bladed.editorsManager';

    protected function uses()
    {
        return [BladedServiceProvider::class];
    }

    public function provides()
    {
        return [IManager::class, self::PROVIDES_SERVICE];
    }

    /**
     * Will be triggered when the app's 'booting' event is triggered.
     */
    public function launching()
    {

    }

    /**
     * Triggered when service is being registered.
     */
    public function registering()
    {
        $this->app->singleton(IManager::class, Manager::class);

        $this->app->bind(self::PROVIDES_SERVICE, function (Application $app) {
            return $app->make(IManager::class);
        });

        $this->with([BladedServiceProvider::PROVIDES_SERVICE], function (IBladedManager $manager) {
            $manager->registerCommandNamespace('editor', Editor::class);
        });
    }
}
