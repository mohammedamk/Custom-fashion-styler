<?php

namespace App\Http\Resources\Partners;

use App\Http\Resources\Models\ModelResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [

            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'views'         => 0,
            'sales'         => 0,
            'conversion'    => '-',
            'models'        => ModelResource::collection( $this->models ),
            'is_pending'    => ! empty( $this->is_pending ),
            'urls'          => [

                'edit'      => route( 'dashboard.partners.edit', [ 'partner' => $this ] ),
                'products'  => route( 'dashboard.partners.products', [ 'partner' => $this ] ),

            ]
        ];
    }
}
