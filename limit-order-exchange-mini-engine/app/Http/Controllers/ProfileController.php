<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $response = ['message' => 'Order creation failed','status' => 'error','data' => null];
        try {
            $user = Auth::user()->load('assets');
            $response['message'] = 'Profile fetched successfully';
            $response['status'] = 'success';
            $response['data'] = $user;
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json($response, 500);
        }
    }
}
