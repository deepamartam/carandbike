<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Models\User;
use App\Models\RoleUserSub;
use App\Models\Role;
use App\Models\Address;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        //$this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($role)
    {
        try {
            $role = ucwords($role);

            $query = User::with('roleSub.role')->where('is_active',1);

            if(!is_null($role)) {

                $query->whereIn('id', function($query2) use ($role) {
                    $query2->select('role_user_subs.user_id')->from('role_user_subs')->leftJoin('roles', 'role_user_subs.role_id', '=', 'roles.id')->where('roles.role', $role);
                });
            }

            $users = $query->get();

            //$users = User::with('roleSub.role')->where('roleSub.role', $role)->get();
            return response()->json([
                'success' => true,
                'message' => 'Users fetched successfuly',
                'data' => $users
            ], 200);

        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try after sometime'
            ], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('username', 'email', 'firstname', 'lastname', 'password', 'phone', 'image', 'house_no', 'street', 'zip_code', 'city', 'country_id', 'subsidiary_id', 'role_id');
        $validator = Validator::make($data, [
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|unique:users',
            'firstname' => 'required',
            'lastname' => 'required',
            'password' => 'required|min:6',
            'city' => 'required|string',
            'zip_code' => 'required',
            'country_id' => 'required',
            'role_id' => 'required|numeric',
            'subsidiary_id' => 'required|numeric',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }

        try {

            $user = User::create([
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'image' => $request->image,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
            ]);

            $roleSub = RoleUserSub::create([
                'user_id' => $user->id,
                'role_id' => $request->role_id,
            ]);

            $address = Address::create([
                'from_user_id' => $user->id,
                'house_no' => $request->house_no,
                'street' => $request->street,
                'zip_code' => $request->zip_code,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'subsidiary_id' => $request->subsidiary_id,
            ]);

            //User created, return success response
            Log::info('User created - '.$request->username);
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $user = User::with('roleSub.role')->where(['id' => $id])->first();
    
            if (!$user) {
                return response()->json([
                'success' => false,
                'message' => 'User not found',
                ], 400);
            }
            else {
                return response()->json([
                    'success' => true,
                    'message' => 'User fetched successfuly',
                    'data' => $user
                ], 200);
            }
        
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try after sometime'
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //Validate data
        $data = $request->only('username', 'email', 'firstname', 'lastname', 'phone', 'image', 'house_no', 'street', 'zip_code', 'city', 'country_id', 'subsidiary_id', 'role_id', 'password');
        $validator = Validator::make($data, [
            'username' => 'required|string|unique:users,username,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|numeric|unique:users,phone,'.$user->id,
            'firstname' => 'required',
            'lastname' => 'required',
            'city' => 'required|string',
            'zip_code' => 'required',
            'country_id' => 'required',
            //'role_id' => 'required|numeric',
            'subsidiary_id' => 'required|numeric',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }

        try {

            $userUpdate = $user->update([
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'image' => $request->image,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            if($request->password) {
                $user->update([
                    'password' => bcrypt($request->password),
                ]);
    
            }

            if($request->role_id) {
                $roleUpdate = RoleUserSub::where('user_id', $user->id)->update([
                    'role_id' => $request->role_id,
                ]);
            }

            $product = Address::updateOrCreate([
                'from_user_id' => $user->id,
            ],[
                'house_no' => $request->house_no,
                'street' => $request->street,
                'zip_code' => $request->zip_code,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'subsidiary_id' => $request->subsidiary_id,
            ]);

            //User updated, return success response
            Log::info('User updated - '.$request->name);
            return response()->json([
                'success' => true,
                'message' => 'User updates successfully',
            ], 200);

        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(Request $request, User $user)
    {
        //Validate data
        $data = $request->only('status');
        $validator = Validator::make($data, [
            'status' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Input',
                'error' => $validator->messages()], 400);
        }

        try{ 

            //Request is valid, update User
            $user = $user->update([
                'is_active' => $request->status,
            ]);

            //User updated, return success response
            Log::info('User '.( $request->status == 0 ? 'Disabled' : 'Enabled' ).' - '.$user);

            return response()->json([
                'success' => true,
                'message' => 'User '.( $request->status == 0 ? 'disabled' : 'enabled' ).' successfully',
            ], Response::HTTP_OK);

        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try{ 

            $user->delete();

            //User delete, return success response
            Log::info('User Deleted - '.$user);

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ], Response::HTTP_OK);

        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * User Email Update
     */
    public function changeEmail(Request $request)
    {
        //Validate data
        $validator = Validator::make($request->all(), [
            'new_email' => 'required|email|unique:users,email|same:email_confirmation',
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }

        $credentials = $request->only('email','password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Incorrect email or password.',
                ], 400);
            }
            else {
                User::where('email', $request->email)->update([
                    'email' => $request->new_email,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Email updated successfully',
                ], Response::HTTP_OK);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Somthing went wrong.',
                ], 500);
        }
    }

    public function changePassword(Request $request)
    {   

        $old_password = $request->old_password;
        //valid credential
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:6|max:50',
            'new_password' => 'required|min:6|max:50',
            'confirm_password' => 'required|same:new_password',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Input',
                'error' => $validator->messages()], 400);
        }

        try {
            $user = JWTAuth::authenticate($request->token);
            if(Hash::check($old_password, $user->password)) {

                User::find($user->id)->update(['password'=> Hash::make($request->new_password)]);

                Log::info('password-updated - '.$user->email);

                return response()->json([
                    'success' => true,
                    'message' => 'Password changed.'
                ], 200);
            }
            else {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password not matched.'
                ], 400);
            }
            
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
