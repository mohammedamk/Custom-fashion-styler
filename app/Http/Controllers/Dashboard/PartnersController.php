<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Resources\Partners\PartnerIntegrationResource;
use App\Interfaces\Integration;
use App\Models\Partner;

class PartnersController extends DashboardController
{

    public function list_all()
    {

        $this->setTitle( 'Partners' );

        return view( 'dashboard.partners.list_all' );
    }

    public function create_form()
    {

        $this->setTitle( [ 'Add new', 'Partners' ] );

        return view( 'dashboard.partners.create_form' );
    }

    public function edit_form( Partner $partner )
    {

        $this->setTitle( [ $partner->name, 'Partners' ] );

        $integration = $partner->integration()->first();

        if( $integration )
        {
            $integration = new PartnerIntegrationResource( $integration );
        }

        return view( 'dashboard.partners.edit_form', [

            'partner'           => $partner,
            'integration'       => $integration

        ] );
    }

    public function import_hub( Partner $partner )
    {

        $this->setTitle( [ 'Import', $partner->name, 'Partners' ] );

        $integration = $partner->integration()->first();

        $integration_model = $integration->integration()->first();

        /**
         * @var Integration $integration_model
         */

        $integration_model->retrieveProducts();

        if( $integration )
        {
            $integration = new PartnerIntegrationResource( $integration );
        }

        return view( 'dashboard.partners.import-hub', [

            'partner'           => $partner,
            'integration'       => $integration

        ] );
    }
}
