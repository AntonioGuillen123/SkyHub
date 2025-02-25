<?php

namespace App;

use DateTime;
use Illuminate\Support\Facades\DB;

trait CreatePersonalAccessClient
{
    public function createPersonalAccessClient(){
        DB::table('oauth_clients')->insert([
            'id' => 1,
            'name' => 'Personal Access Client',
            'secret' => bcrypt('secret-key-for-tests'),
            'redirect' => 'http://localhost',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => 1,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);

        config([
            'passport.personal_access_client.id' => 1,
            'passport.personal_access_client.secret' => 'secret-key-for-tests',
        ]);
    }
}
