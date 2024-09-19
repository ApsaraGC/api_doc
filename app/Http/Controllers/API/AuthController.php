<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request){
           $validateUser =Validator::make(
            $request->all(),
            [
                'name'=>'required',
                'email'=>'required|email|unique:users,email',
                'password'=>'required'
            ]
            );
            if($validateUser->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'Validation Error',
                    'errors'=>$validateUser->errors()->all()

                ],401);
            }
            $user =User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>$request->password,
            ]);
            return response()->json([
                'status'=>True,
                'message'=>'User Created Successfully',
                'user'=>$user,
               // 'errors'=>$validateUser->errors()->all()

            ],200);
    }
    public function login(Request $request){
        $validateUser =Validator::make(
            $request->all(),
            [

                'email'=>'required|email',
                'password'=>'required'
            ]
            );
            if($validateUser->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>'Authentication Fails',
                    'errors'=>$validateUser->errors()->all()

                ],404);
            }

            if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
                $authUser =Auth::user();
                return response()->json([
                    'status'=>True,
                    'message'=>'User logged in Successfully',
                    'token'=>$authUser->createToken("Api token")->plainTextToken,
                    'token_type'=>'Bearer',
                   // 'errors'=>$validateUser->errors()->all()

                ],200);

            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>'Email or Password does not match',
                    'errors'=>$validateUser->errors()->all()

                ],401);
            }
    }
    public function logout(Request $request){
         $user =$request->user();
         $user->tokens()->delete();

         return response()->json([
            'status'=>true,
            'message'=>'Logout Successfully',
            'user'=>$user,
        ],200);
    }
    //
}
