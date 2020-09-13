<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url = url("api/getpackageslist");
        
        $token = env('API_TOKEN');
        $headers = [
            'Authorization' => 'Bearer ' . $token,        
            'Accept'        => 'application/json',
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url, [
            'headers' => $headers
        ])->getBody()->getContents();

        //dd($response);

        return view('welcome',['res'=>json_decode($response)]);
    }
}
