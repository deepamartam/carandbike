<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Cviebrock\EloquentSluggable\Services\SlugService;
use DB;

class StoreController extends Controller
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

            $stores = Store::with('user')->get();
            return response()->json([
                'success' => true,
                'message' => 'Stores fetched',
                'data' => $stores
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
        $data = $request->only('user_id','name','title','address','city','state',
        'zip_code','country','latitude','longitude','status','opening_hours');

        $validator = Validator::make($data, [
            'user_id' => 'required|numeric',
            'name' => 'required|unique:stores',
            'title' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }


        try {

            //Request is valid, create new Store
            $store = Store::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'title' => $request->title,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => $request->status,
                'opening_hours' => $request->opening_hours
            ]);

            //Store created, return success response
            Log::info('Store created - '.$request->name);
            return response()->json([
                'success' => true,
                'message' => 'Store created successfully',
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
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $store = Store::where(['id' => $id])->whereNull('deleted_at')->first();
    
            if (!$store) {
                return response()->json([
                'success' => false,
                'message' => 'Store not found',
                ], 400);
            }
            else {
                return response()->json([
                    'success' => true,
                    'message' => 'Store fetched successfuly',
                    'data' => $store
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
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        //Validate data
        $data = $request->only('user_id','name','title','address','city','state',
        'zip_code','country','latitude','longitude','status','opening_hours');

        $validator = Validator::make($data, [
            'user_id' => 'required|numeric',
            'name' => 'required|unique:stores,name,'.$store->id,
            'title' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }

        try {


            //Request is valid, create new store
            $store = $store->update([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'title' => $request->title,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => $request->status,
                'opening_hours' => $request->opening_hours
            ]);

            //Store created, return success response
            Log::info('Store updated - '.$request->name);
            return response()->json([
                'success' => true,
                'message' => 'Store updated successfully',
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
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function statusUpdate(Request $request, Store $store)
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

            //Request is valid, update Store
            $store = $store->update([
                'status' => $request->status,
            ]);

            //Store updated, return success response
            Log::info('Store '.( $request->status == 0 ? 'Disabled' : 'Enabled' ).' - '.$store);

            return response()->json([
                'success' => true,
                'message' => 'Store updated successfully',
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
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        try{ 

            $store->delete();

            //Store updated, return success response
            Log::info('Store Deleted - '.$store);

            return response()->json([
                'success' => true,
                'message' => 'Store deleted successfully',
            ], Response::HTTP_OK);

        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
