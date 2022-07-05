<?php

namespace App\Http\Controllers\API;


use App\Http\Requests\API\Partners\AssignModelsRequest;
use App\Http\Requests\API\Partners\CreateRequest;
use App\Http\Requests\API\Partners\EditRequest;
use App\Http\Requests\API\Partners\InviteRequest;
use App\Http\Resources\Partners\PartnerIntegrationResource;
use App\Http\Resources\Partners\PartnerResource;
use App\Interfaces\Integration;
use App\Models\Partner;
use App\Models\PartnerIntegration;
use App\Models\PartnerInvite;
use App\Models\ShopifyIntegration;
use Illuminate\Http\Request;
use Imtigger\LaravelJobStatus\JobStatus;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PartnersController extends APIController
{

    public function list_partners( Request $request )
    {

        $list = $request->get( 'list', 'approved' );

        $query = Partner::query();

        $query->orderBy( 'name', 'asc' );

        if( $list == 'approved' )
        {
            $query->where( 'is_pending', '=', 0 );
        }
        else if( $list == 'pending' )
        {
            $query->where( 'is_pending', '=', 1 );
        }

        return PartnerResource::collection( $query->get() );
    }

    public function create_partner( CreateRequest $request )
    {

        $partner = new Partner( $request->only( [

            'name',
            'email',
            'primary_color',
            'secondary_color'

        ] ) );

        $partner->setAttribute( 'is_pending', false );

        if( ! $partner->save() )
        {
            throw new UnprocessableEntityHttpException( "An error occurred while creating the partner." );
        }

        return [

            'success'           => true,
            'redirect'          => route( 'dashboard.partners.edit', [

                'partner'               => $partner->id

            ] )
        ];
    }

    public function edit_partner( Partner $partner, EditRequest $request ) {

        $partner = $partner->fill( $request->only( [

            'name',
            'email',
            'primary_color',
            'secondary_color',
            'is_pending',
            'is_font_enabled'

        ] ) );

        if( ! $partner->save() )
        {
            throw new UnprocessableEntityHttpException( "An error occurred while updating the partner." );
        }

        return [

            'success'           => true,
            'partner'           => $partner
        ];
    }

    public function invite( Partner $partner, InviteRequest $request )
    {

        $integration = $partner->integration()->first();

        /**
         * @var PartnerIntegration|null $integration
         */

        $integration_model = null;

        switch( $request->get( 'store_type' ) )
        {

            case 'shopify':

                $integration_data = [

                    'partner_id'            => $partner->id,
                    'shopify_url'           => $request->get( 'shopify_url' ),
                    'access_token'          => $request->get( 'access_token' ),
                    'private_app_key'       => $request->get( 'api_key' ),
                    'private_app_secret'    => $request->get( 'api_secret' ),

                ];

                if( ! $integration || ! $integration_model = $integration->integration()->first() )
                {
                    $integration_model = new ShopifyIntegration();
                }

                $integration_model->fill( $integration_data );

                if( ! $integration_model->save() )
                {
                    throw new UnprocessableEntityHttpException( "An error occurred while creating an integration for the partner." );
                }

                break;
        }

        if( ! $integration )
        {
            $integration = new PartnerIntegration();
        }

        $integration->fill( [

            'partner_id'            => $partner->id,

        ] );

        $integration->integration()->associate( $integration_model );

        if( ! $integration->save() )
        {
            throw new UnprocessableEntityHttpException( "An error occurred while creating an integration for the partner." );
        }

        //$partner->sendInvite();

        return [

            'success'       => true,
            'integration'   => new PartnerIntegrationResource( $integration )

        ];
    }

    public function import( Partner $partner, Request $request )
    {

        if( ! $integration = $partner->integration()->first() )
        {
            throw new UnprocessableEntityHttpException( "No integration is active for this partner." );
        }

        /**
         * @var PartnerIntegration $integration
         */

        if( ! $integration_model = $integration->integration()->first() )
        {
            throw new UnprocessableEntityHttpException( "No integration is active for this partner." );
        }

        /**
         * @var Integration $integration_model
         */

        if( ! empty( $request->get( 'from_scratch' ) ) )
        {

            $partner->products()->whereNotNull( 'variant_for' )->delete();

            $partner->products()->delete();

            $partner->product_categories()->delete();
        }

        $job_id = $integration_model->import();

        return [

            'success'       => true,
            'job_id'        => $job_id
        ];
    }

    public function import_status( Partner $partner, $job_id )
    {

        $job_status = JobStatus::find( $job_id );

        if( ! $job_status )
        {
            throw new UnprocessableEntityHttpException( "Unable to retrieve status." );
        }

        return [

            'success'       => true,
            'progress'      => $job_status->progress_percentage,
            'message'       => isset( $job_status->output[ 'message' ] ) ? $job_status->output[ 'message' ] : null,
            'is_ended'      => $job_status->is_ended
        ];
    }

    public function assign_models( Partner $partner, AssignModelsRequest $request )
    {

        $partner->models()->sync( $request->get( 'models' ) );

        $partner->fill( $request->only( [

            'is_rotate_enabled',
            'is_rotate_enabled',
            'is_tuck_in_enabled'

        ] ) );

        $partner->save();

        return [

            'success'           => true

        ];
    }


}
