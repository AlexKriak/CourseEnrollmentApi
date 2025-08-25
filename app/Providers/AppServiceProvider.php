<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Устанавливаем русскую локаль для Faker
        $this->app->resolving(\Faker\Generator::class, function (\Faker\Generator $faker) {
            $faker->addProvider(new \Faker\Provider\ru_RU\Person($faker));
            $faker->addProvider(new \Faker\Provider\ru_RU\Address($faker));
            $faker->addProvider(new \Faker\Provider\ru_RU\PhoneNumber($faker));
        });
    }
}
