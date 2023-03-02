<?php

namespace App\Http\Requests\API\Partners;

use Illuminate\Foundation\Http\FormRequest;

class AssignModelsRequest extends FormRequest
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

            'models'            => 'nullable|array',
            'is_rotate_enabled' => 'nullable|boolean',
            'is_tuck_in_enabled'=> 'nullable|boolean'
        ];
    }
}
