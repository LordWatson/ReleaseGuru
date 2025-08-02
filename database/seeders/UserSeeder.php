<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $adminRole = Role::where('name', 'Admin')->first();

        Organization::create([
            'name' => 'SCG',
            'industry' => 'Software',
            'size' => 50,
        ]);

        // get the orgs
        $organizations = Organization::all();

        // admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@user.com',
            'org_id' => 1,
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
        ]);

        // qa user
        User::create([
            'name' => 'QA User',
            'email' => 'qa@user.com',
            'org_id' => 1,
            'password' => Hash::make('password'),
            'role_id' => 3,
            'email_verified_at' => now(),
        ]);

        // seed 3 developers
        foreach(range(1, 3) as $index){
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'role_id' => 2,
                'org_id' => 1,
                'email_verified_at' => now(),
                'remember_token' => null,
            ]);
        }

        // standard user
        User::create([
            'name' => 'Standard User',
            'email' => 'standard@user.com',
            'org_id' => 1,
            'password' => Hash::make('password'),
            'role_id' => 4,
            'email_verified_at' => now(),
        ]);
    }
}
