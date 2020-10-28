<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

/**
 * Class PackageServiceProvider
 *
 * @package App\Providers
 */
class PackageServiceProvider extends ServiceProvider
{
    protected $packageName  = null;
    protected $loadViews    = false;
    protected $scanRoutes   = [];
    protected $scanEvents   = [];
    protected $listen       = [];
    protected $subscribe    = [];

    /**
     *
     */
    public function boot()
    {

        /**
         * If the package name has not been set by the service provider then we assume the package has a
         * service provider named ExamplePackageServiceProvider and set the package name to be the
         * snake case version. So in this case we'd set packageName to example_package.
         */
        if (is_null($this->packageName)) {
            $classBasename = class_basename($this);
            if (Str::endsWith($classBasename, 'ServiceProvider')) {
                $this->packageName = Str::snake(str_replace("ServiceProvider", "", $classBasename));
            }
        }

        /**
         * If the loadViews is set to true, then look for a folder called Views in the package folder
         * and load the views from it using the snake_case package name
         */
        if (!is_null($this->packageName) && $this->loadViews === true) {
            $path = __DIR__ . "/../../packages/" . Str::studly($this->packageName) . "/Views";
            if (is_dir($path)) {
                $this->loadViewsFrom($path, $this->packageName);
            }
        }

        \Event::listen('packages.scan.routes', function () {
            return $this->scanRoutes;
        });

        \Event::listen('packages.scan.events', function () {
            return $this->scanEvents;
        });

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                \Event::listen($event, $listener);
            }
        }

        foreach ($this->subscribe as $subscriber) {
            \Event::subscribe($subscriber);
        }

    }

    /**
     * register
     */
    public function register()
    {
        //
    }
}
