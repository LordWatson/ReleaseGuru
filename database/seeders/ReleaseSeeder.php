<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Release;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReleaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $developers = User::where('role_id', 2)->get();

        foreach($projects as $project){
            for($i = 1; $i <= 10; $i++){
                Release::create([
                    'project_id' => $project->id,
                    'release_date' => now()->addDays(rand(-30, 30)),
                    'released_by' => $developers->random()->id,
                    'name' => "Release {$i}.0",
                    'description' => fake()->sentence(),
                    'branch' => "release/{$i}.0",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
