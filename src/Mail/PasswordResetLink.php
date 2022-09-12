<?php

namespace Sadguru\SGEntryPass\Mail;

use Illuminate\Support\Facades\URL;
use Sadguru\SGEntryPass\PasswordReset;
use Sadguru\SGEntryPass\UserLoginToken;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use DB;

class PasswordResetLink extends Mailable
{
    use Queueable, SerializesModels;


    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $tokenExist = PasswordReset::where('email', $user->email)->first();
        if($tokenExist){
            $now = Carbon::now();
            $tokenTime = Carbon::parse($tokenExist->created_at);
            if($tokenTime->diffInMinutes($now) < 30){
                $token = $tokenExist->token;
            }else{
                DB::table('password_resets')->where('token', $tokenExist->token)->delete();
                $token = $this->createToken($user);
            }
        }else{
            $token = $this->createToken($user);
        }
        $this->url = URL::to('/')."/reset-password?token=$token";
    }

    private function createToken($user){
        $token = $this->generateRandomString();
        DB::table('password_resets')->insert(
            [
                'email'=>$user->email,
                'token'=>$token
            ]
        );
        return $token;
    }

    private function generateRandomString($length = 12) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('SGEntryPass::password-reset-link-mail');
    }
}
