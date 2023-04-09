<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Validator;
use Illuminate\Support\Facades\Response;
use Redirect;

class SubscriberController extends Controller
{
    private $apiKey = null;
    private $defultHeaders = [];

    public function __construct() {
        $settings = Setting::first();
        $this->apiKey = $settings->key;
        $this->defultHeaders = [
            'Authorization' => 'Bearer '.$this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        // Getting the page number and length
        $length = $request->input('length', 10);
        $start = $request->input('start', 0);
        $draw = $request->input('draw', 0);

        // Getting the next API cursor
        $url = 'https://connect.mailerlite.com/api/subscribers';
        $next = $request->session()->get('next_url', false);
        $prev = $request->session()->get('prev_url', false);
        $current_page = $request->session()->get('current_page', 1);

        $params = ['limit' => $length];
        if ( ($start > $current_page) && $next != false) {
            $params['cursor'] = $next;
        } else if ($start < $current_page && $prev != false) {
            $params['cursor'] = $prev;
        }


        // Getting the subscribers from the mailer lite API
        $response = Http::withHeaders( $this->defultHeaders )
                    ->get($url, $params)
                    ->getBody()->getContents();

        $subscribers = json_decode($response);
        
        $request->session()->put('current_page', $start);

        // Getting the total count 
        $response = Http::withHeaders( $this->defultHeaders )
                    ->get($url.'?limit=0')
                    ->getBody()->getContents();
        $subscribersTotal = json_decode($response);
        return Response::json($this->parseDatatablesResponse($subscribers, $subscribersTotal->total, $draw, $request), 201);
    }

    private function parseDatatablesResponse($response, $count, $draw, $request) {
        // Setting the lings
        $request->session()->put('next_url', $response->meta->next_cursor);
        $request->session()->put('prev_url', $response->meta->prev_cursor);

        $parsedResponse  = new \stdClass();
        $parsedResponse->draw = $draw;
        $parsedResponse->recordsTotal = $count;
        $parsedResponse->recordsFiltered = $count;
        $parsedResponse->data = [];

        // Parsing the resposne data 
        foreach ($response->data as $row) {
            // Parsing the date 
            $d = strtotime($row->subscribed_at);
            $subscribed_at_date = date("d/m/Y", $d);
            $subscribed_at_time = date("H:i:s", $d);
            $tempDataRow = [
                $row->email,
                $row->fields->name,
                $row->fields->country,
                $subscribed_at_date,
                $subscribed_at_time,
                $row->id,
            ];

            $parsedResponse->data[] = $tempDataRow;
        }

        return $parsedResponse;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sub-form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'country' => 'required|min:3', 
            'name' => 'required|min:2',
            'email => required|email'
        ];
        $validator = Validator::make($request->all(), $rules);

        // Validate the input
        if ($validator->fails())
        {
            $errors = $validator->getMessageBag()->toArray();
            $messages = '';
            foreach ($errors as $key => $e) {
                $messages .= implode(',', $e) .'<br>';
            }

            return $this->errorResponse($messages);
        }

         // Saving the subscriber info 
         $requestBody = new \stdClass();
         $requestBody->email = $request->input('email');
         $requestBody->fields = new \stdClass();
         $requestBody->fields->name = $request->input('name');
         $requestBody->fields->country = $request->input('country');

         $response = Http::withHeaders( $this->defultHeaders )
         ->withBody(json_encode($requestBody), 'application/json')
         ->post('https://connect.mailerlite.com/api/subscribers/')
         ->getBody()->getContents();

        $subscriber = json_decode($response);
        if (isset ($subscriber->data)) {
            return Response::json([
                'success' => true
            ], 201);
        } else {
            return $this->errorResponse($subscriber->message);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Getting the subscriber info 
        $response = Http::withHeaders( $this->defultHeaders )
                    ->get('https://connect.mailerlite.com/api/subscribers/' . $id)
                    ->getBody()->getContents();

        $subscriber = json_decode($response);
        if (isset($subscriber->message)) {
            return redirect()->action([HomeController::class, 'index'])->withErrors($subscriber->message);
        }

        return view('sub-form', [
            'subscriber' => $subscriber->data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = ['country' => 'required|min:3', 'name' => 'required|min:2'];
        $validator = Validator::make($request->all(), $rules);

        // Validate the input
        if ($validator->fails())
        {
            $errors = $validator->getMessageBag()->toArray();
            $messages = '';
            foreach ($errors as $key => $e) {
                $messages .= implode(',', $e) .'<br>';
            }

            return $this->errorResponse($messages);
        }

         // Saving the subscriber info 
         $requestBody = new \stdClass();
         $requestBody->fields = new \stdClass();
         $requestBody->fields->name = $request->input('name');
         $requestBody->fields->country = $request->input('country');

         $response = Http::withHeaders( $this->defultHeaders )
         ->withBody(json_encode($requestBody), 'application/json')
         ->put('https://connect.mailerlite.com/api/subscribers/' . $id)
         ->getBody()->getContents();

        $subscriber = json_decode($response);
        if (isset ($subscriber->data)) {
            return Response::json([
                'success' => true
            ], 201);
        } else {
            return $this->errorResponse($subscriber->message);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Deleting the subscriber
        $response = Http::withHeaders( $this->defultHeaders )
         ->delete('https://connect.mailerlite.com/api/subscribers/' . $id)
         ->getBody()->getContents();
    }
}
