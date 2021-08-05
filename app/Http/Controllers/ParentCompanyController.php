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
        //$this->user = JWTAuth::parseToken()->authenticate();
    }

    public function createCompany(Request $request)
    {
        $data = $request->only(
            'user_id',
            'company_name',
            'contact_person',
            // 'subsidiary_id',
            'Image_path',
            'Company_logo_path',
            'Address',
            'Latitude',
            'Longitude',
            'No_Of_Dealers',
            'Establishment_Year'
        );
        $validator = Validator::make($data, [
            'company_name' => 'required|string',
            'contact_person' => 'required|string',
            'Image_path' => 'required|image:jpeg,png,jpg,gif,svg|max:5048',
            'Company_logo_path' => 'required|image:jpeg,png,jpg,gif,svg|max:5048',
            'Address' => 'required|string',
            'Latitude' => 'required|string',
            'Longitude' => 'required|string',
            // 'subsidiary_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'No_Of_Dealers' => 'required|numeric',
            'Establishment_Year' => 'required|numeric',


        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ", $validator->messages()->all()),
                'error' => $validator->messages()
            ], 400);
        }

        $uploadFolder = 'gallery';
        $image = $request->file('Image_path');
        $logo = $request->file('Company_logo_path');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $logo_uploaded_path = $logo->store($uploadFolder, 'public');

        $company = parent_companies::create([
            'company_name' => $request->company_name,
            'contact_person' => $request->contact_person,
            'user_id' => $request->user_id,
            'Image_path' => $image_uploaded_path,
            'Establishment_Year' => $request->Establishment_Year,
            'No_Of_Dealers' => $request->No_Of_Dealers,
            'Latitude' => $request->Latitude,
            'Longitude' => $request->Longitude,
            'Address' => $request->Address,
            'Company_logo_path' => $logo_uploaded_path,
            // 'subsidiary_id'=>$request->subsidiary_id,

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Company created successfully',
        ], Response::HTTP_OK);
    }

    public function updateCompany(Request $request, $id)
    {

        $data = $request->only(
            'user_id',
            'company_name',
            'contact_person',
            // 'subsidiary_id',
            'Image_path',
            'Company_logo_path',
            'Address',
            'Latitude',
            'Longitude',
            'No_Of_Dealers',
            'Establishment_Year'
        );

        $validator = Validator::make($data, [
            'company_name' => 'required|string',
            'contact_person' => 'required|string',
            'user_id' => 'required|numeric',
            'Image_path' => 'image:jpeg,png,jpg,gif,svg|max:5048',
            'Company_logo_path' => 'image:jpeg,png,jpg,gif,svg|max:5048',
            'Address' => 'required|string',
            'Latitude' => 'required|string',
            'Longitude' => 'required|string',
            'No_Of_Dealers' => 'required|numeric',
            'Establishment_Year' => 'required|numeric',
            // 'subsidiary_id' => 'required|numeric',




        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => implode(" ", $validator->messages()->all()),
                'error' => $validator->messages()
            ], 400);
        }


        $uploadFolder = 'gallery';


        $image = $request->file('Image_path');
        $logo = $request->file('Company_logo_path');

        try {

            $companyupdate = parent_companies::where('id', $id)->update([
                'company_name' => $request->company_name,
                'contact_person' => $request->contact_person,
                'Image_path' => $image?$image->store($uploadFolder, 'public'):parent_companies::where('id',$id)->pluck('Image_path'),
                'Establishment_Year' => $request->Establishment_Year,
                'No_Of_Dealers' => $request->No_Of_Dealers,
                'Latitude' => $request->Latitude,
                'Longitude' => $request->Longitude,
                'Address' => $request->Address,
                'Company_logo_path' =>$logo? $logo->store($uploadFolder, 'public'):parent_companies::where('id',$id)->pluck('Company_logo_path'),
                // 'subsidiary_id'=>$request->subsidiary_id,
                'user_id' => $request->user_id,



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
