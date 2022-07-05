<?php

namespace App\Http\Requests\API\Partners;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InviteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [

            'store_type'            => [
                'required',
                'string',
                Rule::in( [ 'shopify' ] ),
            ]
        ];

        if( $this->get( 'store_type' ) == 'shopify' )
        {

            $rules[ 'shopify_url' ] = [

                'required',
                'string'
            ];
        }

        return $rules;
    }
}
