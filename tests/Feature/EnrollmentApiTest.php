<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use App\Models\Course;
use App\Models\Enrollment;
use Tests\TestCase;

class EnrollmentApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (!config('app.key')) {
            \Illuminate\Support\Facades\Artisan::call('key:generate');
        }

        Config::set('services.api_token', 'test-token');
    }

    public function test_user_can_create_enrollment()
    {
        $course = Course::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer test-token'
        ])->postJson('/api/enrollments', [
            'course_id' => $course->course_id,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'ivan@example.com'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'enrollment_id']);

        $this->assertDatabaseHas('enrollments', [
            'email' => 'ivan@example.com',
            'course_id' => $course->course_id
        ]);
    }

    public function test_duplicate_enrollment_is_rejected()
    {
        $course = Course::factory()->create();
        Enrollment::factory()->create([
            'course_id' => $course->course_id,
            'email' => 'ivan@example.com'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer test-token'
        ])->postJson('/api/enrollments', [
            'course_id' => $course->course_id,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'ivan@example.com'
        ]);

        $response->assertStatus(409)
            ->assertJson(['error' => 'Заявка с этим email уже существует для данного курса.']);
    }

    public function test_unauthorized_request_is_rejected()
    {
        $course = Course::factory()->create();

        $response = $this->postJson('/api/enrollments', [
            'course_id' => $course->course_id,
            'first_name' => 'Иван',
            'last_name' => 'Иванов',
            'email' => 'ivan@example.com'
        ]);

        $response->assertStatus(401);
    }
}
