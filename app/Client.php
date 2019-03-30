<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    protected $table = 'clients';

    public function getClients()
    {
        $clients = DB::select('select * from clients order by name');
        return $clients;
    }

    public function createClient($client)
    {
        $client_id = DB::table('clients')->insertGetId($client, 'id');
        return $client_id;
    }

    public function updateClient($client)
    {
        DB::table('clients')
          ->where('id', $client['id'])
          ->update($client);
        return $client;
    }
}
