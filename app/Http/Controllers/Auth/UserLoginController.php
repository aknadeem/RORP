<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function Loginuser(Request $request)
    {
    	dd('hello');
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (
            \Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
                'status' => 'active',
            ])
        ) {
            return redirect('/login');
        } else {
            return Redirect::back()->withErrors([
                'email' => 'The email or password you entered didnot match the records!',
            ]);
        }
    }
}
