<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Models\ModelResource;
use App\Models\Partner;
use Illuminate\Http\Request;

class ModelsController extends ClientController
{

    public function retrieveModels( Partner $partner )
    {
        return ModelResource::collection( $partner->models()->get() );
    }

    public function retrieveModel( $model_id, Partner $partner )
    {

        $query = $partner->models();

        $query->where( 'id', '=', $model_id );

        return [

            'success'       => true,
            'model'         => new ModelResource( $query->first() )
        ];
    }

}
