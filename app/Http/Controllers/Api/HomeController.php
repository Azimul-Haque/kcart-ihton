<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Examcategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function getHomeData(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'অননুমোদিত অ্যাক্সেস (User not authenticated)',
                ], 401);
            }

            // ০. ইউজারের লাইভ এপ্রুভাল স্ট্যাটাস (ক্যাশ বাইপাস)
            $isApproved = Cache::remember('user_approval_status_' . $user->id, 864000, function () use ($user) {
                return (int) $user->is_approved;
            });

            // ১. চলমান কোর্সসমূহ ( priority অনুযায়ী সর্টেড এবং ১০ দিনের ক্যাশ )
            $activeCourses = Cache::remember('active_courses', now()->addDays(10), function () {
                return Course::query()
                    ->select('id', 'name', 'priority')
                    ->where('status', 1)
                    ->withCount('courseexams as total_exams')
                    ->orderBy('priority', 'asc')
                    ->get()
                    ->map(function ($course) {
                        return [
                            'id'          => $course->id,
                            'title'       => $course->name,
                            'total_exams' => $course->total_exams ?? 0,
                        ];
                    });
            });

            // ২. ক্যাটাগরি সমূহ ( ১০ দিনের ক্যাশ )
            $categories = Cache::remember('exam_categories', now()->addDays(10), function () {
                return Examcategory::select('id', 'name', 'thumbnail')->get();
            });

            return response()->json([
                'status'         => true,
                'message'        => 'হোম ডাটা সফলভাবে পাওয়া গেছে।',
                'user_id'        => $user->id,
                'user_name'      => $user->name,
                'is_approved'    => $isApproved, // লাইভ স্ট্যাটাস (1 অথবা 0)
                'active_courses' => $activeCourses,
                'categories'     => $categories,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Home Data API Error: ' . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'সার্ভার এরর: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function checkUserApprovalStatus(Request $request)
    {
        $userId = $request->user()->id; // Sanctum Token থেকে Auth User

        // ১০ দিনের ক্যাশ (এডমিন থেকে স্টেটাস চেঞ্জ করলে ক্যাশ ফ্লাশ করা হবে)
        $isApproved = Cache::remember('user_approval_status_' . $userId, 864000, function () use ($userId) {
            $user = User::select('id', 'is_approved')->find($userId);
            
            return $user ? (int) $user->is_approved : null;
        });

        if ($isApproved === null) {
            return response()->json([
                'status'  => false,
                'message' => 'ব্যবহারকারী পাওয়া যায়নি',
            ], 444);
        }

        return response()->json([
            'status'      => true,
            'is_approved' => $isApproved, // 1 অথবা 0
        ], 200);
    }

    public function getExamSummary(Request $request)
    {
        try {
            $now = Carbon::now();
            $todayStr = $now->format('Y-m-d');
            
            // ১. ক্যাশ কী
            $cacheKey = "home_exam_summary_{$todayStr}";

            // ২. আজকের দিনের শেষ সেকেন্ড (রাত ১১:৫৯:৫৯) পর্যন্ত কত সেকেন্ড বাকি আছে হিসাব করা
            $secondsUntilEndOfDay = $now->diffInSeconds(Carbon::tomorrow()->startOfDay());

            // ৩. ক্যাশে রাখা (আজকের দিনের শেষ সময় পর্যন্ত)
            $data = Cache::remember($cacheKey, $secondsUntilEndOfDay, function () use ($todayStr) {
                
                $todayExams = Exam::whereDate('available_from', '<=', $todayStr)
                    ->whereDate('available_to', '>=', $todayStr)
                    ->with('courseexams.course')
                    ->get()
                    ->map(function ($exam) {
                        $course = $exam->courseexams->first()?->course;
                        $exam->course_name = $course ? $course->name : 'সাধারণ কোর্স';
                        $exam->course_id = $course ? $course->id : 0;
                        return $exam;
                    });

                $upcomingExam = null;
                if ($todayExams->isEmpty()) {
                    $upcomingExam = Exam::whereDate('available_from', '>', $todayStr)
                        ->with('courseexams.course')
                        ->orderBy('available_from', 'asc')
                        ->first();

                    if ($upcomingExam) {
                        $course = $upcomingExam->courseexams->first()?->course;
                        $upcomingExam->course_name = $course ? $course->name : 'সাধারণ কোর্স';
                        $upcomingExam->course_id = $course ? $course->id : 0;
                    }
                }

                return [
                    'today_exams' => $todayExams,
                    'upcoming_exam' => $upcomingExam,
                ];
            });

            return response()->json([
                'status' => true,
                'today_exams' => $data['today_exams'],
                'upcoming_exam' => $data['upcoming_exam'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}