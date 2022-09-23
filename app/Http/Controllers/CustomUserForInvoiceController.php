<?php

namespace App\Http\Controllers;

use App\Models\Society;
use App\Models\User;
use App\Models\ComplaintLog;
use App\Models\RequestService;
use App\Models\Complaint;
use App\Models\DepartmentHod;
use App\Models\UserLevel;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ComplaintNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomUserForInvoiceController extends Controller
{
    use HelperTrait;

    public function getCustomUser(Request $request)
    {
        $users = User::where('is_custom_user', '1')->get();
        $societies = Society::get();
        return  view('custom-user-data.index', compact('users','societies'));
    }

    public function customUserForm()
    {
        $societies = Society::with('sectors')->get();
        return view('custom-user-data.create', compact('societies'));
    }

    public function storeCustomUser(Request $request)
    {

//        dd($request->toArray());
        $userId = \Auth::user()->id;
        $this->validate($request,[
            'society_id' => 'bail|required|integer',
            'sector_id' => 'bail|required|integer',
            'name' => 'bail|required|string|min:3',
            'email' => 'bail|required|string|unique:users',
            'contact_no' => 'bail|required|string',
            'gender' => 'bail|string|nullable'
        ]);

//        dd($request->toArray());

        $user = User::create([
            'name' => $request->name,
            'society_id' => $request->society_id,
            'society_sector_id' => $request->sector_id,
            'email' => $request->email,
            'cnic' => $request->cnic,
            'contact_no' => $request->contact_no,
            'user_level_id' => 6,
            'gender' => $request->gender,
            'address' => $request->address,
            'addedby' => $userId,
            'is_custom_user' => '1'
        ]);
        Session::flash('notify', ['type'=>'success','message' => 'Data created successfully']);
        return redirect()->route('getCustomUser');
    }
}
