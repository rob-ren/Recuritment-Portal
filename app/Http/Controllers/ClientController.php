<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Recruiter;

class ClientController extends Controller
{
    protected $client_model;
    protected $recruiter_model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->recruiter_model = new Recruiter();
        $this->client_model = new Client();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = $this->client_model->getClients();
        return view('clientList', array(
          'clients' => $clients));
    }

    public function store(Request $request)
    {
        $currentTime = new \DateTime();
        $client = array(
          'name' => $request->name,
          'created_at' => $currentTime,
          'updated_at' => $currentTime
        );

        if ($this->client_model->createClient($client)) {
            return redirect()->back()->with('client_success', 'Client created successfully');
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $currentTime = new \DateTime();
        $client = array(
          'id' => $id,
          'name' => $request->name,
          'updated_at' => $currentTime
        );
        if ($this->client_model->updateClient($client)) {
            return redirect()->back()->with('client_success', 'Client updated successfully');
        }
        return redirect()->back();
    }
}
