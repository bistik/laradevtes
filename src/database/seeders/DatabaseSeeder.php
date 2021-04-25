<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            [
                'name' => 'John Doe',
                'user_name' => 'johndoe',
                'email' => 'johndoe@example.com',
                'user_role' => 'admin',
                'active' => true,
            ],
            [
                'password' => Hash::make('secret'),
            ],
        );
    }
}
