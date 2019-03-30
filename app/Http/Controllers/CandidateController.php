<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Client;
use App\Interviews;
use App\PositionCandidate;
use App\Recruiter;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    protected $candidate_model;
    protected $client_model;
    protected $recruiter_model;
    protected $role_model;
    protected $interview_model;
    protected $position_candidate_model;

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
          'first_name' => 'required|string|max:255',
          'last_name' => 'required|string|max:255',
          'role_id' => 'exists:roles,id',
          'client_id' => 'exists:clients,id',
          'recruiter_id' => 'exists:recruiters,id',
        ], $message);
    }

    public function validatorUpdate(array $data, $message = null, $id = null)
    {
        return Validator::make($data, [
          'first_name' => 'required|string|max:255',
          'last_name' => 'required|string|max:255',
            //'email' => ['required', Rule::unique('candidates')->ignore($id)]
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
          'first_name.required' => 'You must input first name',
          'last_name.required' => 'You must input last name',
          'email.required' => 'You must input email',
          'email.unique' => 'This email already existed.',
          'role_id.exists' => 'Please select a role.',
          'client_id.exists' => 'Please select a client.',
          'recruiter_id.exists' => 'Please select a recruiter.'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidates = $this->candidate_model->getCandidates();
        $roles = $this->role_model->getRoles();
        $recruiters = $this->recruiter_model->getRecruiters();
        $criteria = array('role_id' => 'all', 'rate' => 'all', 'created_at' => 'all');
        return view('candidateList', array(
          'candidates' => $candidates,
          'roles' => $roles,
          'recruiters' => $recruiters,
          'criteria' => $criteria));
    }

    public function query(Request $request)
    {
        $criteria = array(
          'created_at' => $request->created_at,
          'role_id' => $request->role_id,
          'rate' => $request->rate
        );
        $candidates = $this->candidate_model->getCandidatesByCriteria($criteria);
        $roles = $this->role_model->getRoles();
        $clients = $this->client_model->getClients();
        $recruiters = $this->recruiter_model->getRecruiters();
        if ($candidates) {
            return view('candidateList', array(
              'candidates' => $candidates,
              'roles' => $roles,
              'clients' => $clients,
              'recruiters' => $recruiters,
              'criteria' => $criteria));
        }
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($positon_id = null)
    {
        $roles = $this->role_model->getRoles();
        $recruiters = $this->recruiter_model->getRecruiters();
        return view('candidateCreate', array(
          'roles' => $roles,
          'recruiters' => $recruiters,
          'position_id' => $positon_id));
    }

    private function upload(Request $request)
    {
        $file_absolute_path = '';
        if ($request->hasFile('cv_file_path')) {
            $cv_file = $request->file('cv_file_path');
            $file_name = md5(uniqid()) . '.' . $cv_file->getClientOriginalExtension();
            $file_absolute_path = 'upload' . DIRECTORY_SEPARATOR . 'cv_files' . DIRECTORY_SEPARATOR . $file_name;
            $cv_file->move(public_path() . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . 'cv_files' . DIRECTORY_SEPARATOR, $file_name);
        }
        return $file_absolute_path;
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

        // upload cv file
        $file_absolute_path = $this->upload($request);
        if ($request->cv_file_path && !$file_absolute_path) {
            return redirect()->back()->with('error_msg', 'Upload CV failed, Please try again');
        }

        $candidate = array(
          'first_name' => $request->first_name,
          'last_name' => $request->last_name,
          'email' => $request->email,
          'phone' => $request->phone,
          'wage' => $request->wage,
          'rate' => $request->rate,
          'cv_file_path' => $file_absolute_path,
          'comments' => $request->comments,
          'role_id' => $request->role_id,
          'recruiter_id' => $request->recruiter_id,
          'notice_period' => $request->notice_period,
          'visa_status' => $request->visa_status,
          'number_years_experience' => $request->number_years_experience,
          'reason_of_leaving' => $request->reason_of_leaving,
          'communication_skills' => $request->communication_skills,
          'completely_avoid' => $request->completely_avoid,
          'created_at' => $currentTime,
          'updated_at' => $currentTime
        );

        $candidates = $this->candidate_model->getCandidates();
        $roles = $this->role_model->getRoles();
        $recruiters = $this->recruiter_model->getRecruiters();
        $criteria = array('role_id' => 'all', 'rate' => 10, 'created_at' => 'all');

        $candidate_id = $this->candidate_model->createCandidate($candidate);
        if ($candidate_id) {
            $candidates = $this->candidate_model->getCandidates();
        };

        //create connection with position as well
        if ($request->position_id) {
            $position_candidate = array(
              'position_id' => $request->position_id,
              'candidate_id' => $candidate_id,
              'status' => 'interview_1_to_be_scheduled',
              'created_at' => $currentTime,
              'updated_at' => $currentTime
            );
            if ($this->position_candidate_model->createPositionCandidate($position_candidate)) {
                return redirect()->route('positionUpdate', $request->position_id)->with('candidate_success', 'Candidate created successfully');
            }
        }
        return view('candidateList', array(
          'candidates' => $candidates,
          'roles' => $roles,
          'recruiters' => $recruiters,
          'criteria' => $criteria));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidate = $this->candidate_model->getCandidateById($id);
        $positions = $this->position_candidate_model->getPositionsCandidatesByCandidateID($id);
        $roles = $this->role_model->getRoles();
        $recruiters = $this->recruiter_model->getRecruiters();
        $position_status_array = $this->EnumToArray('App\Enum\PositionStatus');
        $position_candidate_status_array = $this->EnumToArray('App\Enum\PositionCandidateStatus');
        return view('candidateUpdate', array(
          'candidate' => $candidate,
          'positions' => $positions,
          'roles' => $roles,
          'recruiters' => $recruiters,
          'position_status_array' => $position_status_array,
          'position_candidate_status_array' => $position_candidate_status_array));
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

        // validate the current form post
        $this->validatorUpdate($request->all(), $this->messages(), $id)->validate();

        //get the current candidate by id
        $current_candidate = $this->candidate_model->getCandidateById($id);

        // upload cv file
        $file_absolute_path = $this->upload($request);

        // get file upload from request but have not upload successfully
        if ($request->cv_file_path && !$file_absolute_path) {
            return redirect()->back()->with('error_msg', 'Upload CV failed, Please try again');
        }

        // already have cv file and have not upload new one, then apply the old path
        if ($current_candidate->cv_file_path && !$file_absolute_path) {
            $file_absolute_path = $current_candidate->cv_file_path;
        }

        $candidate = array(
          'id' => $id,
          'first_name' => $request->first_name,
          'last_name' => $request->last_name,
          'email' => $request->email,
          'phone' => $request->phone,
          'wage' => $request->wage ? $request->wage : $current_candidate->wage,
          'rate' => $request->rate,
          'cv_file_path' => $file_absolute_path,
          'comments' => $request->comments,
          'role_id' => $request->role_id,
          'recruiter_id' => $request->recruiter_id,
          'notice_period' => $request->notice_period,
          'visa_status' => $request->visa_status,
          'number_years_experience' => $request->number_years_experience,
          'reason_of_leaving' => $request->reason_of_leaving,
          'communication_skills' => $request->communication_skills,
          'completely_avoid' => $request->completely_avoid,
          'updated_at' => $currentTime
        );

        if ($this->candidate_model->updateCandidate($candidate)) {
            return redirect()->back()->with('candidate_success', 'Candidate updated successfully');
        };
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function downloadCV($file_path)
    {
        $decrypt_file_path = Crypt::decryptString($file_path);
        return response()->download($decrypt_file_path);
    }
}
