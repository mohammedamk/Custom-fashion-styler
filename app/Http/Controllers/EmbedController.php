<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class EmbedController extends Controller
{

    public function js_file()
    {

        $file = '/assets/embed/dist/bundle/app.js';

        if( config( 'app.env' ) != 'local' )
        {
            $file = str_replace( '.js', '.min.js', $file );
        }

        return response()->file( public_path( $file ), [

            'Content-Type' => 'text/javascript'

        ] );
    }

    public function css_file()
    {

        $file = '/assets/embed/dist/bundle/app.css';

        if( config( 'app.env' ) != 'local' )
        {
            $file = str_replace( '.css', '.min.css', $file );
        }

        return response()->file( public_path( $file ), [

            'Content-Type' => 'text/css'

        ] );
    }

    public function get_html( Partner $partner )
    {

        $options = [
            'assets_url'            =>  asset( '/assets/embed' ) . '/',
            'api_url'               =>  url( '/api/app' ) . '/',
            'is_tuck_in_enabled'    =>  $partner->is_tuck_in_enabled,
            'is_rotate_enabled'     =>  $partner->is_rotate_enabled,
            'partner_name'          =>  $partner->name,
            'is_font_enabled'       =>  $partner->is_font_enabled
        ];

        return view( 'layouts.embed', [

            'options'   => $options

        ] );
    }


    public function font_file( $slashData = null, Request $request ) {
      header( 'Access-Control-Allow-Origin: *' );
      $file = '/assets/fonts/' . $slashData;
      return response()->file( public_path( $file ), [
        'Content-Type' => 'font/otf'
      ] );
    }

}
