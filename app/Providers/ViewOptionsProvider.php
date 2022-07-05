<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class ViewOptionsProvider extends ServiceProvider
{

    private static $routes = null;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    private function getRoutes()
    {

        if( ! isset( self::$routes ) )
        {

            $routes = Route::getRoutes();

            $js_routes = [];

            foreach ( $routes as $route )
            {
                /**
                 * @var \Illuminate\Routing\Route $route
                 **/

                if ( !$name = $route->getName() )
                {
                    continue;
                }

                $params = [];

                foreach ( $route->parameterNames() as $parameterName )
                {
                    $params[ $parameterName ] = '%' . strtoupper( $parameterName ) . '%';
                }

                try
                {

                    $js_routes[ $name ] = route( $name, $params );
                }
                catch ( RouteNotFoundException $e )
                {
                }
            }

            self::$routes = $js_routes;
        }

        return self::$routes;
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        \Khead::setTitle( 'Moda Match' );

        $retrieve = function() {

            return $this->getRoutes();
        };

        view()->composer( '*', function () use ( $retrieve ) {

            view()->share( 'js_routes', $retrieve );

        } );
    }
}
