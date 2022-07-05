<?php

namespace App\Http\Requests\API\Products;

use Illuminate\Foundation\Http\FormRequest;

class DettachImageRequest extends FormRequest
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
            'side'          => 'required|string',
            'model'         => 'required|exists:models,id'
        ];
    }
}
