<?php
namespace Packages\{PACKAGENAME}\Providers;

use App\Providers\PackageServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;
use Packages\{PACKAGENAME}\Http\Controllers\{PACKAGENAME}Controller;
use Packages\{PACKAGENAME}\Models\{MODELNAME};
use Packages\{PACKAGENAME}\Observers\{MODELNAME}Observer;

/**
 * Class {PACKAGENAME}ServiceProvider
 *
 * @package Packages\{PACKAGENAME}\Providers
 */
class {PACKAGENAME}ServiceProvider extends PackageServiceProvider
{
    /**
     * Load views for this package.
     *
     * @var bool
     */
    protected $loadViews = true;
    /**
     * The routes to scan.
     *
     * @var array
     */
    protected $scanRoutes = [
        {PACKAGENAME}Controller::class,
    ];

    /**
     * The classes to scan for event annotations.
     *
     * @var array
     */
    protected $scanEvents = [];

    /**
     * @param Dispatcher $events
     */
    public function boot()
    {
        parent::boot();

        {MODELNAME}::observe({MODELNAME}Observer::class);
    }

    /**
     * Package Registration
     */
    public function register()
    {
        /**
         * Bind Contracts
         */
    }
}
