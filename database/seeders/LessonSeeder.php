<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Course;

class LessonSeeder extends Seeder
{
    /**
     * Seed the application's database with lessons.
     *
     * @return void
     */
    public function run()
    {
        // Get the first course
        $course = Course::first();

        // Add some lessons with video embed code (e.g., YouTube iframe embed)
        Lesson::create([
            'title' => 'Introduction to Laravel',
            'slug' => 'introduction-to-laravel', // Add a slug here
            'content' => 'This lesson covers the basics of Laravel, including installation, routing, and controllers.',
            'video_embed_code' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/XMVvFiPtQ4M?si=n_mblcLFHCyhvxum" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
            'course_id' => $course->id,
        ]);

        Lesson::create([
            'title' => 'Routing in Laravel',
            'slug' => 'routing-in-laravel', // Add a slug here
            'content' => 'This lesson explains how to define routes and use them in your Laravel application.',
            'video_embed_code' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/XMVvFiPtQ4M?si=n_mblcLFHCyhvxum" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
            'course_id' => $course->id,
        ]);
    }
}
