<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert([
           'nome' => 'APT 102',
            'id_owner' => '1'
        ]);
        DB::table('units')->insert([
            'nome' => 'APT 103',
            'id_owner' => '1'
        ]);
        DB::table('units')->insert([
            'nome' => 'APT 302',
            'id_owner' => '0'
        ]);
        DB::table('units')->insert([
            'nome' => 'APT 204',
            'id_owner' => '0'
        ]);

        DB::table('areas')->insert([
            'allowed' => '1',
            'title' => 'Academia',
            'cover' => 'gym.jpg',
            'days' => '1,2,3,4,5',
            'start_time' => '06:00:00',
            'end_time' => '22:00:00'
        ]);

        DB::table('areas')->insert([
            'allowed' => '1',
            'title' => 'Piscina',
            'cover' => 'pool.jpg',
            'days' => '1,2,3,4,5',
            'start_time' => '07:00:00',
            'end_time' => '23:00:00'
        ]);

        DB::table('areas')->insert([
            'allowed' => '1',
            'title' => 'Churrasqueira',
            'cover' => 'barbecue.jpg',
            'days' => '4,5,6',
            'start_time' => '09:00:00',
            'end_time' => '23:00:00'
        ]);

        DB::table('walls')->insert([
            'title' => 'Aviso 01',
            'body' => 'lorem flkpekfpekkpk f kf apskf sakf pask fks afkposa k',
            'datecreated' => '2020-12-20 15:00:00',
        ]);

        DB::table('walls')->insert([
            'title' => 'Aviso 02',
            'body' => 'lorem flkpekfpekkpk f kf apskf sakf pask fks afkposa k',
            'datecreated' => '2020-12-20 18:00:00',
        ]);
    }
}
