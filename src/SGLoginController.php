<?php

namespace Sadguru\SGEntryPass;


use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Mail;
use Sadguru\SGEntryPass\Mail\LoginLink;
use Sadguru\SGEntryPass\Mail\PasswordResetLink;
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
        $data = $request->only(['email', 'password']);
        if(Auth::attempt($data)){
            return redirect(config('sgentrypass.success_route') ?config('sgentrypass.success_route'): config('SGEntryPass.success_route'));
        }
        return redirect('login');
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
            'email' => 'required|email|max:255|exists:users,email',
        ]);
        $userExist = User::where('email',$request->get('email'))->first();
        if($userExist){
            if(env('APP_ENV') === 'local'){
                Mail::to($userExist->email)->send(new PasswordResetLink($userExist));
            }else{
                Mail::to($userExist->email)->queue(new PasswordResetLink($userExist));
            }
            $email = $userExist->email;
            return view('SGEntryPass::password-reset-link-sent', compact('email'));
        }
        $error = "Email does not exist.";
        // return view('SGEntryPass::login-link', compact(['error']));
        return redirect()->back()->withErrors(['email' => $error]);
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
            $user = User::where('email', $loginToken->email)->first();
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
            'email' => 'required|email|max:255|exists:users,email',
        ]);
        $userExist = User::where('email',$request->get('email'))->first();
        if($userExist){
            if(env('APP_ENV') === 'local'){
                Mail::to($userExist->email)->send(new LoginLink($userExist));
            }else{
                Mail::to($userExist->email)->queue(new LoginLink($userExist));
            }
            $email = $userExist->email;
            return view('SGEntryPass::login-link-sent', compact('email'));
        }
        $error = "Email does not exist.";
       // return view('SGEntryPass::login-link', compact(['error']));
        return redirect('/login-link')->withErrors(['email' => $error]);

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
                $user = User::where('email', $loginToken->email)->first();
                //$user = User::where('email', 'user@sadguruit.com')->first();
                $loginToken->status = 'USED';
                $loginToken->save();
                Auth::login($user);//Auth::attempt($request->only('email', 'password'));
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
           'email'=>'required|email|unique:users|max:255',
           'password'=>'required|min:8|max:255'
       ]);
        $data = $request->all();
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password'])
        ]);

        return redirect('login');

    }
}
