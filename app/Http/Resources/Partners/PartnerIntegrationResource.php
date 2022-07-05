<?php

namespace App\Http\Resources\Partners;

use App\Models\Partner;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\PersonalAccessToken;

class PartnerIntegrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $output = [

            'id'            => $this->id,
            'store_type'    => '-',
            'status'        => '-',
            'last_updated'  => $this->updated_at,
            'partner_app_token' => null,
            'button_code'   => null,
            'is_active'     => false,
        ];

        if( $model = $this->integration()->first() )
        {
            $output[ 'store_type' ] = $model->integration_name;
            $output[ 'status' ] = $model->is_active ? 'Activated' : 'Inactive';
            $output[ 'button_code' ] = $model->button_code;
            $output[ 'is_active' ] = ! empty( $model->is_active );
        }

        if( $partner = $this->partner()->first() )
        {

            /**
             * @var Partner $partner
             */

            if( $invite = $partner->invite()->first() )
            {
                $output[ 'status' ] = 'Invitation sent';
            }

            $output[ 'partner_app_token' ] = $partner->api_token;
        }

        return $output;
    }
}
