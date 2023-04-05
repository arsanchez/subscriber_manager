<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;

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

        // Getting the subscriber from the mailer lite API
        $next = $request->session()->get('next_page', false);
        $prev = $request->session()->get('prev_page', false);

        $response = Http::withHeaders( $this->defultHeaders )
                    ->get('https://connect.mailerlite.com/api/subscribers', ['limit' => $length])
                    ->getBody()->getContents();

        $subscribers = json_decode($response);
        
        // Getting the total count 
        $response = Http::withHeaders( $this->defultHeaders )
                    ->get('https://connect.mailerlite.com/api/subscribers?limit=0')
                    ->getBody()->getContents();
        $subscribersTotal = json_decode($response);
        return Response::json($this->parseDatatablesResponse($subscribers->data, $subscribersTotal->total), 201);
    }

    private function parseDatatablesResponse($data, $count) {
        $response  = new \stdClass();
        $response->draw = 1;
        $response->recordsTotal = $count;
        $response->recordsFiltered = $count;
        $response->data = [];

        // Parsing the resposne data 
        foreach ($data as $row) {
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

            $response->data[] = $tempDataRow;
        }

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
