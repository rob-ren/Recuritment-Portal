<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Client;
use App\Recruiter;
use App\Role;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $candidate_model;
    protected $client_model;
    protected $recruiter_model;
    protected $role_model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->candidate_model = new Candidate();
        $this->role_model = new Role();
        $this->recruiter_model = new Recruiter();
        $this->client_model = new Client();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidates = $this->candidate_model->getCandidates();
        $roles = $this->role_model->getRoles();
        $clients = $this->client_model->getClients();
        $recruiters = $this->recruiter_model->getRecruiters();
        $criteria = array('role_id' => 'all', 'rate' => 10, 'created_at' => 'all');
        return view('home', array(
          'candidates' => $candidates,
          'roles' => $roles,
          'clients' => $clients,
          'recruiters' => $recruiters,
          'criteria' => $criteria));
    }

    public function admin(Request $req)
    {
        return view('unauthorized')->withMessage("Admin");
    }
}
