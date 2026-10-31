<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

use App\Models\Topic;
use App\Models\Exam;
use App\Models\Examcategory;
use App\Models\Examquestion;
use App\Models\Question;
// use App\Models\Questionimage;
use App\Models\Questionexplanation;
use App\Models\Meritlist;
use App\Models\Message;

use Carbon\Carbon;
use DB;
use Hash;
use Auth;
use Image;
use File;
use Session;
use Artisan;
use OneSignal;
use Cache;
use View;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $unresolvedmessagecount = Message::where('status', 0)->count();
        View::share('unresolvedmessagecount', $unresolvedmessagecount);

        $this->middleware('auth')->except('clear');
        $this->middleware(['admin'])->only('getQuestions');
    }

    public function getExamsLanding()
    {
        $examcategories = $examcategories = Examcategory::all();

        return view('dashboard.exams.landing')
                    ->withExamcategories($examcategories);
    }

    public function getExams(Request $request)
    {
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')) {
            abort(403, 'Access Denied');
        }

        if(!empty($request->id)) {
            $totalexams = Exam::where('examcategory_id', $request->id)->count();
            // এখানে withQueryString() যোগ করা হয়েছে
            $exams = Exam::where('examcategory_id', $request->id)
                         ->orderBy('id', 'desc')
                         ->paginate(10)
                         ->withQueryString(); 
        } else {
            $totalexams = Exam::count();
            $exams = Exam::orderBy('id', 'desc')
                         ->paginate(10)
                         ->withQueryString();
        }
        
        $examcategories = Examcategory::all();

        return view('dashboard.exams.index')
                    ->withExams($exams)
                    ->withExamcategories($examcategories)
                    ->withTotalexams($totalexams);
    }

    public function getExamsSearch($search)
    {
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')) {
            abort(403, 'Access Denied');
        }
        
        $totalexams = Exam::where('name', 'LIKE', "%$search%")->count();
        $exams = Exam::where('name', 'LIKE', "%$search%")->paginate(10); 
        $examcategories = Examcategory::all();

        Session::flash('success', $totalexams . ' টি পরীক্ষা পাওয়া গিয়েছে!');
        return view('dashboard.exams.index')
                    ->withExams($exams)
                    ->withExamcategories($examcategories)
                    ->withTotalexams($totalexams);
    }

    public function getExamMeritList($exam_id)
    {  
        $exam = Exam::findOrFail($exam_id);

        $rank = 1;
        $previous = null;
        foreach ($exam->meritlists->sortByDesc('marks') as $score) {
            if ($previous && $previous->marks != $score->marks) {
                $rank++;
            }
            $score->rank = $rank;
            $previous = $score;
        }
        return view('dashboard.exams.meritlist')
                    ->withExam($exam);
    }

    public function storeExamCategory(Request $request)
    {
        $this->validate($request,array(
            'name'          => 'required|string|max:191',
            'thumbnail'     => 'required|string|max:191',
        ));

        $category = new Examcategory;
        $category->name = $request->name;
        $category->thumbnail = $request->thumbnail;
        $category->save();

        Cache::forget('examcategories');
        Cache::forget('exam_categories');
        Session::flash('success', 'Category created successfully!');
        return redirect()->bacK();
    }

    public function updateExamCategory(Request $request, $id)
    {
        $this->validate($request,array(
            'name'          => 'required|string|max:191',
            'thumbnail'     => 'required|string|max:191',
        ));

        $category = Examcategory::find($id);;
        $category->name = $request->name;
        $category->thumbnail = $request->thumbnail;
        $category->save();

        Cache::forget('examcategories');
        Cache::forget('exam_categories');
        Session::flash('success', 'Category updated successfully!');
        return redirect()->back();
    }

    public function deleteExamCategory($id)
    {
        $category = Examcategory::find($id);
        $category->delete();

        Cache::forget('examcategories');
        Cache::forget('exam_categories');
        Session::flash('success', 'Category deleted successfully!');
        return redirect()->route('dashboard.exams');
    }

    public function storeExam(Request $request)
    {
        // dd($request->file('image'));
        $this->validate($request,array(
            'examcategory_id'    => 'required|string|max:191',
            'name'               => 'required|string|max:191',
            'duration'           => 'required|string|max:191',
            'qsweight'           => 'required|string|max:191',
            'negativepercentage' => 'required|string|max:191',
            // 'price_type'         => 'required|string|max:191',
            'cutmark'            => 'required|string|max:191',
            'available_from'     => 'required|string|max:191',
            'available_to'       => 'required|string|max:191',
            'syllabus'           => 'required|string',
            'alltimeavailability'           => 'sometimes',
            'lecture_url'           => 'sometimes',
        ));

        $exam = new Exam;
        $exam->examcategory_id = $request->examcategory_id;
        $exam->name = $request->name;
        $exam->duration = $request->duration;
        $exam->qsweight = $request->qsweight;
        $exam->negativepercentage = $request->negativepercentage;
        $exam->price_type = 1; // paid
        $exam->cutmark = $request->cutmark;
        $exam->available_from = Carbon::parse($request->available_from);
        $exam->available_to = Carbon::parse($request->available_to);
        $exam->syllabus = nl2br($request->syllabus);
        $exam->lecture_url =$request->lecture_url;
        // if($request->alltimeavailability) {
        //     $exam->alltimeavailability = 1;
        // }else {
        //     $exam->alltimeavailability = 0;
        // }
        $exam->save();
        
        Session::flash('success', 'Exam created successfully!');
        return redirect()->route('dashboard.exams');
    }

    public function updateExam(Request $request, $id)
    {
        // dd($request->file('image'));
        // dd($request->file('image'));
        $this->validate($request,array(
            'examcategory_id'    => 'required|string|max:191',
            'name'               => 'required|string|max:191',
            'duration'           => 'required|string|max:191',
            'qsweight'           => 'required|string|max:191',
            'negativepercentage' => 'required|string|max:191',
            // 'price_type'         => 'required|string|max:191',
            'cutmark'            => 'required|string|max:191',
            'available_from'     => 'required|string|max:191',
            'available_to'       => 'required|string|max:191',
            'syllabus'           => 'required|string',
            'alltimeavailability'           => 'sometimes',
            'lecture_url'           => 'sometimes',
        ));

        $exam = Exam::find($id);
        $exam->examcategory_id = $request->examcategory_id;
        $exam->name = $request->name;
        $exam->duration = $request->duration;
        $exam->qsweight = $request->qsweight;
        $exam->negativepercentage = $request->negativepercentage;
        $exam->price_type = 1;
        $exam->cutmark = $request->cutmark;
        $exam->available_from = Carbon::parse($request->available_from);
        $exam->available_to = Carbon::parse($request->available_to);
        $exam->syllabus = nl2br($request->syllabus);
        $exam->lecture_url =$request->lecture_url;
        // if($request->alltimeavailability) {
        //     $exam->alltimeavailability = 1;
        // } else {
        //     $exam->alltimeavailability = 0;
        // }
        $exam->save();

        Cache::forget('exam' . $id);
        foreach($exam->courseexams as $examdata) {
            Cache::forget('courseexams_' . $examdata->course_id);
            Cache::forget('course_exams_' . $examdata->course_id);
        }
        Session::flash('success', 'Exam updated successfully!');
        // return redirect()->route('dashboard.exams');
        return redirect()->back();
    }
    
    public function copyExam(Request $request, $id)
    {
        // dd($request->file('image'));
        // dd($request->file('image'));
        $this->validate($request,array(
            'name'               => 'required|string|max:191',
        ));

        $oldexam = Exam::findOrFail($id);
        $exam = new Exam();
        $exam->examcategory_id = $oldexam->examcategory_id;
        $exam->name = $request->name;
        $exam->duration = $oldexam->duration;
        $exam->qsweight = $oldexam->qsweight;
        $exam->negativepercentage = $oldexam->negativepercentage;
        $exam->price_type = 1;
        $exam->cutmark = $oldexam->cutmark;
        $exam->available_from = Carbon::parse($oldexam->available_from);
        $exam->available_to = Carbon::parse($oldexam->available_to);
        $exam->syllabus = $oldexam->syllabus;
        $exam->alltimeavailability = $oldexam->alltimeavailability;
        $exam->save();

        foreach($oldexam->examquestions as $oldexamquestion) {
            $examquestion = new Examquestion;
            $examquestion->exam_id = $exam->id;
            $examquestion->question_id = $oldexamquestion->question_id;
            $examquestion->save();
        }

        Session::flash('success', 'Exam copied successfully!');
        return redirect()->route('dashboard.exams');
    }

    public function deleteExam($id)
    {
        $exam = Exam::findOrFail($id);
        foreach($exam->examquestions as $examquestion) {
            $examquestion->delete();
        }
        $exam->delete();

        Session::flash('success', 'Exam deleted successfully!');
        return redirect()->route('dashboard.exams');
    }

    public function storeExamExcelQuestion(Request $request, $exam_id)
    {
        // ১. ফাইল ভ্যালিডেশন
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv|max:10240',
        ]);

        try {
            $filePath = $request->file('file')->getRealPath();

            // ২. FastExcel দিয়ে ১ম শিট (Sheet1) থেকে ডাটা রিড করা
            $rows = (new FastExcel)->sheet(1)->import($filePath);

            if ($rows->isEmpty()) {
                return redirect()->back()->with('error', 'ফাইলে কোনো প্রশ্ন পাওয়া যায়নি।');
            }

            DB::beginTransaction();

            Examquestion::where('exam_id', $exam_id)->delete();

            $importedCount = 0;

            foreach ($rows as $row) {
                
                // কি (Key) গুলোকে নরমালইজ করা (স্মল লেটার ও স্পেস মুক্ত করা)
                $cleanRow = [];
                foreach ($row as $key => $value) {
                    $cleanKey = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', trim($key)));
                    $cleanRow[$cleanKey] = is_string($value) ? trim($value) : $value;
                }

                // প্রশ্ন এবং অপশন ১ চেক করা
                $questionText = $cleanRow['question'] ?? null;
                $option1      = $cleanRow['option1'] ?? null;

                // যদি প্রশ্ন বা অপশন ১ ফাঁকা হয় (যেমন ফাঁকা রো), তবে স্কিপ করবে
                if (empty($questionText) || empty($option1)) {
                    continue;
                }

                // মানসমূহ রিড করা
                $option2     = $cleanRow['option2'] ?? '';
                $option3     = $cleanRow['option3'] ?? '';
                $option4     = $cleanRow['option4'] ?? '';
                $answer      = $cleanRow['answer14'] ?? $cleanRow['answer'] ?? 1;
                $topicId     = $cleanRow['topicid'] ?? $cleanRow['topic_id'] ?? null;
                $explanation = $cleanRow['explanation'] ?? null;

                // Question টেবিলে ইনসার্ট
                $question = Question::create([
                    'topic_id'   => !empty($topicId) ? (int)$topicId : null,
                    'question'   => $questionText,
                    'option1'    => $option1,
                    'option2'    => $option2,
                    'option3'    => $option3,
                    'option4'    => $option4,
                    'answer'     => (int)$answer,
                    'difficulty' => 1,
                ]);

                // Explanation থাকলে ইনসার্ট
                if (!empty($explanation)) {
                    Questionexplanation::create([
                        'question_id' => $question->id,
                        'explanation' => $explanation,
                    ]);
                }

                // Examquestion পিভট টেবিলে এক্সামের সাথে রিলেশন তৈরি
                Examquestion::create([
                    'exam_id'     => $exam_id,
                    'question_id' => $question->id,
                ]);

                $importedCount++;
            }

            DB::commit();

            return redirect()->back()->with('success', "মোট {$importedCount} টি প্রশ্ন সফলভাবে আপলোড এবং যুক্ত করা হয়েছে।");

        } catch (\Throwable $e) {
            DB::rollBack();

            // এরর লগ করা হচ্ছে যাতে storage/logs/laravel.log এ চেক করা যায়
            Log::error('Excel Import Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'ফাইল আপলোডে সমস্যা হয়েছে। Error: ' . $e->getMessage());
        }
    }

    public function addQuestionToExam($id)
    {
        $exam = Exam::findOrFail($id);
        $examquestions = Examquestion::where('exam_id', $exam->id)
                                     ->orderBy('question_id', 'asc')
                                     ->get();
        $topics = Topic::all();


        $mainTopics = Topic::whereNull('parent_id')
                                    ->orWhere('parent_id', 0)
                                    ->get();

        $questions = Question::select('id', 'question', 'topic_id')->get()->take(20);
        // $questions = Question::all();
        
        return view('dashboard.exams.addquestion')
                                    ->withExam($exam)
                                    ->withExamquestions($examquestions)
                                    ->withTopics($topics)
                                    ->withQuestions($questions)
                                    ->withMainTopics($mainTopics);
    }

    public function addQuestionToExamAll($id)
    {
        $exam = Exam::findOrFail($id);
        $examquestions = Examquestion::where('exam_id', $exam->id)
                                     ->orderBy('question_id', 'asc')
                                     ->get();

        $totalquestions = Question::count();
        $questions = Question::orderBy('id', 'desc')->paginate(15);
        $topics = Topic::orderBy('id', 'asc')->get();
        // $questions = Question::all();
        
        return view('dashboard.exams.addquestionbankall')
                                    ->withExam($exam)
                                    ->withExamquestions($examquestions)
                                    ->withTopics($topics)
                                    ->withQuestions($questions)
                                    ->withTotalquestions($totalquestions);
    }

    public function addQuestionFromOthers($id)
    {
        $exam = Exam::findOrFail($id);
        $examquestions = Examquestion::where('exam_id', $exam->id)
                                     ->orderBy('question_id', 'asc')
                                     ->get();
        $exams = Exam::all();
        
        return view('dashboard.exams.addquestionfrotmothers')
                                    ->withExam($exam)
                                    ->withExams($exams)
                                    ->withExamquestions($examquestions);
    }

    public function storeQuestionFromOthers(Request $request, $id)
    {
        $this->validate($request,array(
            'otherexamids'    => 'required',
        ));

        $exam = Exam::findOrFail($id);
        $otherexamids = explode(',', $request->otherexamids);

        foreach($otherexamids as $examid) {
            $nameofthevariable = 'questionamount' . $examid;
            // dd($request->$nameofthevariable);
            $selectedexamquestions = Examquestion::where('exam_id', $examid)
                                        ->inRandomOrder()
                                        ->limit($request->$nameofthevariable)
                                        ->get();

            // dd($selectedexamquestions);
            foreach($selectedexamquestions as $selectedexamquestion) {
                $examquestion = new Examquestion;
                $examquestion->exam_id = $id;
                $examquestion->question_id = $selectedexamquestion->question_id;
                $examquestion->save();
            }
        }
        // dd($request->all());
        Session::flash('success', 'প্রশ্ন হালনাগাদ করা হয়েছে!');
        return redirect()->back();
    }

    public function getSubtopics(Request $request)
    {
        // যদি target_id না থাকে তবে মেইন সিলেক্টর থেকে আইডি নেবে
        $parentId = $request->target_id ?? $request->main_topic_id;
        
        $topics = Topic::where('parent_id', $parentId)
                        ->where('total_questions_sum', '>', 0)
                        ->withCount('questions')
                        ->with('children')
                        ->get()
                        ->mapWithKeys(function($topic) {
                            return [$topic->name => [
                                'id' => $topic->id,
                                'direct_q' => $topic->questions_count, // নিজস্ব প্রশ্ন
                                'total_q' => $topic->total_questions_sum,
                                'has_children' => $topic->children()->count() > 0,
                                'all_ids' => $topic->descendant_ids // সব লিফ আইডি
                            ]];
                        });

        return response()->json($topics);
    }

    public function storeAutoQuestions(Request $request) {
        $allQuestionIds = [];

        // dd($request->all());
        // ১. শুধুমাত্র নিজস্ব প্রশ্ন প্রসেস করা (only_own)
        if ($request->has('only_own')) {
            foreach ($request->only_own as $topicId => $count) {
                $count = (int)$count;
                if ($count > 0) {
                    $questions = Question::where('topic_id', $topicId)
                                    ->inRandomOrder()
                                    ->limit($count)
                                    ->pluck('id')
                                    ->toArray();
                    $allQuestionIds = array_merge($allQuestionIds, $questions);
                }
            }
        }

        // ২. সাবটপিকসহ প্রশ্ন প্রসেস করা (topic_groups)
        if ($request->has('topic_groups')) {
            foreach ($request->topic_groups as $idsCommaString => $count) {
                $count = (int)$count;
                if ($count > 0) {
                    // আইডিগুলো কমা দিয়ে আলাদা করা (স্ট্রিং থেকে অ্যারে)
                    $ids = explode(',', $idsCommaString);

                    if (empty($ids)) continue;

                    $query = Question::whereIn('topic_id', $ids);

                    // যদি ইতিমধ্যে কিছু প্রশ্ন সিলেক্ট হয়ে থাকে তবে সেগুলো বাদ দেওয়া
                    if (!empty($allQuestionIds)) {
                        $query->whereNotIn('id', $allQuestionIds);
                    }

                    $questions = $query->inRandomOrder()
                                    ->limit($count)
                                    ->pluck('id')
                                    ->toArray();
                                    
                    $allQuestionIds = array_merge($allQuestionIds, $questions);
                }
            }
        }

        // dd($allQuestionIds);

        // ৩. এক্সাম পেপারে প্রশ্নগুলো সেভ করা
        $examId = $request->exam_id;
        if (!empty($allQuestionIds)) {
            $insertData = [];
            $now = now();

            foreach (array_unique($allQuestionIds) as $qId) {
                $insertData[] = [
                    'exam_id'     => $examId,
                    'question_id' => $qId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }

            // ২. ডাটাবেসে একবারে ইনসার্ট করা (Performance Optimization)
            // insertOrIgnore ব্যবহার করলে যদি প্রশ্নটি আগে থেকেই থাকে তবে সেটি এরর দিবে না, স্কিপ করবে।
            DB::table('examquestions')->insertOrIgnore($insertData);

            // return response()->json([
            //     'status'  => 'success',
            //     'message' => count($insertData) . ' টি প্রশ্ন পরীক্ষায় যোগ করা হয়েছে।',
            //     'total'   => count($insertData)
            // ]);
            return back()->with('success', count($insertData) . ' টি প্রশ্ন পরীক্ষায় যোগ করা হয়েছে।');
        }

        return back()->with('error', 'কোনো প্রশ্ন সিলেক্ট করা হয়নি।');
    }

    public function fastCleanup()
    {
        // ১. একটি টেম্পোরারি টেবিল তৈরি করুন যেখানে ডুপ্লিকেট ছাড়া ইউনিক আইডিগুলো থাকবে
        DB::statement("CREATE TABLE temp_ids AS 
                       SELECT MIN(id) as id 
                       FROM examquestions 
                       GROUP BY exam_id, question_id");

        // ২. এবার মেইন টেবিল থেকে সেই সব ডাটা ডিলিট করুন যে আইডিগুলো টেম্পোরারি টেবিলে নেই
        DB::statement("DELETE FROM examquestions 
                       WHERE id NOT IN (SELECT id FROM temp_ids)");

        // ৩. টেম্পোরারি টেবিলটি ড্রপ করে দিন
        DB::statement("DROP TABLE temp_ids");

        return "সফলভাবে ডুপ্লিকেট ক্লিন হয়েছে!";
    }

    public function storeExcelExam(Request $request)
    {
        // dd($request->file('file'));
        ini_set('memory_limit', '512000000');
        try {
            $collections = (new FastExcel)->import($request->file('file'));
        } catch (\Exception $exception) {
            Session::flash('error', 'You have uploaded a wrong format file, please upload the right file.');
            return back();
        }

        // dd($collections);
        DB::beginTransaction();
        foreach ($collections as $collection) {
            try {
                $exam             = new Exam;
                $exam->examcategory_id   = $collection['examcategory_id'];
                $exam->name   = $collection['name'];
                $exam->duration    = $collection['duration'];
                $exam->qsweight    = $collection['qsweight'];
                $exam->negativepercentage    = $collection['negativepercentage'];
                $exam->price_type = 1;
                $exam->available_from    = Carbon::parse($collection['available_from']);
                $exam->available_to    = Carbon::parse($collection['available_from']);
                $exam->syllabus     = $collection['syllabus'];
                // $exam->participation     = 0;
                $exam->cutmark     = $collection['cutmark'];
                $exam->alltimeavailability     = 0;
                $exam->serial     = $collection['serial'];
                $exam->save();

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
            }
        }
        
        Session::flash('success', 'Exams uploaded successfully!');
        return redirect()->route('dashboard.exams');
    }

    public function addQuestionToExamTopic($topic_id, $id)
    {
        $exam = Exam::findOrFail($id);
        $examquestions = Examquestion::where('exam_id', $exam->id)
                                     ->orderBy('question_id', 'asc')
                                     ->get();

        $totalquestions = Question::where('topic_id', $topic_id)->count();
        $questions = Question::where('topic_id', $topic_id)
                             ->orderBy('id', 'desc')
                             ->paginate(15);
        $topics = Topic::orderBy('id', 'asc')->get();
        // $questions = Question::all();
        
        return view('dashboard.exams.addquestionbankall')
                                    ->withExam($exam)
                                    ->withExamquestions($examquestions)
                                    ->withTopics($topics)
                                    ->withQuestions($questions)
                                    ->withTotalquestions($totalquestions);
    }

    public function addQuestionToExamSearch($id, $search)
    {
        $exam = Exam::findOrFail($id);
        $examquestions = Examquestion::where('exam_id', $exam->id)
                                     ->orderBy('question_id', 'asc')
                                     ->get();

        $totalquestions = Question::where('question', 'LIKE', "%$search%")->count();
        $questions = Question::where('question', 'LIKE', "%$search%")
                             ->orWhere('option1', 'LIKE', "%$search%")
                             ->orWhere('option2', 'LIKE', "%$search%")
                             ->orWhere('option3', 'LIKE', "%$search%")
                             ->orWhere('option4', 'LIKE', "%$search%")
                             ->orderBy('id', 'desc')
                             ->paginate(15);
        $topics = Topic::orderBy('id', 'asc')->get();
        // $questions = Question::all();
        
        return view('dashboard.exams.addquestionbankall')
                                    ->withExam($exam)
                                    ->withExamquestions($examquestions)
                                    ->withTopics($topics)
                                    ->withQuestions($questions)
                                    ->withTotalquestions($totalquestions);
    }

    public function clearExamQuestions(Request $request)
    {
        $this->validate($request,array(
            'exam_id'          => 'required'
        ));

        $oldexamquestions = Examquestion::where('exam_id', $request->exam_id)->get();
        if(count($oldexamquestions) > 0) {
            foreach($oldexamquestions as $oldexamquestion) {
                $oldexamquestion->delete();
            }
        }

        Session::flash('success', 'Questions have been cleared!');
        return redirect()->route('dashboard.exams.add.question', $request->exam_id);
    }

    public function storeExamQuestion(Request $request)
    {
        $this->validate($request,array(
            'exam_id'          => 'required',
            'hiddencheckarray' => 'sometimes',
            'currentchecktext' => 'sometimes',
        ));

        if($request->hiddencheckarray == '') {
            if($request->currentchecktext != '') {
                $oldexamquestionsids = explode(',', $request->currentchecktext);
                $oldexamquestions = Examquestion::where('exam_id', $request->exam_id)
                                                ->whereIn('question_id', $oldexamquestionsids)
                                                ->get();
                // dd($oldexamquestions);
                if(count($oldexamquestions) > 0) {
                    foreach($oldexamquestions as $oldexamquestion) {
                        $oldexamquestion->delete();
                    }
                }
                Session::flash('success', 'Question updated successfully!');
            } else {
                Session::flash('warning', 'কিছুতো সিলেক্ট করুন');
            }
        } else {
            $oldexamquestionsids = explode(',', $request->currentchecktext);
            $oldexamquestions = Examquestion::where('exam_id', $request->exam_id)
                                                ->whereIn('question_id', $oldexamquestionsids)
                                                ->get();
            if(count($oldexamquestions) > 0) {
                foreach($oldexamquestions as $oldexamquestion) {
                    $oldexamquestion->delete();
                }
            }
            $hiddencheckarray = explode(',', $request->hiddencheckarray);
            // sort($hiddencheckarray);
            // dd($hiddencheckarray);
            foreach($hiddencheckarray as $question_id) {
                $examquestion = new Examquestion;
                $examquestion->exam_id = $request->exam_id;
                $examquestion->question_id = $question_id;
                $examquestion->save();
            }
            Session::flash('success', 'Question updated successfully!!');
        }
        
        // $oldexamquestions = Examquestion::where('exam_id', $request->exam_id)->get();
        // if(count($oldexamquestions) > 0) {
        //     foreach($oldexamquestions as $oldexamquestion) {
        //         $oldexamquestion->delete();
        //     }
        // }
        // $hiddencheckarray = explode(',', $request->hiddencheckarray);
        // // sort($hiddencheckarray);
        // // dd($hiddencheckarray);
        // foreach($hiddencheckarray as $question_id) {
        //     $examquestion = new Examquestion;
        //     $examquestion->exam_id = $request->exam_id;
        //     $examquestion->question_id = $question_id;
        //     $examquestion->save();
        // }

        
        return redirect()->back();
    }

    public function removeExamQuestion($exam_id, $question_id)
    {
        $examquestion = Examquestion::where('exam_id', $exam_id)
                                    ->where('question_id', $question_id)
                                    ->first();
        // dd($examquestion);
        $examquestion->delete();

        Session::flash('success', 'Question removed successfully!');
        return redirect()->route('dashboard.exams.add.question', $exam_id);
    }
    
    public function automaticeExamQuestionSet(Request $request)
    {
        $this->validate($request,array(
            'exam_id'          => 'required',
        ));

        // dd($request->all());
        $oldexamquestions = Examquestion::where('exam_id', $request->exam_id)->get();
        if(count($oldexamquestions) > 0) {
            foreach($oldexamquestions as $oldexamquestion) {
                $oldexamquestion->delete();
            }
        }

        $topics = Topic::all();
        $quantitycheck = 0;
        foreach($topics as $topic) {
            $topicname = 'topic' . $topic->id;
            $quantity = 'quantity' . $topic->id;
            if($request[$topicname] == $topic->id && $request[$quantity] > 0) {
                $topicquestions = Question::where('topic_id', $request[$topicname])->inRandomOrder()->limit($request[$quantity])->get();
                // dd($topicquestions);
                foreach($topicquestions as $topicquestion) {
                    $examquestion = new Examquestion;
                    $examquestion->exam_id = $request->exam_id;
                    $examquestion->question_id = $topicquestion->id;
                    $examquestion->save();
                }
            }
            $quantitycheck = $quantitycheck + $request[$quantity];
            // dd($quantitycheck);
        }
        if($quantitycheck == 0) {
            Session::flash('info', 'At least one topic is required!');
        } else {
            Session::flash('success', 'Question updated successfully!');
        }
        return redirect()->route('dashboard.exams.add.question', $request->exam_id);
    }
    
    public function getMeritList($course_id, $exam_id)
    {
        // OBSOLETE, USE KORTESI NA
        // OBSOLETE, USE KORTESI NA
        // OBSOLETE, USE KORTESI NA
        $meritlists = Meritlist::where('course_id', $course_id)
                               ->where('exam_id', $exam_id)
                               ->orderBy('marks', 'desc')
                               ->get();
        foreach($meritlists as $meritlist) {
            $meritlist->name = $meritlist->user->name;
        }
        $meritlists->makeHidden('user', 'created_at', 'updated_at');
        $newmeritlists = $this->rankandScore($meritlists->toArray());
        $finalmeritlist = collect($newmeritlists);
        // foreach($finalmeritlist as $meritlist) {
        //     echo $meritlist->user->name . '-' . $meritlist->marks . '<br>';
        // }
        dd($finalmeritlist);
    }

    public function rankandScore($scores){
        // OBSOLETE, USE KORTESI NA
        // OBSOLETE, USE KORTESI NA
        // OBSOLETE, USE KORTESI NA
        return collect($scores)
            ->sortByDesc('marks')
            ->zip(range(1, count($scores)))
            ->map(function ($scoreAndRank){
                list($marks, $rank) = $scoreAndRank;
                return array_merge($marks, [
                    'rank' => $rank
                ]);
            })
            ->groupBy('marks')
            ->map(function ($tiedScores){
                $lowestRank = $tiedScores->pluck('rank')->min();
                return $tiedScores->map(function ($rankedScore) use ($lowestRank){
                    return array_merge($rankedScore, [
                        'rank' => $lowestRank,
                    ]);
                });

            })
            ->collapse()
            ->sortBy('rank');
    }
}
