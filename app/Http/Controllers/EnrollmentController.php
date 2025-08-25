<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    /**
     * Создать заявку на курс
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,course_id',
            'first_name' => 'required|string|max:255|regex:/^[\p{L}\s\-]+$/u',
            'last_name' => 'required|string|max:255|regex:/^[\p{L}\s\-]+$/u',
            'patronymic' => 'nullable|string|max:255|regex:/^[\p{L}\s\-]+$/u',
            'email' => 'required|email|max:255',
        ], [
            'first_name.regex' => 'Имя может содержать только буквы, пробелы и дефисы.',
            'last_name.regex' => 'Фамилия может содержать только буквы, пробелы и дефисы.',
            'email.email' => 'Email должен быть в корректном формате.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if (Enrollment::where('course_id', $data['course_id'])
            ->where('email', $data['email'])
            ->exists()) {
            return response()->json([
                'error' => 'Заявка с этим email уже существует для данного курса.'
            ], 409);
        }

        $enrollment = Enrollment::create($data);

        return response()->json([
            'message' => 'Заявка успешно создана',
            'enrollment_id' => $enrollment->enrollment_id
        ], 201);
    }

    /**
     * Удалить заявку по ID
     */
    public function destroy($id): JsonResponse
    {
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json(['error' => 'Заявка не найдена'], 404);
        }

        $enrollment->delete();

        return response()->json(['message' => 'Заявка удалена']);
    }

    /**
     * Получить заявки по email
     */
    public function getByEmail(Request $request): JsonResponse
    {
        $email = $request->query('email');

        if (!$email) {
            return response()->json(['error' => 'Требуется параметр email'], 400);
        }

        $enrollments = Enrollment::where('email', $email)
            ->with('course:id,title')
            ->get()
            ->map(function ($e) {
                return [
                    'enrollment_id' => $e->enrollment_id,
                    'course_id' => $e->course->course_id,
                    'course_title' => $e->course->title,
                    'first_name' => $e->first_name,
                    'last_name' => $e->last_name,
                    'patronymic' => $e->patronymic,
                    'email' => $e->email,
                    'created_at' => $e->created_at,
                ];
            });

        return response()->json($enrollments);
    }

    /**
     * Получить заявки по ID курса
     */
    public function getByCourse($courseId): JsonResponse
    {
        $course = Course::select('title')->find($courseId);

        if (!$course) {
            return response()->json(['error' => 'Курс не найден'], 404);
        }

        $enrollments = Enrollment::where('course_id', $courseId)
            ->get()
            ->map(function ($e) {
                return [
                    'enrollment_id' => $e->enrollment_id,
                    'first_name' => $e->first_name,
                    'last_name' => $e->last_name,
                    'patronymic' => $e->patronymic,
                    'email' => $e->email,
                    'created_at' => $e->created_at,
                ];
            });

        return response()->json([
            'course_id' => $courseId,
            'course_title' => $course->title,
            'enrollments' => $enrollments
        ]);
    }
}
