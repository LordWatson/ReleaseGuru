<?php

namespace Database\Seeders;

use App\Enums\FeatureTypeEnum;
use App\Models\Feature;
use App\Models\Project;
use App\Models\Release;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $releases = Release::all();
        $developers = User::where('role_id', 2)->pluck('id');
        $projects = Project::pluck('id');

        $types = FeatureTypeEnum::cases();
        $tagsPool = ['quoting', 'ordering', 'payments', 'auth', 'dashboard', 'optimization', 'maintenance', 'api'];

        foreach($releases as $release){
            for($i = 1; $i <= rand(3, 15); $i++){
                $type = $types[array_rand($types)]->value;

                Feature::create([
                    'release_id' => $release->id,
                    'developer_id' => $developers->random(),
                    'project_id' => $projects->random(),
                    'title' => fake()->sentence(4),
                    'description' => fake()->paragraphs(2, true),
                    'type' => $type,
                    'branch' => "$type/" . fake()->slug(3),
                    'api_id' => rand(0, 1) ? fake()->numberBetween(1000, 9999) : null,
                    'status' => collect(['open', 'approved', 'rejected', 'in progress' ,'completed'])->random(),
                    'tags' => array_rand(array_flip($tagsPool), rand(1, 4)),
                ]);
            }
        }
    }
}
