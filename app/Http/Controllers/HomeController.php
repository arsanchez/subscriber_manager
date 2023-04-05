<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Response;
use Validator;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        // Checking for api key 
        $settings = Setting::first() ?? false;
        return view('index', [
            'settings' => $settings
        ]);
    }

    public function saveKey(Request $request) 
    {
        $rules = ['key' => 'required|min:30'];
        $validator = Validator::make($request->all(), $rules);

        // Validate the input and return correct response
        if ($validator->fails())
        {
            $errors = implode(', ', $validator->getMessageBag()->toArray()['key']);
            return $this->errorResponse($errors);
        }
        
        // The API key looks good, we now test it against Mailerlite API
        $apiKey = $request->input('key');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->get('https://connect.mailerlite.com/api/subscribers')->getBody()->getContents();
        $data = json_decode($response);

        if (isset($data->message)) {
            return $this->errorResponse($data->message);
        }

        // At this point the API key should be good so we now save it
        Setting::create(['key' => $apiKey]);
        return Response::json([
            'success' => true
        ], 201);
    }   

    private function errorResponse($message = 'there was an unexpected error') {
        return Response::json(array(
            'success' => false,
            'errors' => $message
        ), 400);
    }
}
