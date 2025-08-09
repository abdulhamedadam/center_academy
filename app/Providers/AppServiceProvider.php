<?php

namespace App\Providers;
use App\Interfaces\CourseInterface;
use App\Interfaces\InstructorInterface;
use App\Interfaces\StudentInterface;
use App\Repository\CourseRepository;
use App\Repository\InstructorRepository;
use App\Repository\StudentRepository;
use App\Services\CourseService;
use App\Services\InstructorService;
use App\Services\StudentService;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CourseInterface::class,
            CourseRepository::class,
            CourseService::class
        );
        $this->app->bind(
            StudentInterface::class,
            StudentRepository::class,
            StudentService::class
        );

        $this->app->bind(
            InstructorInterface::class,
            InstructorRepository::class,
            InstructorService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en'])
                ->flags([
                    'ar' => asset('flags/arabic-flag.png'),
                    'en' => asset('flags/english-flag.png'),
                ]);
        });
    }
}
