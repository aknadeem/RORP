<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResidentFamilyRequest extends FormRequest
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
    public function rules() {
        $rules = [
            'name' => 'bail|required|string',
            'guardian_name' => 'bail|required|string',
            'cnic' => 'bail|required|string',
            'email' => 'bail|string|nullable',
            'mobile_number' => 'bail|required|string',
            'relation' => 'bail|required|string',
            'gender' => 'bail|required|string',
            'image' => 'bail|string|nullable',
            'resident_data_id' => 'bail|integer|nullable',
        ]; 
        return $rules;
    }


}
