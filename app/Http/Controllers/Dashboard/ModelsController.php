<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Partners\PartnerIntegrationResource;
use App\Models\Model;
use App\Models\Partner;
use Illuminate\Http\Request;

class ModelsController extends DashboardController
{

    public function list_all()
    {

        $this->setTitle( 'Models' );

        return view( 'dashboard.models.list_all' );
    }

    public function create_form()
    {

        $this->setTitle( [ 'Add new', 'Models' ] );

        return view( 'dashboard.models.create_form' );
    }

    public function edit_form( Model $model )
    {

        $this->setTitle( [ $model->name, 'Models' ] );

        return view( 'dashboard.models.edit_form', [

            'model'           => $model,

        ] );
    }

}
