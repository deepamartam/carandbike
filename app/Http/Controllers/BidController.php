<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Models\Bidauction;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class BidController extends Controller
{
    protected $user;

    
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function send_offer(Request $request)
    {
        $data = $request->only(
            'offer',
            'ad_vehicle_id',
            // 'subsidiary_id'
        );

        

        $validator = Validator::make($data, [
            'offer' => 'required',
            'ad_vehicle_id' => 'required',
            // 'subsidiary_id' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ", $validator->messages()->all()),
                'error' => $validator->messages()
            ], 400);
        }


        $bid = Bidauction::create([
            'offer' => $request->offer,
            'user_id' => $this->user->id,
            // 'subsidiary_id' => $request->subsidiary_id,
            'ad_vehicle_id' => $request->ad_vehicle_id,


        ]);

        
        return Response()->json([
            'success' => true,
            'message' => 'Offer Sent successfully',
        ], Response::HTTP_OK);
    }

    public function get_offer_list()
    {
        $list = Bidauction::where('user_id',$this->user->id)->select('id','ad_vehicle_id','offer','status')->get();
        return Response()->json([
            'success' => true,
            'message' => ' Bid Status',
            'data' => $list
        ], Response::HTTP_OK);
       
    }

    // public function seller_offer_list()
    // {
    //     $slist = Bidauction::where('user_created_by_id',$this->user->id)->get();
    //     return Response()->json([
    //         'success' => true,
    //         'message' => ' Bid Status',
    //         'data' => $slist
    //     ], Response::HTTP_OK);

    // }
    
    // public function accept_reject(Request $request)
    // {
    //     $data = $request->only('status','bid_id');
    //     $validator = Validator::make($data, [
    //         'status' => 'required',
    //         'bid_id'=> 'required',
    //     ]);
 
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid Input',
    //             'error' => $validator->messages()], 400);

    //     }

        
    //     try{ 

    //         //Request is valid, update Store
    //         $bid = Bidauction::where('id',$request->bid_id)->update([
    //             'status'=> $request->status,
    //         ]);
           
    //         //Store updated, return success response

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Status updated successfully',
    //         ], Response::HTTP_OK);
    //     } catch (JWTException $exception) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Something went wrong! Please try again'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }


    // }
}
