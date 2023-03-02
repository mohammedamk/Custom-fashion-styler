<?php

namespace App\Http\Middleware;

use App\Models\Partner;
use App\Models\PartnerIntegration;
use App\Models\PartnerInvite;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicPartnerInvite
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

        $invite = $request->route( 'invite' );

        if( ! $invite )
        {
            throw new NotFoundHttpException( 'Could not find this invitation.' );
        }

        /**
         * @var PartnerInvite $invite
         */

        if( $invite->secret_key != $request->get( 'token' ) )
        {
            throw new NotFoundHttpException( 'Could not find this invitation.' );
        }

        if( ! $partner = $invite->partner()->first() )
        {
            throw new NotFoundHttpException( 'Could not find this invitation.' );
        }

        $request->attributes->set( 'partner', $partner );

        /**
         * @var Partner $partner
         */

        if( ! $integration = $partner->integration()->first() )
        {
            throw new NotFoundHttpException( 'Could not find this invitation.' );
        }

        $request->attributes->set( 'integration', $integration );

        /**
         * @var PartnerIntegration $integration
         */

        if( ! $integration_model = $integration->integration()->first() )
        {
            throw new NotFoundHttpException( 'Could not find this invitation.' );
        }

        $request->attributes->set( 'integration_model', $integration_model );

        return $next($request);
    }
}
