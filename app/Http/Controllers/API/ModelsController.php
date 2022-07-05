<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Models\SaveRequest;
use App\Http\Resources\Models\ModelResource;
use App\Models\Model;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ModelsController extends APIController
{

    public function list_models()
    {

        $query = Model::query();

        $query->orderBy( 'name', 'asc' );

        return ModelResource::collection( $query->get() );
    }

    public function create_model( SaveRequest $request )
    {

        $model = new Model( $request->only( [

            'name',
            'gender',
            'dimensions',
            'size'

        ] ) );

        if( ! $model->save() )
        {
            throw new UnprocessableEntityHttpException( "An error occurred while creating the model." );
        }

        $model->processImages( $request );

        $model->refresh();

        return [

            'success'           => true,
            'redirect'          => route( 'dashboard.models.edit', [

                'model'               => $model->id

            ] )
        ];
    }

    public function edit_model( Model $model, SaveRequest $request )
    {

        $model = $model->fill( $request->only( [

            'name',
            'gender',
            'dimensions',
            'size'

        ] ) );

        if( ! $model->save() )
        {
            throw new UnprocessableEntityHttpException( "An error occurred while updating the model." );
        }

        $model->processImages( $request );

        $model->refresh();

        return [

            'success'           => true,
            'model'             => $model
        ];
    }

}
