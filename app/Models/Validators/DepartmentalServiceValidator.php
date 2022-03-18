<?php

namespace App\Models\Validators;

use App\Helpers\Constant;
use Illuminate\Validation\Rule;
use App\Models\DepartmentalService;
use Illuminate\Support\Facades\Validator;

class DepartmentalServiceValidator
{
    public function validate(DepartmentalService $service, array $attributes)
    {
        return validator($attributes,
            [
                'department_id' => ['bail', 'required', 'integer'],
                'sub_department_id' => ['bail', 'required', 'integer'],
                'service_title' => ['bail', 'required', 'string', 'min:3'],
                'service_charges' => ['bail', 'required', 'numeric'],
                'duration_type' => ['bail', 'required', Rule::in(Constant::CHARGES_TYPE_RULE)],
                'description' => ['bail', 'nullable'],
            ]
        )->validate();
    }
}