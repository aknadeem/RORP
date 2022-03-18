<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResidentDataRequest extends FormRequest
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
            'type' => 'bail|required|string',
            'password' => 'bail|string|nullable',
            'name' => 'bail|required|string',
            'father_name' => 'bail|required|string',
            'address' => 'bail|string|nullable',
            'cnic' => 'bail|required|string',
            'landlord_name' => 'bail|string|nullable',
            'landlord_id' => 'bail|integer|nullable',
            'mobile_number' => 'bail|required|string',
            'emergency_contact' => 'bail|string|nullable',
            'email' => 'bail|required_if:type,resident|nullable|email',
            'occuptaion' => 'bail|string|nullable',
            'gender' => 'bail|required|string',
            'martial_status' => 'bail|required|string',
            'image' => 'bail|string|nullable',
            'business_number' => 'bail|string|nullable',
            'society_id' => 'bail|required|integer',
            'society_sector_id' => 'bail|required|integer',
            'address' => 'bail|required|string',
            'previous_address' => 'bail|string|nullable',
            'business_address' => 'bail|string|nullable',
            'mail_address' => 'bail|string|nullable'
        ]; 
        return $rules;
    }
    // public function rules()
    // {
    //     $installment_plans = ['full','1','3','12'];
    //     return [
    //         'project' => 'required|string',
    //         'unit_code' => 'required|string',
    //         'client_id' => 'required|integer',
    //         'amount' => 'required',
    //         'advance_amount' => 'required',
    //         'advance_percent' => 'required',
    //         'installment_plan' => 'required|string',
    //         'total_installment' => 'required_if:installment_plan,1,3,12',
    //         'th_date' => 'required_if:installment_plan,1,3,12|min:0|max:31',
    //         'commission_amount' => 'required_with:agent_id',
    //         'commission_percent' => 'required_with:agent_id',
    //         'ref_no' => 'string|nullable',
    //         'installment_plan' => [Rule::in($installment_plans),]
    //     ];   
    // }
}


