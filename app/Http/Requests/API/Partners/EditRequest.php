<?php

namespace App\Http\Requests\API\Partners;

use App\Models\Partner;
use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
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

        $partner = $this->route( 'partner' );

        $partner_id = $partner instanceof Partner ? $partner->id : $partner;

        return [

            'name'              => 'required|string|max:60',
            'email'             => 'required|email|unique:partners,email,' . $partner_id,
            'primary_color'     => 'nullable|string|max:6',
            'secondary_color'   => 'nullable|string|max:6'
        ];
    }
}
