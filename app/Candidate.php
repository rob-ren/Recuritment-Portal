<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Candidate extends Model
{
    protected $table = 'candidates';

    protected $fillable = array('first_name', 'last_name', 'email', 'phone', 'rate', 'cv_file_path', 'comments');

    public function role()
    {
        return $this->belongsTo('App\Role', 'role_id');
    }

    public function createCandidate($candidate)
    {
        $candidate_id = DB::table('candidates')->insertGetId($candidate, 'id');
        return $candidate_id;
    }

    public function getCandidateById($id)
    {
        $candidate = DB::table('candidates')->where('id', $id)->first();
        return $candidate;
    }

    public function getCandidatesByCriteria(array $criteria)
    {
        $candidates = DB::table('candidates')
          ->leftJoin('roles', 'roles.id', '=', 'candidates.role_id')
          ->leftJoin('recruiters', 'recruiters.id', '=', 'candidates.recruiter_id')
          ->leftJoin('interviews', 'interviews.candidate_id', '=', 'candidates.id')
          ->select('candidates.first_name', 'candidates.last_name', 'candidates.created_at', 'candidates.rate', 'candidates.id', 'roles.name as role_name', 'recruiters.name as recruiter_name', 'interviews.interview_time')
          ->orderBy('candidates.id', 'desc');
        // apply the criteria
        if (array_key_exists('created_at', $criteria) && $criteria['created_at'] != 'all') {
            if ($criteria['created_at'] == 'greater_6month') {
                $candidates
                  ->where('candidates.created_at', '<=', Carbon::now()->subMonth(6));
            }
            if ($criteria['created_at'] == 'less_6month') {
                $candidates
                  ->where('candidates.created_at', '<=', now())
                  ->where('candidates.created_at', '>', Carbon::now()->subMonth(6));
            }
        }
        if (array_key_exists('rate', $criteria) && $criteria['rate'] != 'all') {
            $candidates
              ->where('candidates.rate', '>=', $criteria['rate']);
        }
        if (array_key_exists('role_id', $criteria) && $criteria['role_id'] != 'all') {
            $candidates
              ->where('candidates.role_id', '=', $criteria['role_id']);
        }

        return $candidates->get();
    }

    public function getCandidates()
    {
        $candidates = DB::table('candidates')
          ->leftJoin('roles', 'roles.id', '=', 'candidates.role_id')
          ->leftJoin('recruiters', 'recruiters.id', '=', 'candidates.recruiter_id')
          ->leftJoin('interviews', 'interviews.candidate_id', '=', 'candidates.id')
          ->select('candidates.first_name', 'candidates.last_name', 'candidates.created_at', 'candidates.rate', 'candidates.id', 'roles.name as role_name', 'recruiters.name as recruiter_name', 'interviews.interview_time', 'candidates.completely_avoid')
          ->orderBy('candidates.id', 'desc');
        return $candidates->get();
    }

    public function updateCandidate($candidate)
    {
        DB::table('candidates')
          ->where('id', $candidate['id'])
          ->update($candidate);
        return $candidate;
    }
}
