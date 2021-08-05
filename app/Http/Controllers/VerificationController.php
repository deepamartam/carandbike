<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class VerificationController extends Controller
{
    public function verify($user_id, Request $request) {
        if (!$request->hasValidSignature()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid/Expired url provided',
            ], 400);
        }
    
        $user = User::findOrFail($user_id); 
    
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Email verified.',
        ], 200);
        //return redirect()->to('/');
    }
    
    public function resend() {
        if (auth()->user()->hasVerifiedEmail()) {
            return response()->json(['success' => false,
            'message' => "Email already verified."], 400);
        }
    
        auth()->user()->sendEmailVerificationNotification();
        
        return response()->json(['success' => true,
            'message' => "Email verification link sent on your email id"], 200);

    }
    
}
