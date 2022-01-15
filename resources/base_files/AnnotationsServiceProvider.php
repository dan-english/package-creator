<?php
namespace App\Providers;

use Collective\Annotations\AnnotationsServiceProvider as ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Collective\Annotations\Routing\Scanner as RouteScanner;
use Illuminate\Support\Facades\Event;


class AnnotationsServiceProvider extends ServiceProvider {

    /**
     * The classes to scan for event annotations.
     *
     * @var array
     */
    protected $scanEvents = [];

    /**
     * The classes to scan for route annotations.
     *
     * @var array
     */
    protected $scanRoutes = [];

    /**
     * The classes to scan for model annotations.
     *
     * @var array
     */
    protected $scanModels = [];

    /**
     * Determines if we will auto-scan in the local environment.
     *
     * @var bool
     */
    protected $scanWhenLocal = false;

    /**
     * Determines whether or not to automatically scan the controllers
     * directory (App\Http\Controllers) for routes
     *
     * @var bool
     */
    protected $scanControllers = true;

    /**
     * Determines whether or not to automatically scan all namespaced
     * classes for event, route, and model annotations.
     *
     * @var bool
     */
    protected $scanEverything = false;

    /**
     * AnnotationsServiceProvider constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Add annotation classes to the route scanner
     * @param RouteScanner $namespace
     */
//    public function addRoutingAnnotations(RouteScanner $scanner)
//    {
//        $scanner->addAnnotationNamespace('App\Http\Annotations');
//    }

    /**
     * @return array
     */
    public function eventScans()
    {
        $events = parent::eventScans();

        return $this->mergeScansFromEvent($events, 'events');
    }

    /**
     * @return array
     */
    public function routeScans()
    {
        $routes = parent::routeScans();

        return $this->mergeScansFromEvent($routes, 'routes');
    }


    /**
     * @param $scans
     * @param $type
     *
     * @return array
     */
    protected function mergeScansFromEvent($scans, $type)
    {

        foreach (Event::dispatch("packages.scan.$type") as $others) {
            if (is_array($others)) {
                $scans = array_merge($scans, $others);
            }
        }

        return $scans;
    }

}
