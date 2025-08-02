<?php

namespace Database\Seeders;

use App\Models\BugReport;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BugReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = Feature::all();
        $qa = User::where('role_id', 3)->first();

        foreach($features as $feature){
            for($i = 0; $i < rand(0, 10); $i++){
                BugReport::create([
                    'feature_id' => $feature->id,
                    'created_by' => $qa->id,
                    'title' => 'Bug: ' . fake()->words(3, true),
                    'description' => fake()->paragraph,
                    'status' => collect(['open', 'in_progress', 'closed'])->random(),
                ]);
            }
        }
    }
}
