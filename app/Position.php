<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Position extends Model
{
    protected $table = 'positions';

    public function createPosition($position)
    {
        $position_id = DB::table('positions')->insertGetId($position, 'id');
        return $position_id;
    }

    public function getPositionById($id)
    {
        $positions = DB::table('positions')->where('id', $id)->first();
        return $positions;
    }

    public function getPositions()
    {
        $positions = DB::table('positions')
          ->leftJoin('clients', 'clients.id', '=', 'positions.client_id')
          ->leftJoin('positions_candidates', 'positions_candidates.position_id', '=', 'positions.id')
          ->select('positions.id', 'positions.title', 'positions.created_at', 'positions.closed_date', 'positions.description', 'positions.status', 'clients.name as client_name', DB::raw('count(positions_candidates.id) as applied_count'))
          ->groupBy('positions.id')
          ->orderBy('positions.id', 'desc');
        return $positions->get();
    }

    public function getPositionsByCriteria(array $criteria)
    {
        $positions = DB::table('positions')
          ->leftJoin('clients', 'clients.id', '=', 'positions.client_id')
          ->leftJoin('positions_candidates', 'positions_candidates.position_id', '=', 'positions.id')
          ->select('positions.id', 'positions.title', 'positions.created_at', 'positions.closed_date', 'positions.description', 'positions.status', 'clients.name as client_name', DB::raw('count(positions_candidates.id) as applied_count'))
          ->groupBy('positions.id')
          ->orderBy('positions.id', 'desc');
        // apply the criteria
        if (array_key_exists('status', $criteria) && $criteria['status'] != 'all') {
            $positions
              ->where('positions.status', '=', $criteria['status']);
        }
        if (array_key_exists('client_id', $criteria) && $criteria['client_id'] != 'all') {
            $positions
              ->where('positions.client_id', '=', $criteria['client_id']);
        }

        return $positions->get();
    }

    public function updatePosition($position)
    {
        DB::table('positions')
          ->where('id', $position['id'])
          ->update($position);
        return $position;
    }
}
