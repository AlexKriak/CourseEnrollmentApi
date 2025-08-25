<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Course::create([
            'title' => 'Веб-разработка на Laravel',
            'description' => 'Полный курс по Laravel для начинающих и среднего уровня.',
            'start_date' => '2025-03-01',
            'end_date' => '2025-06-01',
        ]);

        Course::create([
            'title' => 'Алгоритмы и структуры данных',
            'description' => 'Глубокое погружение в алгоритмы и их применение.',
            'start_date' => '2025-04-01',
            'end_date' => '2025-07-01',
        ]);
    }
}
