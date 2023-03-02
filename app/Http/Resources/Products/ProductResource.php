<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray( $request ) {

        $data = [
            'id'                 => $this->id,
            'title'              => $this->formatted_title,
            'description'        => $this->description,
            'price'              => $this->price,
            'image'              => $this->image,
            'gallery'            => $this->gallery,
            'models'             => $this->models,
            'category'           => $this->category,
            'layer'              => $this->layer,
            'variants'           => $this->whenLoaded( 'variants', ProductResource::collection( $this->variants ) ),
            'is_variant'         => $this->is_variant,
            'is_default_variant' => $this->is_default_variant,
            'options'            => [],
            'source_product_id'  => $this->source_product_id
        ];

        $title_with_options = $this->formatted_title;

        for ( $i = 1; $i < 5; $i++ )
        {

            $choices_key = 'option' . $i . '_choices';
            $value_key = 'option' . $i;

            if ( isset( $this->$choices_key ) )
            {
                $data[ 'options' ][ 'option' . $i ] = $this->$choices_key;
            }

            if ( isset( $this->$value_key ) )
            {
                $data[ $value_key ] = $this->$value_key;
                if($this->$value_key){
                    $title_with_options = $title_with_options . ' - ' . $this->$value_key;
                }
            }
        }
        $data['title_with_options'] = $title_with_options;

        return $data;
    }
}
