<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'Admin',
                'description' => 'Administrator with full access',
                'level' => 1,
            ],[
                'name' => 'Developer',
                'description' => 'A developer responsible for writing the code and maintaining the application.',
                'level' => 2,
            ],[
                'name' => 'QA',
                'description' => 'A QA engineer responsible for testing the application.',
                'level' => 3,
            ],[
                'name' => 'User',
                'description' => 'A standard user.',
                'level' => 4,
            ]
        ]);
    }
}
