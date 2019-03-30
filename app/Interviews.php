<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Interviews extends Model
{
    protected $table = 'interviews';

    public function createInterviews($interviews)
    {
        DB::table('interviews')->insert($interviews);
        return true;
    }

    public function getInterviewsByCandidateID($candidate_id)
    {
        //$interviews = DB::select('select * from interviews where candidate_id = ?', array($candidate_id));
        $interviews = DB::table('interviews')->where('candidate_id', $candidate_id)->first();
        return $interviews;
    }

    public function updateInterviews($interviews)
    {
        DB::table('interviews')
          ->where('id', $interviews['id'])
          ->update($interviews);
        return $interviews;
    }
}
