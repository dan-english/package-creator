<?php

namespace DanEnglish\PackageCreator;

use DanEnglish\PackageCreator\Commands\NewPackageCommand;
use DanEnglish\PackageCreator\Commands\ResetDemoCommand;
use Illuminate\Support\ServiceProvider;
use DanEnglish\PackageCreator\Commands\FooCommand;
use DanEnglish\PackageCreator\Exceptions\InvalidConfiguration;

class PackageCreatorServiceProvider extends ServiceProvider
{
    protected $vendorName = 'dan-english';
    protected $packageName = 'PackageCreator';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * A list of artisan commands for your package.
     *
     * @var array
     */
    protected $commands = [
        NewPackageCommand::class,
        ResetDemoCommand::class,
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services and bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/'.$this->packageName.'.php', $this->packageName);

        $config = config($this->packageName);

        // Register the service the package provides.
        $this->app->singleton(PackageCreator::class, function ($app) use ($config) {
            // Checks if configuration is valid
            $this->guardAgainstInvalidConfiguration($config);

            return new PackageCreator;
        });

        // Make alias for use with package name
        $this->app->alias(PackageCreator::class, $this->packageName);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [$this->packageName];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file
        $this->publishes([
            __DIR__.'/../config/'.$this->packageName.'.php' => config_path($this->packageName.'.php'),
        ], 'config');

        // Publishing the views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/'.$this->vendorName.'/'.$this->packageName),
        ], 'views');

        // Publishing seed's
        $this->publishes([
            __DIR__.'/../database' => base_path('/database'),
        ], 'seeds');

        // Registering package commands
        $this->commands($this->commands);
    }

    /**
     * Checks if the config is valid.
     *
     * @param  array|null $config the package configuration
     * @throws InvalidConfiguration exception or null
     * @see  \DanEnglish\PackageCreator\Exceptions\InvalidConfiguration
     */
    protected function guardAgainstInvalidConfiguration(array $config = null)
    {
        // Here you can add as many checks as your package config needed to
        // consider it valid.
        // @see \Me\MyPackage\Exceptions\InvalidConfiguration
        if (empty($config['version'])) {
            throw InvalidConfiguration::versionNotSpecified();
        }
    }

    /**
     * Check if package is running under Lumen app.
     *
     * @return bool
     */
    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen') === true;
    }
}
