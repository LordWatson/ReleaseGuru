<?php

namespace Database\Seeders;

use App\Models\BugReport;
use App\Models\Project;
use App\Models\Task;
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
        $projects = Project::all();
        $users = User::all();

        $modules = ['quoting', 'ordering', 'invoicing'];
        $severities = ['low', 'medium', 'high', 'critical'];
        $statuses = ['open', 'in_progress', 'resolved', 'closed'];

        foreach($projects as $project){
            // Create 15-25 bug reports per project
            $bugCount = rand(15, 25);

            for($i = 0; $i < $bugCount; $i++){
                $status = fake()->randomElement($statuses);
                $severity = fake()->randomElement($severities);
                $module = fake()->randomElement($modules);

                // Resolved timestamp if status is resolved or closed
                $resolvedAt = in_array($status, ['resolved', 'closed'])
                    ? fake()->dateTimeBetween('-30 days', 'now')
                    : null;

                $titles = [
                    "Button not responding in {$module} section",
                    "Data validation error in {$module}",
                    "Page loading timeout in {$module} module",
                    "Incorrect calculations in {$module}",
                    "UI layout broken on mobile in {$module}",
                    "Form submission fails in {$module}",
                    "Search functionality not working in {$module}",
                    "Export feature crashes in {$module}",
                    "Permission denied error in {$module}",
                    "Database connection timeout in {$module}"
                ];

                BugReport::create([
                    'project_id' => $project->id,
                    'module' => $module,
                    'reported_by' => $users->random()->id,
                    'title' => fake()->randomElement($titles),
                    'description' => fake()->paragraph(3) . "\n\n" . fake()->paragraph(2),
                    'severity' => $severity,
                    'status' => $status,
                    'steps_to_reproduce' => $this->generateStepsToReproduce($module),
                    'expected_behavior' => fake()->sentence(10),
                    'actual_behavior' => fake()->sentence(8),
                    'resolved_at' => $resolvedAt,
                    'created_at' => fake()->dateTimeBetween('-60 days', 'now'),
                    'updated_at' => fake()->dateTimeBetween('-30 days', 'now'),
                ]);
            }
        }
    }

    private function generateStepsToReproduce(string $module): string
    {
        $steps = [
            'quoting' => [
                "1. Navigate to the quoting module",
                "2. Click on 'Create New Quote' button",
                "3. Fill in customer details",
                "4. Add line items to the quote",
                "5. Click 'Generate Quote' button"
            ],
            'ordering' => [
                "1. Go to the ordering section",
                "2. Select products from catalog",
                "3. Add items to cart",
                "4. Proceed to checkout",
                "5. Submit the order"
            ],
            'invoicing' => [
                "1. Access the invoicing module",
                "2. Create new invoice",
                "3. Add billing information",
                "4. Include line items",
                "5. Process the invoice"
            ]
        ];

        return implode("\n", $steps[$module] ?? [
            "1. Perform the standard workflow",
            "2. Follow the normal process",
            "3. Execute the expected action"
        ]);
    }
}

