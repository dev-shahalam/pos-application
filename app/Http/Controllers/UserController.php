<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class UserController extends Controller
{
    // for view pages
    function login(){
        return view('pages.auth.login-page');
    }

    function register(){
        return view('pages.auth.registration-page');
    }

    function dashboard(){
        return view('pages.dashboard.dashboard-page');
    }
    function sendOtpPage(){
        return view('pages.auth.send-otp-page');
    }
    function submitOtpPage(){
        return view('pages.auth.verify-otp-page');
    }
    function resetPasswordPage(){
        return view('pages.auth.reset-pass-page');
    }

    function userProfile(){
        return view('pages.dashboard.profile-page');
    }




    // for login and registration form
    function registrationUser(Request $request)
    {
        try {
            $request->validate([
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'required|email|unique:users',
                'mobile' => 'required',
                'password' => 'required',
            ]);

            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful',
            ], 200);
        }

        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    function loginUser(Request $request){
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = User::where('email', $request->input('email'))
                ->where('password', $request->input('password'))->first();

            if ($credentials){
                $token=JWTToken::CreateToken($request->input('email'),$credentials->id);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                ],200)->cookie('token_cookie',$token,60);
            }

        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ]);
    }
    function logoutUser(){
        return redirect()->route('login')->cookie('token_cookie','', -1);
    }

    function sendOtp(Request $request){
            $email = $request->input('email');
            $otp=rand(1000,9999);
            $count=User::where('email',$email)->count();
            if ($count==1) {
                Mail::to($email)->send(new OtpMail($otp));
                User::where('email', $email)->update(['otp' => $otp]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP sent successfully',
                ], 200);
            }
        else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }

    }

    function submitOtp(Request $request){
        $email=$request->input('email');
        $otp=$request->input('otp');
        $count=User::where('email',$email)
            ->where('otp',$otp)->count();
        if($count==1){
//            User::where('email', $email)->update(['otp' => null]);
            return response()->json([
                'status'=>'success',
                'message'=>'OTP verified successfully',
            ]);
        }
        else{
            return response()->json([
                'status'=>'Failed',
                'message'=>'Invalid OTP',
            ]);
        }
    }

    function resetPassword(Request $request){
        $email=$request->input('email');
        $password=$request->input('password');
        User::where('email',$email)->update(['password'=>$password]);

        return response()->json([
            'status'=>'success',
            'message'=>'Password reset successfully',
        ]);

    }


    function userProfileData(Request $request){
        $email=$request->header('email');
        $user=User::where('email',$email)->first();
        return response()->json([
            'status'=>'success',
            'message'=>'Request Successful',
            'user'=>$user,
        ],200);
    }

    function userProfileUpdate(Request $request){
        $email=$request->header('email');
        User::where('email',$email)->update([
            'firstName'=>$request->input('firstName'),
            'lastName'=>$request->input('lastName'),
            'mobile'=>$request->input('mobile'),
        ]);
        return response()->json([
            'status'=>'success',
            'message'=>'Profile updated successfully',
        ],200);
    }



}
