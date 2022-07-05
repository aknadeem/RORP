<?php

namespace App\Http\Controllers\UserManagement;

use App\Models\Designation;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DesginationController extends Controller
{
    use HelperTrait;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'bail|required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()->toArray(),
                'success' => 'no',
            ], 201);
        }

        $designation = Designation::create([
            'name' => $request->name,
            'slug' => $this->getSlug($request->name),
        ]);
        if($designation){
            $message = 'Data created successfully!';
            $success = 'yes';
            $icon_type = 'success';
            $result = ['id' => $designation->id, 'name'  => $designation->name];
        }else{
            $message = 'Data not saved, Something went wrong';
            $success = 'no';
            $icon_type = 'warning';
            $result = [];
        }
        return response()->json([
            'message' => $message,
            'success' => $success,
            'data' => $result,
        ], 201);
    }
}
