<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Examquestion;
use App\Models\Meritlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    /**
     * নির্দিষ্ট পরীক্ষার সকল প্রশ্ন, বিকল্প, ব্যাখ্যা ও ছবি লোড করা (ক্যাশেড)
     */
    public function getExamQuestions($examId)
    {
        try {
            // ১০ দিনের জন্য প্রশ্ন ও অপশন ক্যাশে রাখা
            $questions = Cache::remember('exam_questions_v1_' . $examId, 864000, function () use ($examId) {
                return Examquestion::query()
                    ->where('exam_id', $examId)
                    ->with([
                        'question' => function ($q) {
                            $q->select('id', 'topic_id', 'question', 'option1', 'option2', 'option3', 'option4', 'answer');
                        },
                        'question.questionimage' => function ($q) {
                            $q->select('id', 'question_id', 'image');
                        },
                        'question.questionexplanation' => function ($q) {
                            $q->select('id', 'question_id', 'explanation');
                        }
                    ])
                    ->get()
                    ->map(function ($item) {
                        $q = $item->question;
                        if (!$q) return null;

                        return [
                            'question_id'  => $q->id,
                            'question'     => $q->question,
                            'option1'      => $q->option1,
                            'option2'      => $q->option2,
                            'option3'      => $q->option3,
                            'option4'      => $q->option4,
                            'answer'       => (int) $q->answer,
                            'image'        => $q->questionimage->image ?? null,
                            'explanation'  => $q->questionexplanation->explanation ?? null,
                        ];
                    })
                    ->filter()
                    ->values();
            });

            return response()->json([
                'status'    => true,
                'questions' => $questions,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Exam Questions Load Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'সার্ভার এরর 발생'], 500);
        }
    }

    /**
     * পরীক্ষা সাবমিট ও ফলাফল মেরিট লিস্টে সংরক্ষণ
     */
    public function submitExam(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer',
            'exam_id'   => 'required|integer',
            'user_id'   => 'required|integer',
            'marks'     => 'required',
        ]);

        try {
            DB::beginTransaction();

            // মেরিট লিস্টে সেভ অথবা আপডেট
            $merit = Meritlist::updateOrCreate(
                [
                    'course_id' => $request->course_id,
                    'exam_id'   => $request->exam_id,
                    'user_id'   => $request->user_id,
                ],
                [
                    'marks' => (string) $request->marks,
                ]
            );

            DB::commit();

            // ⚡ ২. নির্দিষ্ট কোর্সের নির্দিষ্ট পরীক্ষার ক্যাশ ইনভ্যালিডেট করা
            Cache::forget("merit_list_course_{$request->course_id}_exam_{$request->exam_id}");

            return response()->json([
                'status'  => true,
                'message' => 'পরীক্ষার উত্তরপত্র সফলভাবে জমা হয়েছে।',
                'merit'   => $merit,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Exam Submit Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'সাবমিট করতে সমস্যা হয়েছে'], 500);
        }
    }

    public function getExamMeritList($courseId, $examId)
    {
        try {
            $exam = Exam::find($examId);
            
            if (!$exam) {
                return response()->json([
                    'status' => false,
                    'message' => 'পরীক্ষা পাওয়া যায়নি।',
                    'meritlist' => []
                ], 404);
            }

            // ⚡ কোর্স আইডি ও এক্সাম আইডি মিলিয়ে ১টি ইউনিক ক্যাশ কী
            $cacheKey = "merit_list_course_{$courseId}_exam_{$examId}";

            // ১ ঘণ্টার জন্য ক্যাশ করে রাখা
            $meritlist = Cache::remember($cacheKey, 3600, function () use ($courseId, $examId) {
                return Meritlist::with([
                    'user' => function ($query) {
                        $query->select('id', 'name');
                    }
                ])
                ->where('course_id', $courseId)
                ->where('exam_id', $examId)
                ->orderByRaw('CAST(marks AS DECIMAL(10,2)) DESC')
                ->get();
            });

            return response()->json([
                'status' => true,
                'message' => 'মেধা তালিকা সফলভাবে লোড হয়েছে।',
                'exam_name' => $exam->name,
                'total_participants' => $meritlist->count(),
                'meritlist' => $meritlist
            ], 200);

        } catch (\Exception $e) {
            Log::error('Exam Questions Load Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'সার্ভার এরর: ' . $e->getMessage(),
                'meritlist' => []
            ], 500);
        }
    }
}