<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VisitorController extends Controller
{
    public function visitorRequest(Request $request){

        $ip = $request->ip();
        $visitorName = $request->input('visitor_name');

        if( !$visitorName or $visitorName === null ){
            return response()->json(['status' => 'error','message' => 'Missing query params'],400);
        }

        $weatherApiKey = env('WEATHER_API_KEY');

        $weatherResponse = Http::get("http://api.weatherapi.com/v1/current.json?key={$weatherApiKey}&q={$ip}");


        if ($weatherResponse->failed()) {
            return response()->json(['status' => 'error','message' => 'Kindly try again something went wrong.'], 500);
        }

        $weatherData = $weatherResponse->json();

        $location = $weatherData['location'];

        $exact_location = $location['region']. ','.$location['country'];

        $temperature = $weatherData['current']['temp_c'];


        return response()->json([
            'status' => 'success',
            'message' =>['client_ip' => $ip,'location' =>  $exact_location,'greeting' => "Hello, $visitorName !, the temperature is  $temperature degree celcius in  $exact_location"]
            ],200);

    }
}
