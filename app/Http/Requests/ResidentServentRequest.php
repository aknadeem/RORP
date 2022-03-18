<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResidentServentRequest extends FormRequest
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
     resident_data_id
     */
    public function rules() {
        $rules = [
            'name' => 'bail|required|string',
            'father_name' => 'bail|required|string',
            'cnic' => 'bail|required|string|max:15',
            'servent_type_id' => 'bail|required|integer',
            'gender' => 'bail|required|string',
            'mobile_number' => 'bail|required',
            'email' => 'bail|string|nullable',
            'image' => 'bail|string|nullable',
            'occupation' => 'bail|string|nullable',
            'resident_data_id' => 'bail|integer|nullable',
        ];
        return $rules;
    }
}
