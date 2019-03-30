<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Recruiter;

class RecruiterController extends Controller
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
        $recruiters = $this->recruiter_model->getRecruiters();
        return view('recruiterList', array(
          'recruiters' => $recruiters));
    }

    public function store(Request $request)
    {
        $currentTime = new \DateTime();
        $recruiter = array(
          'name' => $request->name,
          'created_at' => $currentTime,
          'updated_at' => $currentTime
        );

        if ($this->recruiter_model->createRecruiter($recruiter)) {
            return redirect()->back()->with('recruiter_success', 'Recruiter created successfully');
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $currentTime = new \DateTime();
        $recruiter = array(
          'id' => $id,
          'name' => $request->name,
          'updated_at' => $currentTime
        );
        if ($this->recruiter_model->updateRecruiter($recruiter)) {
            return redirect()->back()->with('recruiter_success', 'Recruiter updated successfully');
        }
        return redirect()->back();
    }
}
