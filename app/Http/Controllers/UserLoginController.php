<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Session;
use Redirect;
use Illuminate\Validation\ValidationException;
class UserLoginController extends Controller
{
    use HelperTrait;
    public function Loginuser(Request $request)
    {
    	// dd('hello');    
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (\Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
                // 'user_level_id' => [1,2,3,4,5],
            ])) {

            if(auth()->user()->is_active == 0){
                auth()->logout();
                return Redirect::back()->withErrors([
                'email' => 'Your Account has been deactivated!',
            ]);
            }else{
                return redirect('/');
            }
        } else {
            return Redirect::back()->withErrors([
                'email' => 'The email or password you entered didnot match the records!',
            ]);
        }
    }
    
    // for json api 
    public function changePasswordApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'bail|required|min:3',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 422);
        }
        $user_detail = $this->apiLogUser();
        if($user_detail !=''){
            $user = User::where('id', $user_detail->id)->first();
            $user->fill([
                'password' => Hash::make($request->password),
            ])->save();
            if($user){
                $message = 'yes';
            }else{
                $message = 'no';
            }
            return response()->json([
                'message' => 'yes',
                'user' => $user,
            ], 201);
        }
    }
    
    
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'bail|required|email',
            'password' => 'bail|required|string|min:4',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                // $user->tokens()->delete();
                // event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            $reset_message = 'Password reset successfully';
            return view('auth.passwords.reset-success', compact('reset_message'));
        }else{
            return back()->withErrors(['email' => [__($status)]]);
        }
        // return response([
        //     'message'=> __($status)
        // ], 500);
    }
}
