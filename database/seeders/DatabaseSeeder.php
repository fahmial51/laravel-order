<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        $accounts = [[
            'name' => 'Adrian Demian',
            'email' => 'admin@test.test',
            'password' => '12345678'
        ]];

        foreach ($accounts as $account) {
            // user
            $user = User::create([
                'name' => $account['name'],
                'email' => $account['email'],
                'password' => bcrypt($account['password'])
            ]);

            // api
            // $user->createToken('Personal');
        }
    }
}
