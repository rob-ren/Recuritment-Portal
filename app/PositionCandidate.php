<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PositionCandidate extends Model
{
    protected $table = 'positions_candidates';

    public function createPositionCandidate($position_candidate)
    {
        $position_candidate_id = DB::table('positions_candidates')->insertGetId($position_candidate, 'id');
        return $position_candidate_id;
    }

    public function getPositionCandidateById($id)
    {
        $positions_candidates = DB::table('positions_candidates')->where('id', $id)->first();
        return $positions_candidates;
    }

    public function getPositionsCandidatesByCandidateID($candidate_id)
    {
        $positions_candidates = DB::table('positions_candidates')
          ->leftJoin('candidates', 'candidates.id', '=', 'positions_candidates.candidate_id')
          ->leftJoin('positions', 'positions.id', '=', 'positions_candidates.position_id')
          ->leftJoin('clients', 'clients.id', '=', 'positions.client_id')
          ->select('positions_candidates.id', 'positions.id as position_id', 'positions.title', 'positions.created_at',
            'positions.closed_date', 'positions.description', 'positions.status as position_status', 'clients.name as client_name', 'positions_candidates.comments',
            'positions_candidates.interview_time', 'positions_candidates.status','positions_candidates.rate as position_rate',
            'candidates.notice_period', 'candidates.visa_status', 'candidates.number_years_experience',
            'candidates.reason_of_leaving', 'candidates.communication_skills','candidates.completely_avoid')
          ->where('positions_candidates.candidate_id', $candidate_id)
          ->orderBy('positions_candidates.id', 'desc');
        return $positions_candidates->get();
    }

    public function getPositionsCandidatesByPositionID($position_id)
    {
        $positions_candidates = DB::table('positions_candidates')
          ->leftJoin('candidates', 'candidates.id', '=', 'positions_candidates.candidate_id')
          ->leftJoin('positions', 'positions.id', '=', 'positions_candidates.position_id')
          ->leftJoin('clients', 'clients.id', '=', 'positions.client_id')
          ->select('positions_candidates.id', 'candidates.id as candidates_id', 'candidates.first_name',
            'candidates.last_name', 'positions.id as position_id', 'positions.title', 'positions.created_at',
            'positions.closed_date', 'positions.description', 'positions.status as position_status', 'clients.name as client_name',
            'positions_candidates.comments', 'positions_candidates.interview_time', 'positions_candidates.status','positions_candidates.rate as position_rate',
            'candidates.notice_period', 'candidates.visa_status', 'candidates.number_years_experience',
            'candidates.reason_of_leaving', 'candidates.communication_skills','candidates.completely_avoid')
          ->where('positions_candidates.position_id', $position_id)
          ->orderBy('positions_candidates.id', 'desc');
        return $positions_candidates->get();
    }

    public function updatePositionCandidate($position_candidate)
    {
        DB::table('positions_candidates')
          ->where('id', $position_candidate['id'])
          ->update($position_candidate);
        return $position_candidate;
    }

    public function removePositionCandidate($id)
    {
        DB::table('positions_candidates')
          ->where('id', $id)
          ->delete();
        return true;
    }
}
