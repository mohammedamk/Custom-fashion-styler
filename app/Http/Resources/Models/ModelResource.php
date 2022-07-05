<?php

namespace App\Http\Resources\Models;

use Illuminate\Http\Resources\Json\JsonResource;

class ModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray( $request )
    {

        return [

            'id'            => $this->id,
            'name'          => $this->name,
            'gender'        => $this->gender,
            'dimensions'    => $this->dimensions,
            'profile_image' => $this->profile_image,
            'front_image'   => $this->front_image,
            'back_image'    => $this->back_image,
            'size'          => $this->size,
            'urls'          => [

                'edit' => route( 'dashboard.models.edit', [ 'model' => $this ] )

            ]
        ];
    }
}
