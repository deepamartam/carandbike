<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use App\Models\OwnerVehicle;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VehicleController extends Controller
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
    public function index()
    {
        try {

            $vehicles = OwnerVehicle::get();

            return response()->json([
                'success' => true,
                'message' => 'Vehicles fetched successfuly',
                'data' => $vehicles
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
        $data = $request->only('vehicle', 'description', 'tire_size_id', 'front_tire_size_id', 'body_vehicles_id', 'user_created_id', 'customer_user_id', 'current_user_id', 'wheel_size_id', 'vehicle_id', 'customer_sub_id', 'current_sub_id', 'version_car_id', 'modal_car_id', 'inside_color_id', 'outside_color_id');

        $validator = Validator::make($data, [
            'vehicle' => 'required',
            'description' => 'required',
            'tire_size_id' => 'required|numeric', 
            'front_tire_size_id' => 'required|numeric', 
            'body_vehicles_id' => 'required|numeric', 
            'user_created_id' => 'required|numeric', 
            'customer_user_id' => 'required|numeric', 
            'current_user_id' => 'required|numeric', 
            'wheel_size_id' => 'required|numeric', 
            'vehicle_id' => 'required|numeric', 
            'customer_sub_id' => 'required|numeric', 
            'current_sub_id' => 'required|numeric', 
            'version_car_id' => 'required|numeric', 
            'modal_car_id' => 'required|numeric', 
            'inside_color_id' => 'required|numeric', 
            'outside_color_id' => 'required|numeric'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }


        try {

            //Request is valid, create new Vehicle
            $ownerVehicle = OwnerVehicle::create([
                'vehicle' => $request->vehicle,
                'description' => $request->description,
                'tire_size_id' => $request->tire_size_id, 
                'front_tire_size_id' => $request->front_tire_size_id, 
                'body_vehicles_id' => $request->body_vehicles_id, 
                'user_created_id' => $request->user_created_id, 
                'customer_user_id' => $request->customer_user_id, 
                'current_user_id' => $request->current_user_id, 
                'wheel_size_id' => $request->wheel_size_id,  
                'vehicle_id' => $request->vehicle_id,  
                'customer_sub_id' => $request->customer_sub_id, 
                'current_sub_id' => $request->current_sub_id,  
                'version_car_id' => $request->version_car_id,  
                'modal_car_id' => $request->modal_car_id,  
                'inside_color_id' => $request->inside_color_id, 
                'outside_color_id' => $request->outside_color_id, 
            ]);

            //Vehicle created, return success response
            Log::info('Vehicle created - '.$request->name);
            return response()->json([
                'success' => true,
                'message' => 'Vehicle created successfully',
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
     * @param  \App\Models\OwnerVehicle  $ownerVehicle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $ownerVehicle = OwnerVehicle::where(['id' => $id])->whereNull('deleted_at')->first();
    
            if (!$ownerVehicle) {
                return response()->json([
                'success' => false,
                'message' => 'Owner Vehicle not found',
                ], 400);
            }
            else {
                return response()->json([
                    'success' => true,
                    'message' => 'Owner Vehicle fetched successfuly',
                    'data' => $ownerVehicle
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
     * @param  \App\Models\OwnerVehicle  $ownerVehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(OwnerVehicle $ownerVehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OwnerVehicle  $ownerVehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OwnerVehicle $ownerVehicle)
    {
        //Validate data
        //Validate data
        $data = $request->only('vehicle', 'description', 'tire_size_id', 'front_tire_size_id', 'body_vehicles_id', 'user_created_id', 'customer_user_id', 'current_user_id', 'wheel_size_id', 'vehicle_id', 'customer_sub_id', 'current_sub_id', 'version_car_id', 'modal_car_id', 'inside_color_id', 'outside_color_id');

        $validator = Validator::make($data, [
            'vehicle' => 'required',
            'description' => 'required',
            'tire_size_id' => 'required|numeric', 
            'front_tire_size_id' => 'required|numeric', 
            'body_vehicles_id' => 'required|numeric', 
            'user_created_id' => 'required|numeric', 
            'customer_user_id' => 'required|numeric', 
            'current_user_id' => 'required|numeric', 
            'wheel_size_id' => 'required|numeric', 
            'vehicle_id' => 'required|numeric', 
            'customer_sub_id' => 'required|numeric', 
            'current_sub_id' => 'required|numeric', 
            'version_car_id' => 'required|numeric', 
            'modal_car_id' => 'required|numeric', 
            'inside_color_id' => 'required|numeric', 
            'outside_color_id' => 'required|numeric'
        ]);


        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }

        try {


            //Request is valid, create new vehicle
            $ownerVehicle = $ownerVehicle->update([
                'vehicle' => $request->vehicle,
                'description' => $request->description,
                'tire_size_id' => $request->tire_size_id, 
                'front_tire_size_id' => $request->front_tire_size_id, 
                'body_vehicles_id' => $request->body_vehicles_id, 
                'user_created_id' => $request->user_created_id, 
                'customer_user_id' => $request->customer_user_id, 
                'current_user_id' => $request->current_user_id, 
                'wheel_size_id' => $request->wheel_size_id,  
                'vehicle_id' => $request->vehicle_id,  
                'customer_sub_id' => $request->customer_sub_id, 
                'current_sub_id' => $request->current_sub_id,  
                'version_car_id' => $request->version_car_id,  
                'modal_car_id' => $request->modal_car_id,  
                'inside_color_id' => $request->inside_color_id, 
                'outside_color_id' => $request->outside_color_id, 
            ]);

            //OwnerVehicle created, return success response
            Log::info('Owner Vehicle updated - '.$request->name);
            return response()->json([
                'success' => true,
                'message' => 'Vehicle updated successfully',
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
     * @param  \App\Models\OwnerVehicle  $ownerVehicle
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(Request $request, OwnerVehicle $ownerVehicle)
    {

        return;
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

            //Request is valid, update Vehicle
            $ownerVehicle = $ownerVehicle->update([
                'status' => $request->status,
            ]);

            //Owner Vehicle updated, return success response
            Log::info('Owner Vehicle '.( $request->status == 0 ? 'Disabled' : 'Enabled' ).' - '.$ownerVehicle);

            return response()->json([
                'success' => true,
                'message' => 'Owner Vehicle updated successfully',
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
     * @param  \App\Models\OwnerVehicle  $ownerVehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(OwnerVehicle $ownerVehicle)
    {
        try{ 

            $ownerVehicle->delete();

            //Owner Vehicle updated, return success response
            Log::info('Owner Vehicle Deleted - '.$ownerVehicle);

            return response()->json([
                'success' => true,
                'message' => 'Owner Vehicle deleted successfully',
            ], Response::HTTP_OK);

        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
