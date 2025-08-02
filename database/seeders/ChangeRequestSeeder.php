<?php

namespace Database\Seeders;

use App\Models\ChangeRequest;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChangeRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = Feature::all();
        $qa = User::where('role_id', 3)->first();

        foreach($features as $feature){
            for($i = 0; $i < rand(0, 2); $i++){
                ChangeRequest::create([
                    'feature_id' => $feature->id,
                    'created_by' => $qa->id,
                    'title' => 'Request: ' . fake()->words(3, true),
                    'description' => fake()->paragraph,
                    'status' => collect(['open', 'approved', 'rejected'])->random(),
                ]);
            }
        }
    }
}
