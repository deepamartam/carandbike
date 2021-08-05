<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Shortlisted_Vehicles;



class ShortlistedVehicleController extends Controller
{
    //
    protected $user;

    public function __construct()
    {
        //$this->user = JWTAuth::parseToken()->authenticate();
    }

    public function shortlistedvehicles()
    {
        return Shortlisted_Vehicles::all();
    }
    
    public function deletevehicle($id)
    {
        try{ 
            $vehicle = Shortlisted_Vehicles::find($id);
            $vehicle->delete();

            //User delete, return success response

            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully'.$id,
            ], Response::HTTP_OK);

        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
}
