<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\ProjectPriority;
use App\Models\Aspirant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = \App\Models\Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 year', '+1 month');
        $expectedCompletion = $this->faker->dateTimeBetween($startDate, '+1 year');
        $status = $this->faker->randomElement(['pending', 'in_progress', 'on_hold', 'completed', 'cancelled']);
        $completionPercentage = $status === 'completed' ? 100 : 
                             ($status === 'in_progress' ? $this->faker->numberBetween(5, 95) : 0);

        return [
            'aspirant_id' => Aspirant::inRandomOrder()->first()?->id ?? Aspirant::factory()->create()->id,
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(3, true),
            'category' => $this->faker->randomElement([
                'infrastructure',
                'education',
                'health',
                'agriculture',
                'security',
                'employment',
                'youth_development',
                'women_empowerment',
                'others'
            ]),
            'priority' => $this->faker->randomElement(ProjectPriority::cases()),
            'estimated_cost' => $this->faker->randomFloat(2, 10000, 10000000),
            'location' => $this->faker->address,
            'beneficiaries' => (string)$this->faker->numberBetween(100, 10000),
            'promise_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'start_date' => $startDate,
            'expected_completion_date' => $expectedCompletion,
            'actual_completion_date' => $status === 'completed' ? 
                $this->faker->dateTimeBetween($startDate, $expectedCompletion) : null,
            'image_path' => null,
            'document_path' => null,
            'status' => $status,
            'completion_percentage' => $completionPercentage,
            'is_public' => $this->faker->boolean(90), // 90% chance of being public
            'notes' => $this->faker->boolean(30) ? $this->faker->paragraph : null,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Configure the model factory to create project updates when a project is created.
     */
    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Project $project) {
            $startDate = $project->start_date instanceof \DateTime 
                ? $project->start_date 
                : new \DateTime($project->start_date);
            $expectedEnd = $project->expected_completion_date instanceof \DateTime
                ? $project->expected_completion_date
                : new \DateTime($project->expected_completion_date);

            $nextUpdateMax = $expectedEnd > new \DateTime('now') ? new \DateTime('now') : $expectedEnd;
            if ($nextUpdateMax <= $startDate) {
                $nextUpdateMax = (clone $startDate)->modify('+1 month');
            }

            // Create initial project update
            $project->updates()->create([
                'user_id' => 1,
                'title' => 'Project Initiated',
                'description' => 'Project has been initiated and planning is in progress.',
                'status' => $project->status,
                'completion_percentage' => 0,
                'update_date' => $startDate,
                'next_update_date' => $this->faker->dateTimeBetween($startDate, $nextUpdateMax),
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => 1,
            ]);

            // If project is in progress or completed, create additional updates
            if ($project->status !== 'pending' && $project->status !== 'cancelled') {
                $updatesCount = $this->faker->numberBetween(2, 8);
                $endDate = $project->actual_completion_date 
                    ? ($project->actual_completion_date instanceof \DateTime ? $project->actual_completion_date : new \DateTime($project->actual_completion_date))
                    : $expectedEnd;
                $now = new \DateTime('now');
                $iterStart = clone $startDate;

                for ($i = 1; $i <= $updatesCount; $i++) {
                    $iterEnd = $endDate < $now ? $endDate : $now;

                    if ($iterStart >= $iterEnd) {
                        $iterEnd = (clone $iterStart)->modify('+1 day');
                    }

                    $updateDate = $this->faker->dateTimeBetween($iterStart, $iterEnd);

                    $nextMax = $endDate < $now ? $endDate : $now;
                    if ($updateDate >= $nextMax) {
                        $nextMax = (clone $updateDate)->modify('+1 day');
                    }

                    $completionPercentage = min(
                        $project->completion_percentage,
                        (int)($i * (100 / ($updatesCount + 1)))
                    );

                    $project->updates()->create([
                        'user_id' => $this->faker->numberBetween(1, 5),
                        'title' => $this->faker->sentence,
                        'description' => $this->faker->paragraphs(2, true),
                        'status' => $this->faker->randomElement(['pending', 'in_progress', 'on_hold', 'completed', 'cancelled']),
                        'completion_percentage' => $completionPercentage,
                        'amount_spent' => $this->faker->randomFloat(2, 1000, $project->estimated_cost / $updatesCount),
                        'funding_source' => $this->faker->randomElement(['State Government', 'Federal Government', 'Private Sector', 'Donor Agency']),
                        'update_date' => $updateDate,
                        'next_update_date' => $i < $updatesCount
                            ? $this->faker->dateTimeBetween($updateDate, $nextMax)
                            : null,
                        'next_steps' => $i < $updatesCount ? $this->faker->paragraph : null,
                        'is_verified' => $this->faker->boolean(80),
                        'verified_at' => $this->faker->optional(0.8)->dateTimeBetween($updateDate, 'now'),
                        'verified_by' => $this->faker->optional(0.8)->passthrough(
                            User::inRandomOrder()->first()?->id ?? 1
                        ),
                    ]);

                    $iterStart = $updateDate;
                }
            }
        });
    }
}
