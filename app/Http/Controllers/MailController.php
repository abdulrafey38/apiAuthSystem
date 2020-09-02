<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignupEmail;
use App\Mail\forgotemail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public static function sendSignupEmail($name,$email,$verification_code){
        $data = [
            'name'=>$name,
            'verification_code'=>$verification_code
        ];
         Mail::to($email)->send(new SignupEmail($data));



    }

    public static function sendforgotPasswordEmail($name,$email,$verification_code){
        $data = [
            'name'=>$name,
            'verification_code'=>$verification_code,
            'email'=>$email
        ];
         Mail::to($email)->send(new forgotemail($data));



    }
}
