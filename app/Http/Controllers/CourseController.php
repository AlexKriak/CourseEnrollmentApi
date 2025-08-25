<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CourseController extends Controller
{
    /**
     * Получить список всех курсов
     */
    public function index(): JsonResponse
    {
        $courses = Cache::remember('courses_list', 3600, function () {
            return Course::select('course_id', 'title', 'description', 'start_date', 'end_date')
                ->get();
        });


        return response()->json($courses);
    }
}
