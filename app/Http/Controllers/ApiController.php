<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use App\Models\RoleUserSub;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\SendMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
    public function register(Request $request)
    {
    	//Validate data
        $data = $request->only('name', 'email', 'phone', 'role_id', 'password');
        $validator = Validator::make($data, [
            //'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|unique:users',
            'password' => 'required|string|min:6|max:50',
            'role_id' => 'required|numeric',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }

        //Request is valid, create new user
        $user = User::create([
        	'name' => $request->name,
        	'email' => $request->email,
            'phone' => $request->phone,
        	'password' => bcrypt($request->password),
            
        ]);

        $roleSub = RoleUserSub::create([
            'user_id' => $user->id,
            'role_id' => $request->role_id,
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }
 
    public function authenticate(Request $request)
    {
        $email = request()->input('email');

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'phone';
        }

        request()->merge([$field => $email]);

        $credentials = $request->only($field, 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            $field => 'required',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
 	
 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
 
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Token Required',
                'error' => $validator->messages()], 400);
        }

		//Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
 
    public function get_user(Request $request)
    {

        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Token Required',
                'error' => $validator->messages()], 400);
        }

        //$currentUser = JWTAuth::user();
        
        $user = JWTAuth::authenticate($request->token);

        $role = RoleUserSub::where('user_id', $user->id)->with('role')->get()->first();
 
        return response()->json([
            'success' => true,
            'message' => 'User details fetched sucessfuly.',
            'user'    => $user,
            'role'    => $role,
        ], 200);  
    }

    /**
     * Forgot Password
     */
    public function reqForgotPassword(Request $request){

        //Validate data
        $data = $request->only('email');
        $validator = Validator::make($data, [
            'email' => 'required|email',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }

        if(!$this->validEmail($request->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found.'
            ], 400);
        } else {
            $this->sendEmail($request->email);
            return response()->json([
                'success' => true,
                'message' => 'Password reset mail has been sent.'
            ], 200);            
        }
    }


    public function sendEmail($email){
        $token = $this->createToken($email);
        Mail::to($email)->send(new SendMail($token));
    }

    public function validEmail($email) {
       return !!User::where('email', $email)->first();
    }

    public function createToken($email){
      $isToken = DB::table('password_resets')->where('email', $email)->first();

      if($isToken) {
        return $isToken->token;
      }

      $token = Str::random(80);;
      $this->saveToken($token, $email);
      return $token;
    }

    public function saveToken($token, $email){
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()            
        ]);
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request){

        //Validate data
        $validator = Validator::make($request->all(), [
            'passwordToken' => 'required',
            'password' => 'required|string|min:6|max:50|same:password_confirmation',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }

        return $this->validateToken($request)->count() > 0 ? $this->changePasswordApi($request) : $this->noToken();
    }

    private function validateToken($request){
        return DB::table('password_resets')->where([
            'token' => $request->passwordToken
        ]);
    }

    private function noToken() {
        return response()->json([
            'success' => false,
            'message' => 'Token does not exist.'
        ],400);
    }

    private function changePasswordApi(Request $request) {

        $getUser = $this->validateToken($request)->first();

        $user = User::whereEmail($getUser->email)->first();
        $user->update([
          'password'=>bcrypt($request->password)
        ]);
        $this->validateToken($request)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ],200);
    }  
}
