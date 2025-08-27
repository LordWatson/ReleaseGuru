<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::create([
            'name' => 'CRM',
            'description' => 'CRM System for the company.',
            'health' => 'running'
        ]);

        Project::create([
            'name' => 'CMS',
            'description' => 'The CMS System for the company.',
            'health' => 'running'
        ]);

        Project::create([
            'name' => 'Invoicing',
            'description' => 'The Invoicing System for the company.',
            'health' => 'down'
        ]);
    }
}
