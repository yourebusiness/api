<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class verifyTokenController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:api');
    }

    public function index()
    {
    	$array = array('id' => 0, "message" => "Okay.");
    	return response()->json($array);
    }
}
