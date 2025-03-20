<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LessonsController extends Controller
{
    // Fetch lessons for a specific course
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        return response()->json($course->lessons);
    }

    public function getLesson($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        Log::info('Fetched lesson by ID', ['lesson' => $lesson]);
        return response()->json($lesson);
    }

    // Add a new lesson to a course
    public function store(Request $request, $courseId)
    {
        // Validate input (without slug)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'video_embed_code' => 'nullable|string',
        ]);

        $course = Course::findOrFail($courseId);

        // Generate a unique slug from title
        $slug = $this->generateUniqueSlug($validated['title']);

        // Create lesson
        $lesson = new Lesson($validated);
        $lesson->course_id = $course->id;
        $lesson->slug = $slug;
        $lesson->save();

        return response()->json($lesson, 201);
    }

    // Edit a lesson
    public function update(Request $request, $lessonId)
    {
        // Validate input (without slug)
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'video_embed_code' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed on update', ['errors' => $validator->errors()]);
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lesson = Lesson::findOrFail($lessonId);
        $data = $request->all();

        // Generate new slug if title is updated
        if (!empty($data['title'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $lessonId);
        }

        // Update lesson
        $lesson->update($data);

        return response()->json($lesson);
    }

    // Delete a lesson
    public function destroy($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $lesson->delete();

        return response()->json(null, 204);
    }

    // Helper function to generate a unique slug
    private function generateUniqueSlug($title, $lessonId = null)
    {
        $slug = Str::slug($title);
        $count = 0;

        // Ensure uniqueness by checking the database
        while (Lesson::where('slug', $slug)->where('id', '!=', $lessonId)->exists()) {
            $count++;
            $slug = Str::slug($title) . '-' . $count;
        }

        return $slug;
    }
}
