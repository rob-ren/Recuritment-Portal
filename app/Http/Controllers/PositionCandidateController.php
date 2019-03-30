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

class PositionCandidateController extends Controller
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

    public function validatorCreate(array $data, $message = null, $position_id)
    {
        return Validator::make($data, [
          'candidate_id' => 'required|uniqueCandidatePosition:' . $position_id,
          'candidate_name' => 'required',
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
          'candidate_id.required' => 'There are no candidates matched',
          'candidate_name.required' => 'There are no candidates matched',
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $position_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $position_id)
    {
        $currentTime = new \DateTime();

        $this->validatorCreate($request->all(), $this->messages(), $position_id)->validate();

        $position_candidate = array(
          'position_id' => $position_id,
          'candidate_id' => $request->candidate_id,
          'comments' => $request->comments,
          'status' => $request->candidate_status,
          'interview_time' => $request->interview_time ? new \DateTime($request->interview_time) : null,
          'created_at' => $currentTime,
          'updated_at' => $currentTime
        );
        if ($this->position_candidate_model->createPositionCandidate($position_candidate)) {
            return redirect()->route('positionUpdate', $position_id)->with('candidate_success', 'Candidate created successfully');
        }
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $position_candidate_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($position_candidate_id)
    {
        $id = $this->position_candidate_model->getPositionCandidateById($position_candidate_id)->position_id;
        $position = $this->position_model->getPositionById($id);
        $positions_candidates = $this->position_candidate_model->getPositionsCandidatesByPositionID($id);
        $clients = $this->client_model->getClients();
        return view('positionUpdate', array(
          'positions_candidates' => $positions_candidates,
          'position' => $position,
          'clients' => $clients));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $position_candidate_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $position_candidate_id)
    {
        $currentTime = new \DateTime();

        $candidate_id = $this->position_candidate_model->getPositionCandidateById($position_candidate_id)->candidate_id;
        $position_id = $this->position_candidate_model->getPositionCandidateById($position_candidate_id)->position_id;

        $position_candidate = array(
          'id' => $position_candidate_id,
          'comments' => $request->comments,
          'status' => $request->candidate_status,
          'interview_time' => $request->interview_time,
          'rate' => $request->rate,
          'updated_at' => $currentTime
        );

        $candidate = array(
          'id' => $candidate_id,
          'notice_period' => $request->notice_period,
          'visa_status' => $request->visa_status,
          'number_years_experience' => $request->number_years_experience,
          'reason_of_leaving' => $request->reason_of_leaving,
          'communication_skills' => $request->communication_skills,
          'completely_avoid' => $request->completely_avoid
        );

        if ($this->position_candidate_model->updatePositionCandidate($position_candidate) && $this->candidate_model->updateCandidate($candidate)) {
            return redirect()->route('positionUpdate', $position_id)->with('candidate_success', 'Candidate updated successfully');
        };
        return redirect()->back();
    }

    /**
     * Delete the specified resource in storage.
     *
     * @param $position_candidate_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($position_candidate_id)
    {
        $position_id = $this->position_candidate_model->getPositionCandidateById($position_candidate_id)->position_id;

        if ($this->position_candidate_model->removePositionCandidate($position_candidate_id)) {
            return redirect()->route('positionUpdate', $position_id)->with('candidate_success', 'Candidate removed successfully');
        };
        return redirect()->back();
    }
}
