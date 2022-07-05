<?php

namespace App\Http\Controllers;

use Code4mk\LaraHead\Facades\Khead;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function setTitle( $title )
    {

        if( ! is_array( $title ) )
        {
            $title = [ $title ];
        }

        $title[] = 'Moda Match';

        Khead::setTitle( join( ' - ', $title ) );
        
    }
}
