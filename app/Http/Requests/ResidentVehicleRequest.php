<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResidentVehicleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules() {
        $rules = [
            'vehicle_type_id' => 'bail|required|integer',
            'vehicle_name' => 'bail|required|string',
            'model_year' => 'bail|required|string',
            'make' => 'bail|string|nullable',
            'vehicle_number' => 'bail|required|string',
            'resident_data_id' => 'bail|integer|nullable',
            'vehicle_image' => 'nullable',
        ]; 
        return $rules;
    }
}