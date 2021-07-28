<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Models\User;
use App\Models\RoleUserSub;
use App\Models\Role;
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
        $this->user = JWTAuth::parseToken()->authenticate();
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
}
