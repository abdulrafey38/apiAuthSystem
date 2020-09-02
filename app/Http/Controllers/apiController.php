<?php

namespace App\Http\Controllers;
use Validator;
use Carbon\Carbon;
use App\User;
use App\resetpasswordRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Notifications\signUp;
use App\Notifications\sms;
use App\Notifications\slack;
use App\Events\RegisterNewUserEvent;

class apiController extends Controller
{

//==================================================================
    //login api also creates sanctum tokens and returns with response
    public function login (Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'required|email',
            'password' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

      //  $user = User::where('email', $request->email)->first();
        $credentials = $request->only(['email', 'password']);
        if (Auth::attempt($credentials)) {
            // user auth pass
            $user = Auth::user();
            if($user->is_verified==0)
            {
                return response(['Unauthorize'=>'User Not Verified'],401);
            }
            $token = $user->createToken('MyApp')->plainTextToken;


            return response()->json([
                'token'=>$token,
                'status' => true,
                'data' => $user,
                'message' => 'Login successfull.'
            ],201);

        }
        return response()->json([
            'status' => false,
            'message' => 'Invalid Credentials.'
        ]);



    }
//======================================================================================
    //user registration
    public function register(request $request)
    {

        $user = new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password= bcrypt($request->password);
        $user->verification_code = sha1(time());
        $user->phone_number = $request->phone_number;
        $user->save();
       
        if($user != null)
        {
            //$user->notify(new signUp($user));//sending mail through laravel notifications
            // $user->notify(new sms($user));//sending sms through laravel notifications [Nexmo]
            //$user->notify(new slack($user));//sending slack Notification through laravel notifications 
            
            //MailController::sendSignupEmail($user->name,$user->email,$user->verification_code);//sending mail through mailer
           

            //Now all the above task which I comment of sending mails and sms done here using Events and listners
            \event(new RegisterNewUserEvent($user));
            return response ()->json([
                'message'=>'Please Verify Your Email Address!!'
            ]);
        }
        else{
            return response()->json([
                'message'=>'Something Went Wrong!!'
            ]);
        }

    }

//============================================================================================
    //verify user if unique verification code matches in mail (that application send) and in users tables verify user 
    public function verify(Request $request)
    {
        $v_code = \Illuminate\Support\Facades\Request::get('code');
        $user = User::where(['verification_code'=> $v_code])->first();
        if($user!=null)
        {
            $user->is_verified = 1;
            $user->save();
            return response(['Success'=>'You are veified now']);
        }
        return response (['Fail'=>'Something Went wrong'],400);
    }
//===============================================================================================
    //logout user and deletes its sanctum tokens
    public function logout(){
        $user = Auth::user();
        $user->tokens()->delete();

    }
//.===============================================================================================
    //returns login user
    public function loginuser(){
        $user = Auth::user();
        return response(['user'=>$user]);
    }
//================================================================================================
    //send mail for password reset link
    public function sendEmail(request $request)
    {

        $user = User::where('email',$request->email)->first();

        if($user != null)
        {   //if user != null put entry and a with unique token in password resets table
            \DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => sha1(time()),
                'created_at' => Carbon::now()
            ]);
            $tokenData = \DB::table('password_resets')->where('email', $request->email)->first();
            //send unique token in mail with other information
            //using mailer to send mails
            MailController::sendforgotPasswordEmail($user->name,$user->email,$tokenData->token);
            return response ()->json([
                'message'=>'Click on link to Reset Password!!'
            ]);
        }
        else{
            return response()->json([
                'message'=>'User Not Found!!'
            ]);
        }

    }
//=====================================================================
    //password reset manually without using laravel auth system functions
    public function reset(Request $request)
    {
        $v_code = \Illuminate\Support\Facades\Request::get('code');// get verification code from query string 
                                                                  //which is appended with link on which we are on
       
                                            
        $email =  \Illuminate\Support\Facades\Request::get('email');// get verification code from query string 
                                                                    //which is appended with link on which we are on

        $password = $request->password;

        $tokenData = \DB::table('password_resets')->where('token', $v_code)->first();//check if you are the true user by matching tokens
        if (!$tokenData)
        {
            return RESPONSE()->JSON([
                'Message'=>'Failed unauthorize'
            ],401);
        }
        $user = User::where(['email'=>$email])->first();


        if($user!=null)
        {
            $user->password = bcrypt($request->password);
            $user->save();
            //deletes enteries from password_reset table
            \DB::table('password_resets')->where('email', $user->email)->delete();


            return response(['Success'=>'Your password has been changed']);
        }
        return response (['Fail'=>'Something Went wrong'],400);
    }
//=========================================================================
    //gets all user
    public function user(){
        $user = User::all();
        return response(['user'=>$user]);
    }
//========================================================================
}
