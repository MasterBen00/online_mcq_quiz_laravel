<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function index()
    {
        $response = ['message' => 'only authenticated users can see this message'];
        return response($response, 200);
    }
}
