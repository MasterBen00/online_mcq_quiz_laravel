<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    //
    public function index()
    {

        return Auth::guard('api')->user();
        $response = ['message' => 'only authenticated users can see this message'];
        return response($response, 200);
    }
}
