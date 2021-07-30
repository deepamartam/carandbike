<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\parent_companies;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ParentCompanyController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    
    public function createCompany(Request $request , User $user)
    {
        $data = $request->only('company_name','contact_person','subsidiary_id');
        $validator = Validator::make($data, [
         'company_name'=> 'required|string',
         'contact_person'=>'required|string',
         'subsidiary_id' => 'required|numeric',

          
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ",$validator->messages()->all()),
                'error' => $validator->messages()], 400);
        }

        $company = parent_companies::create([
         'company_name' => $request->company_name,
         'contact_person' => $request->contact_person,
         'user_id' => $user->id,
         
        ]);

        return response()->json([
         'success' => true,
         'message' => 'Company created successfully',
        ], Response::HTTP_OK);

     
   }

   public function updateCompany(Request $request, User $user)
    {

      $data = $request->only('company_name','contact_person','subsidiary_id');
      $validator = Validator::make($data, [
         'company_name'=> 'required|string',
         'contact_person'=> 'required|string',
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

             $companyupdate = parent_companies::where('user_id', $user->id)->update([
                 'company_name' => $request->company_name,
                 'contact_person' => $request->contact_person,
             ]);

             return response()->json([
                 'success' => true,
                 'message' => 'Company Profile updates successfully',
             ], 200);
         } catch (JWTException $exception) {
             return response()->json([
                 'success' => false,
                 'message' => 'Something went wrong! Please try again'
             ], Response::HTTP_INTERNAL_SERVER_ERROR);
         }
    }

}