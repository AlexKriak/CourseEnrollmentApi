<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\Factory;
use function fake;

class EnrollmentFactory extends Factory
{
    protected $model = Enrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'course_id' => Course::factory(),
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'patronymic' => fake()->firstName,
            'email' => fake()->unique()->safeEmail,
        ];
    }
}
