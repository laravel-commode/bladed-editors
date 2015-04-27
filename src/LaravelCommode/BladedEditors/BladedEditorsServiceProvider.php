<?php
	namespace LaravelCommode\BladedEditors;

	use Illuminate\Support\ServiceProvider;
	use LaravelCommode\Bladed\Interfaces\IBladedManager;
	use LaravelCommode\Common\GhostService\GhostService;

	class BladedEditorsServiceProvider extends GhostService
	{
		protected function uses()
		{
			return ['LaravelCommode\Bladed\BladedServiceProvider'];
		}

		public function provides()
		{
			return [
				'LaravelCommode\BladedEditors\Interfaces\IManager',
				'commode.bladed.editorsManager'
			];
		}

		/**
		 * Will be triggered when the app's 'booting' event is triggered
		 */
		protected function launching() { }

		/**
		 * Triggered when service is being registered
		 */
		protected function registering()
		{
			$this->app->singleton(
				'LaravelCommode\BladedEditors\Interfaces\IManager',
				'LaravelCommode\BladedEditors\Manager'
			);

			$this->app->bindShared('commode.bladed.editorsManager', function ($app)
			{
				return $app->make('LaravelCommode\BladedEditors\Interfaces\IManager');
			});

			$this->with(['commode.bladed'], function(IBladedManager $manager){
				$manager->registerCommandNamespace('editor', 'LaravelCommode\BladedEditors\Commands\Editor');
			});
		}
	}