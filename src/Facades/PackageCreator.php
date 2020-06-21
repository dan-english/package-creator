<?php

namespace DanEnglish\PackageCreator\Facades;

use Illuminate\Support\Facades\Facade;

class PackageCreator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'packagecreator';
    }
}
