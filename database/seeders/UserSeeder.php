<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Root', 'email' => 'root@root.com', 'password' => Hash::make('SecretPassword'), 'role_id' => 1, 'email_verified_at' => Carbon::now()->format('Y-m-d h:m:s')],
            ['name' => 'Root Witann', 'email' => 'root@witann.com', 'password' => Hash::make('SecretPassword'), 'role_id' => 1, 'email_verified_at' => Carbon::now()->format('Y-m-d h:m:s')],
            ['name' => 'Directivo', 'email' => 'direccion@tierrasinnombre.com', 'password' => Hash::make('password'), 'role_id' => 2, 'email_verified_at' => Carbon::now()->format('Y-m-d h:m:s')]
        ];
        User::insert($data);
    }
}
