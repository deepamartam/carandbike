<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use App\Models\Vehicle;
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

            $vehicles = Vehicle::get();

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
}
