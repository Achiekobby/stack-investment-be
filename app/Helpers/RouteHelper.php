<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Cache;

class RouteHelper
{
    //* static method for the route helper class
    public static function includeRouteFiles($folder)
    {
        //* checking to see if the route files has been cached
        if (Cache::has('route_files')) {
            $routeFiles = Cache::get('route_files');
        } else {
            $routeFiles = glob($folder . '/*.php');

            Cache::put('route_files', $routeFiles, 3600);
        }

        //* Require the route files
        foreach ($routeFiles as $routeFile) {
            require $routeFile;
        }
    }
}
