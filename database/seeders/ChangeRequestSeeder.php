<?php

namespace Database\Seeders;

use App\Enums\FeatureTypeEnum;
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
        $features = Feature::where('type', FeatureTypeEnum::ChangeRequest)->get();
        $qa = User::where('role_id', 3)->first();

        foreach($features as $feature){
            ChangeRequest::create([
                'feature_id' => $feature->id,
                'created_by' => $qa->id,
                'title' => 'Request: ' . fake()->words(3, true),
                'description' => fake()->paragraph,
                'status' => collect(['open', 'approved', 'rejected', 'completed'])->random(),
            ]);
        }
    }
}
