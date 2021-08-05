<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Models\parent_companies;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    //
    public function upload(Request $request){
    $validator = Validator::make($request->all(), [
        'user_id'=>'required|numeric',
        'type' => 'required|string',
        'caption'=>'required|string',
        'title' => 'required|string',
        'image' => 'required|image:jpeg,png,jpg,gif,svg|max:5048',
    ]);
    
    
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => implode(" ", $validator->messages()->all()),
            'error' => $validator->messages()
        ], 400);
    }

    $uploadFolder = 'gallery';
    $image = $request->file('image');
    $image_uploaded_path = $image->store($uploadFolder, 'public');

    $file = parent_companies::create([
        'type' => $request->type,

        'title' => $request->title,
        'path' => $image_uploaded_path,
        

    ]);

    return response()->json([
        'success' => true,
        'message' => 'Image uploaded successfully',
    ], Response::HTTP_OK);

}
}
