<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class MenuProvider extends ServiceProvider
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

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer( '*', function () {

            $items = [

                [

                    'label'         => 'Dashboard',
                    'url'           => \route( 'dashboard' ),
                    'icon'          => public_path( '/assets/images/svg/icon_dashboard.svg' ),
                    'is_exact'      => true
                ],

                [

                    'label'         => 'Partners',
                    'url'           => \route( 'dashboard.partners' ),
                    'icon'          => public_path( '/assets/images/svg/icon_partners.svg' )
                ],

                [

                    'label'         => 'Customers',
                    'url'           => '#',
                    'icon'          => public_path( '/assets/images/svg/icon_customers.svg' )
                ],

                [

                    'label'         => 'Models',
                    'url'           => \route( 'dashboard.models' ),
                    'icon'          => public_path( '/assets/images/svg/icon_model.svg' )
                ],

                [

                    'label'         => 'Fonts',
                    'url'           => \route( 'dashboard.fonts' ),
                    'icon'          => public_path( '/assets/images/svg/icon_font.svg' )
                ],

            ];

            $items = array_map( function( $item ) {

                $matches = [
                    $item[ 'url' ]
                ];

                if( empty( $item[ 'is_exact' ] ) )
                {
                    $matches[] = $item[ 'url' ] . '/*';
                }

                $item[ 'is_active' ] = \request()->fullUrlIs( $matches );

                return $item;

            }, $items );

            view()->share( 'menu_items', $items );

        } );
    }
}
