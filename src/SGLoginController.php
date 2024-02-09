<?php

namespace Sadguru\SGEntryPass;


use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
//use Mail;
//use Sadguru\SGEntryPass\Mail\LoginLink;
//use Sadguru\SGEntryPass\Mail\PasswordResetLink;
use DB;


class SGLoginController extends Controller
{


    public function login(){
        $user = Auth::user();
        if($user){
            return redirect('');
        }
        return view('SGEntryPass::login');
    }

    public function loginWithLink(){
        $user = Auth::user();
        if($user){
            return redirect('');
        }
        return view('SGEntryPass::login-link');
    }

    public function authenticateUser(Request $request){
        $user = Auth::user();
        if($user){
            return redirect('');
        }
        Log::info("before validation");
        $this->validate($request , [
            'phone' => 'required|digits:10|numeric',
            'password' => 'required'
            ]);
        $data = $request->only(['phone', 'password']);
        Log::info("After validation");
        $user = User::where($this->username(), $data['phone'])->first();
        if( Hash::check($data['password'], $user->password)){
            Auth::login($user);
            return redirect(config('sgentrypass.success_route') ?config('sgentrypass.success_route'): config('SGEntryPass.success_route'));
        }
        return redirect('login');
    }
    public function authenticateUserByEmail(Request $request){
        $user = Auth::user();
        if($user){
            return redirect('');
        }
        Log::info("before validation");
        $this->validate($request , [
            'email' => 'required|email',
            'password' => 'required'
            ]);
        $data = $request->only(['email', 'password']);
        if(Auth::attempt($data)){
            return redirect(config('sgentrypass.success_route') ?config('sgentrypass.success_route'): config('SGEntryPass.success_route'));
        }
        return redirect('login')->withErrors(['error' => 'Invalid email/password']);
    }

    public function logout(){
        $user = Auth::user();
        if($user){
            Auth::logout();
        }
        return redirect('login');
    }

    public function forgotPassword(){
        $user = Auth::user();
        if($user){
            return redirect('');
        }
        return view('SGEntryPass::forgot-password');
    }

    public function sendPasswordResetLink(Request $request){
        $user = Auth::user();
        if($user){
            return redirect('');
        }
        $this->validate($request, [
            'phone' => 'required|phone|max:255|exists:users,phone',
        ]);
        $userExist = User::where('phone',$request->get('phone'))->first();
        if($userExist){
            if(env('APP_ENV') === 'local'){
                // Mail to be replaced with OTP::to($userExist->phone)->send(new PasswordResetLink($userExist));
            }else{
                // Mail to be replaced with OTP::to($userExist->phone)->queue(new PasswordResetLink($userExist));
            }
            $phone = $userExist->phone;
            return view('SGEntryPass::password-reset-link-sent', compact('phone'));
        }
        $error = "Email does not exist.";
        // return view('SGEntryPass::login-link', compact(['error']));
        return redirect()->back()->withErrors(['phone' => $error]);
    }

    public function createPassword(Request $request){
        $request->validate([
            'password'=>'required|min:8|max:255'
        ]);
        $token =  $request->get('token');
        Log::info($token);
        $loginToken  = PasswordReset::where('token', $token)->first();
        if($loginToken){
            DB::table('password_resets')->where('token', $token)->delete();
            $data = $request->all();
            $user = User::where('phone', $loginToken->phone)->first();
            $user->password = Hash::make($data['password']);
            $user->save();
            return redirect('login');
        }
        return view('SGEntryPass::invalid-reset-link');
    }

    public function resetPassword(Request $request){
        $token =  $request->get('token');
        $loginToken  = PasswordReset::where('token', $token)->first();
        if($loginToken){
            return view('SGEntryPass::reset-password', compact(['token']));
        }
        return view('SGEntryPass::invalid-reset-link');
    }

    public function register(){
        $user = Auth::user();
        if($user){
            return redirect('');
        }
        return view('SGEntryPass::register');
    }

    public function sendLoginLink(Request $request){
        $this->validate($request, [
            'phone' => 'required|phone|max:255|exists:users,phone',
        ]);
        $userExist = User::where('phone',$request->get('phone'))->first();
        if($userExist){
            if(env('APP_ENV') === 'local'){
                // Mail to be replaced with OTP::to($userExist->phone)->send(new LoginLink($userExist));
            }else{
                // Mail to be replaced with OTP::to($userExist->phone)->queue(new LoginLink($userExist));
            }
            $phone = $userExist->phone;
            return view('SGEntryPass::login-link-sent', compact('phone'));
        }
        $error = "Email does not exist.";
       // return view('SGEntryPass::login-link', compact(['error']));
        return redirect('/login-link')->withErrors(['phone' => $error]);

    }

    public function authenticateLoginLink(Request $request){
        if(Auth::user()){
            return redirect('/');
        }
        $token =  $request->get('token');
        $loginToken  = UserLoginToken::where('token', $token)->where('status', null)->first();
        if($loginToken){
            $now = Carbon::now();
            $tokenTime = Carbon::parse($loginToken->created_at);
            if($tokenTime->diffInMinutes($now) < 30){
                $user = User::where('phone', $loginToken->phone)->first();
                //$user = User::where('phone', 'user@sadguruit.com')->first();
                $loginToken->status = 'USED';
                $loginToken->save();
                Auth::login($user);//Auth::attempt($request->only('phone', 'password'));
                if(Auth::user()) {
                    return redirect(config('sgentrypass.success_route') ?config('sgentrypass.success_route'): config('SGEntryPass.success_route'));
                }
            }else{
                $loginToken->status = 'EXPIRED';
                $loginToken->save();
            }
        }
        return view('SGEntryPass::invalid-link');
        //return redirect('/login-link')->withErrors(['token' => 'Invalid Token']);
    }

    public function createNewAccount(Request $request){

       $request->validate([
           'name'=>'required|max:255',
           'phone'=>'required|phone|unique:users|max:255',
           'password'=>'required|min:8|max:255'
       ]);
        $data = $request->all();
        $user = User::create([
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'password'=>Hash::make($data['password'])
        ]);

        return redirect('login');

    }

    public function username()
    {
        return 'phone';
    }
}
