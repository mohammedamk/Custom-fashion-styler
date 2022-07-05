<?php

namespace App\Http\Middleware;

use App\Models\Partner;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthPartner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if( ! $partner = auth()->user() )
        {
            throw new AccessDeniedHttpException( 'Not a valid partner.' );
        }

        if( ! $partner instanceof Partner )
        {
            throw new AccessDeniedHttpException( 'Not a valid partner.' );
        }

        $request->route()->setParameter( 'partner', $partner );

        return $next($request);
    }
}
