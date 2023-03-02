<?php

namespace App\Http\Requests\API\Partners;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
        return [

            'name'              => 'required|string|max:60',
            'email'             => 'required|email|unique:partners,email',
            'primary_color'     => 'nullable|string|max:6',
            'secondary_color'   => 'nullable|string|max:6'
        ];
    }
}
