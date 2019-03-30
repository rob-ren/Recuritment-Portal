<?php

namespace App\Http\Controllers;

use App\Position;
use App\PositionCandidate;
use Illuminate\Http\Request;
use App\Candidate;
use App\Client;
use App\Interviews;
use App\Recruiter;
use App\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    protected $candidate_model;
    protected $position_model;
    protected $position_candidate_model;
    protected $client_model;
    protected $recruiter_model;
    protected $role_model;
    protected $interview_model;

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
        $this->interview_model = new Interviews();
        $this->position_model = new Position();
        $this->position_candidate_model = new PositionCandidate();
    }

    public static function EnumToArray($class)
    {
        $reflect = new \ReflectionClass ($class);
        $constants = $reflect->getConstants();
        return $constants;
    }

    public function validatorCreate(array $data, $message = null)
    {
        return Validator::make($data, [
          'title' => 'required|string|max:255',
          'client_id' => 'exists:clients,id',
        ], $message);
    }

    /**
     * custom the message array
     *
     * @return array
     */
    public function messages()
    {
        return [
          'title.required' => 'You must input title',
          'client_id.exists' => 'Please select a client.'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $positions = $this->position_model->getPositionsByCriteria(array('status' => 'open'));
        $clients = $this->client_model->getClients();
        $criteria = array('client_id' => 'all', 'status' => 'open');
        $position_status_array = $this->EnumToArray('App\Enum\PositionStatus');
        return view('positionList', array(
          'position_status_array' => $position_status_array,
          'positions' => $positions,
          'clients' => $clients,
          'criteria' => $criteria));
    }

    public function query(Request $request)
    {
        $criteria = array(
          'client_id' => $request->client_id,
          'status' => $request->status
        );
        $positions = $this->position_model->getPositionsByCriteria($criteria);
        $clients = $this->client_model->getClients();
        $position_status_array = $this->EnumToArray('App\Enum\PositionStatus');
        if ($positions) {
            return view('positionList', array(
              'position_status_array' => $position_status_array,
              'positions' => $positions,
              'clients' => $clients,
              'criteria' => $criteria));
        }
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = $this->client_model->getClients();
        return view('positionCreate', array(
          'clients' => $clients));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currentTime = new \DateTime();

        $this->validatorCreate($request->all(), $this->messages())->validate();

        $position = array(
          'title' => $request->title,
          'client_id' => $request->client_id,
          'description' => $request->description,
          'status' => $request->status,
          'closed_date' => $request->status == 'closed' ? new \DateTime($request->closed_date) : null,
          'created_at' => $currentTime,
          'updated_at' => $currentTime
        );

        $positions = $this->position_model->getPositionsByCriteria(array('status' => 'open'));
        $clients = $this->client_model->getClients();
        $position_status_array = $this->EnumToArray('App\Enum\PositionStatus');
        $position_id = $this->position_model->createPosition($position);
        if ($position_id) {
            $positions = $this->position_model->getPositionsByCriteria(array('status' => 'open'));
        }
        $criteria = array('client_id' => 'all', 'status' => 'open');
        return view('positionList', array(
          'position_status_array' => $position_status_array,
          'clients' => $clients,
          'positions' => $positions,
          'criteria' => $criteria));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidates = $this->candidate_model->getCandidates();
        $candidate_array = array();
        foreach ($candidates as $candidate) {
            if($candidate->completely_avoid){
                continue;
            }
            $candidate_array[$candidate->id] = ucfirst(sprintf('%s %s, %s', $candidate->first_name, $candidate->last_name, $candidate->role_name));
        }
        $position = $this->position_model->getPositionById($id);
        $positions_candidates = $this->position_candidate_model->getPositionsCandidatesByPositionID($id);
        $clients = $this->client_model->getClients();
        $position_candidate_status_array = $this->EnumToArray('App\Enum\PositionCandidateStatus');
        $position_status_array = $this->EnumToArray('App\Enum\PositionStatus');
        return view('positionUpdate', array(
          'position_status_array' => $position_status_array,
          'position_candidate_status_array' => $position_candidate_status_array,
          'candidates' => $candidate_array,
          'positions_candidates' => $positions_candidates,
          'position' => $position,
          'clients' => $clients));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currentTime = new \DateTime();

        $position = array(
          'id' => $id,
          'title' => $request->title,
          'description' => $request->description,
          'status' => $request->status,
          'closed_date' => $request->status == 'closed' ? new \DateTime($request->closed_date) : null,
          'client_id' => $request->client_id,
          'updated_at' => $currentTime
        );

        if ($this->position_model->updatePosition($position)) {
            return redirect()->back()->with('position_success', 'Position updated successfully');
        };
        return redirect()->back();
    }
}
