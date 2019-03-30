<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Recruiter extends Model
{
    protected $table = 'recruiters';

    public function getRecruiters()
    {
        $recruiters = DB::select('select * from recruiters order by name');
        return $recruiters;
    }

    public function createRecruiter($recruiter)
    {
        $recruiter_id = DB::table('recruiters')->insertGetId($recruiter, 'id');
        return $recruiter_id;
    }

    public function updateRecruiter($recruiter)
    {
        DB::table('recruiters')
          ->where('id', $recruiter['id'])
          ->update($recruiter);
        return $recruiter;
    }
}
