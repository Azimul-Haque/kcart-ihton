<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    /**
     * ক্যাটাগরি আইডি অনুযায়ী চলমান কোর্সসমূহ লোড করা (১০ দিনের ক্যাশ)
     */
    public function getCategoryCourses($categoryId)
    {
        try {
            $courses = Cache::remember('category_courses_' . $categoryId, now()->addDays(10), function () use ($categoryId) {
                return Course::query()
                    ->select('id', 'name', 'category', 'status', 'serial')
                    ->where('category', $categoryId)
                    ->where('status', 1) // এক্টিভ কোর্স
                    ->withCount('courseexams as total_exams')
                    ->orderBy('serial', 'asc') // কোর্সের সিরিয়াল অনুযায়ী সর্ট
                    ->get()
                    ->map(function ($course) {
                        return [
                            'id'          => $course->id,
                            'title'       => $course->name,
                            'total_exams' => $course->total_exams ?? 0,
                        ];
                    });
            });

            return response()->json([
                'status'  => true,
                'message' => 'কোর্সসমূহ সফলভাবে পাওয়া গেছে।',
                'courses' => $courses,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Category Courses API Error: ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'সার্ভার এরর: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getCourseExams($courseId)
    {
        try {
            // v3 ক্যাশ কী ব্যবহার করা হয়েছে
            $courseexams = Cache::remember('course_exams_' . $courseId, 864000, function () use ($courseId) {
                return Exam::query()
                    // ১. Direct Join
                    ->join('courseexams', 'exams.id', '=', 'courseexams.exam_id')
                    ->where('courseexams.course_id', $courseId)
                    
                    // ২. প্রথমে প্রয়োজনীয় কলামগুলো Select করা
                    ->select(
                        'exams.id',
                        'exams.name',
                        'exams.duration',
                        'exams.negativepercentage',
                        'exams.available_from',
                        'exams.available_to',
                        'exams.syllabus',
                        'exams.participation',
                        'exams.cutmark',
                        'exams.lecture_url',
                        'exams.serial'
                    )
                    
                    // ৩. select-এর পরে withCount দিলে total_questions কলামটি বাদ পড়বে না
                    ->withCount('examquestions as total_questions')
                    
                    // ৪. সর্টিং
                    ->orderBy('exams.serial', 'desc')
                    ->orderBy('exams.id', 'desc')
                    ->get()
                    
                    // ৫. JSON ফরম্যাটিং
                    ->map(function ($exam) {
                        $qCount = (int) ($exam->total_questions ?? 0);

                        return [
                            'id'                 => $exam->id,
                            'name'               => $exam->name,
                            'duration'           => $exam->duration,
                            'negativepercentage' => $exam->negativepercentage,
                            'available_from'     => $exam->available_from,
                            'start'              => $exam->available_from,
                            'available_to'       => $exam->available_to,
                            'syllabus'           => $exam->syllabus ?? 'N/A',
                            'participation'      => $exam->participation ?? 0,
                            'cutmark'            => $exam->cutmark ?? 0,
                            'lecture_url'        => $exam->lecture_url,
                            'total_questions'    => $qCount,
                            'questioncount'      => $qCount,
                        ];
                    });
            });

            return response()->json([
                'status'  => true,
                'success' => true,
                'exams'   => $courseexams,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Course Exams API Error: ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'সার্ভার এরর: ' . $e->getMessage(),
            ], 500);
        }
    }
}