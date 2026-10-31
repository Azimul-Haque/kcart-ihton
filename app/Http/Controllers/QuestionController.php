<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

use App\Models\Question;
use App\Models\Questionimage;
use App\Models\Questionexplanation;
use App\Models\Topic;
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
use Purifier;
use Cache;
use View;

class QuestionController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $unresolvedmessagecount = Message::where('status', 0)->count();
        View::share('unresolvedmessagecount', $unresolvedmessagecount);

        $this->middleware('auth');
        $this->middleware(['admin'])->only('storeQuestionsTopic', 'deleteQuestion');
        // $this->middleware(['manager'])->only();
    }

    public function getQuestions()
    {
        ini_set('memory_limit', '-1');
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')) {
            abort(403, 'Access Denied');
        }
        
        // $totalquestions = Question::count(); 
        $totalquestions = Cache::remember('total_questions_count', 
            7 * 24 * 60 * 60, // Cache TTL of 7 days
            function () {
                return Question::count();
            }
        );
        $questions = Question::orderBy('id', 'desc')->cursorPaginate(10);
        $topics = Topic::where('parent_id', null)->orderBy('id', 'asc')->get(); // EKAHNE KAAJ ACHE

        // dd($questions);
        return view('dashboard.questions.index')
                    ->withQuestions($questions)
                    ->withTopics($topics)
                    ->withTotalquestions($totalquestions);
    }

    public function getQuestionsSearch($search)
    {
        ini_set('memory_limit', '-1');
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')) {
            abort(403, 'Access Denied');
        }
        
        $totalquestions = Question::where('question', 'LIKE', "%$search%")->count();
        $questions = Question::where('question', 'LIKE', "%$search%")
                             ->orWhere('option1', 'LIKE', "%$search%")
                             ->orWhere('option2', 'LIKE', "%$search%")
                             ->orWhere('option3', 'LIKE', "%$search%")
                             ->orWhere('option4', 'LIKE', "%$search%")
                             ->orderBy('id', 'desc')
                             ->cursorPaginate(10);
        // // ১. কাউন্ট করার জন্য
        // $totalquestions = Question::whereRaw("MATCH(question, option1, option2, option3, option4) AGAINST(? IN NATURAL LANGUAGE MODE)", [$search])
        //                           ->count();

        // // ২. ডাটা গেট করার জন্য
        // $questions = Question::whereRaw("MATCH(question, option1, option2, option3, option4) AGAINST(? IN NATURAL LANGUAGE MODE)", [$search])
        //                      ->orderBy('id', 'desc')
        //                      ->cursorPaginate(10);
                             
        $topics = Topic::where('parent_id', null)->orderBy('id', 'asc')->get();

        Session::flash('success', $totalquestions . ' টি প্রশ্ন পাওয়া গিয়েছে!');
        return view('dashboard.questions.index')
                    ->withQuestions($questions)
                    ->withTopics($topics)
                    ->withTotalquestions($totalquestions);
    }

    public function getQuestionsTopicBased($id)
    {
        ini_set('memory_limit', '-1');
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')) {
            abort(403, 'Access Denied');
        }
        
        $totalquestions = Question::where('topic_id', $id)->count();
        $questions = Question::where('topic_id', $id)
                             ->orderBy('id', 'desc')
                             ->cursorPaginate(10);

        $topics = Topic::where('parent_id', null)->orderBy('id', 'asc')->get();

        return view('dashboard.questions.index')
                    ->withQuestions($questions)
                    ->withTopics($topics)
                    ->withTotalquestions($totalquestions);
    }


    public function getChangeTopicQuestions()
    {
        ini_set('memory_limit', '-1');
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')) {
            abort(403, 'Access Denied');
        }
        
        $totalquestions = Question::count();
        $questions = Question::orderBy('id', 'desc')->cursorPaginate(10);
        $topics = Topic::where('parent_id', null)->orderBy('id', 'asc')->get();

        // dd($questions);
        return view('dashboard.questions.changetopic')
                    ->withQuestions($questions)
                    ->withTopics($topics)
                    ->withTotalquestions($totalquestions);
    }

    public function getChangeTopicQuestionsSearch($search)
    {
        ini_set('memory_limit', '-1');
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')) {
            abort(403, 'Access Denied');
        }
        
        $totalquestions = Question::where('question', 'LIKE', "%$search%")->count();
        $questions = Question::where('question', 'LIKE', "%$search%")
                             ->orWhere('option1', 'LIKE', "%$search%")
                             ->orWhere('option2', 'LIKE', "%$search%")
                             ->orWhere('option3', 'LIKE', "%$search%")
                             ->orWhere('option4', 'LIKE', "%$search%")
                             ->orderBy('id', 'desc')
                             ->cursorPaginate(10);

        $topics = Topic::where('parent_id', null)->orderBy('id', 'asc')->get();

        // dd($questions);
        return view('dashboard.questions.changetopic')
                    ->withQuestions($questions)
                    ->withTopics($topics)
                    ->withTotalquestions($totalquestions);
    }

    public function postChangeTopicQuestions(Request $request, $id)
    {
        $this->validate($request,array(
            'topicchangeid' => 'required',
        ));

        $question             = Question::findOrFail($id);
        $question->topic_id   = $request->topicchangeid;
        $question->save();

        Session::flash('success', 'সফলভাবে আপডেট করা হয়েছে, যাচাই করে দেখুন!');
        return redirect()->back();
    }


    public function getChangeTopicQuestionsTopicBased($id)
    {
        ini_set('memory_limit', '-1');
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')) {
            abort(403, 'Access Denied');
        }
        
        $totalquestions = Question::where('topic_id', $id)->count();
        $questions = Question::where('topic_id', $id)
                             ->orderBy('id', 'desc')
                             ->cursorPaginate(10);

        $topics = Topic::where('parent_id', null)->orderBy('id', 'asc')->get();

        return view('dashboard.questions.changetopic')
                    ->withQuestions($questions)
                    ->withTopics($topics)
                    ->withTotalquestions($totalquestions);
    }

    public function storeQuestionsTopic(Request $request)
    {
        $this->validate($request,array(
            'name'        => 'required|string|max:191',
        ));

        $topic = new Topic;
        $topic->name = $request->name;
        $topic->save();

        Cache::forget('topics');
        Session::flash('success', 'Topic created successfully!');
        return redirect()->route('dashboard.questions');
    }

    public function updateQuestionsTopic(Request $request, $id)
    {
        $this->validate($request,array(
            'name' => 'required|string|max:191',
        ));

        $topic = Topic::find($id);;
        $topic->name = $request->name;
        $topic->save();

        Cache::forget('topics');
        Session::flash('success', 'Topic updated successfully!');
        return redirect()->route('dashboard.questions');
    }

    public function deleteQuestionsTopic($id)
    {
        $topic = Topic::find($id);
        $topic->delete();

        Cache::forget('topics');
        Session::flash('success', 'Topic deleted successfully!');
        return redirect()->route('dashboard.questions');
    }

    public function storeQuestion(Request $request)
    {
        // dd($request->file('image'));
        $this->validate($request,array(
            'topic_id'    => 'required|string|max:191',
            'question'    => 'required',
            'option1'     => 'required|string|max:191',
            'option2'     => 'required|string|max:191',
            'option3'     => 'required|string|max:191',
            'option4'     => 'required|string|max:191',
            'answer'      => 'required',
            'image'       => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3000',
            'explanation' => 'sometimes|max:2048',
        ));

        $question             = new Question;
        $question->topic_id   = $request->topic_id;
        $question->question   = Purifier::clean($request->question, 'youtube');
        $question->option1    = $request->option1;
        $question->option2    = $request->option2;
        $question->option3    = $request->option3;
        $question->option4    = $request->option4;
        $question->answer     = $request->answer;
        $question->save();

        $topic = Topic::find($request->topic_id);
        if ($topic) {
            // This method triggers the fast, incremental update up the hierarchy.
            $topic->recalculateAggregatedQuestionCount(); 
        }

        // image upload
        if($request->hasFile('image')) {
            $image    = $request->file('image');
            $filename = random_string(5) . time() .'.' . "webp";
            $location = public_path('images/questions/'. $filename);
            Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $questionimage              = new Questionimage;
            $questionimage->question_id = $question->id;
            $questionimage->image       = $filename;
            $questionimage->save();
        }

        if($request->explanation) {
            $questionexplanation              = new Questionexplanation;
            $questionexplanation->question_id = $question->id;
            $questionexplanation->explanation = $request->explanation;
            $questionexplanation->save();
        }

        Session::flash('success', 'Question created successfully!');
        return redirect()->back();
    }

    public function storeExcelQuestion(Request $request)
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
                $question             = new Question;
                $question->topic_id   = $collection['topic_id'];
                $imageName = $collection['image_name'] ?? null;

                if ($imageName) {
                    $question->question = $collection['question'] . '<br><img src="https://bcsexamaid.com/images/questions/' . $imageName . '">';
                } else {
                    $question->question = $collection['question'];
                }
                
                $question->option1    = $collection['option1'];
                $question->option2    = $collection['option2'];
                $question->option3    = $collection['option3'];
                $question->option4    = $collection['option4'];
                $question->answer     = $collection['answer'];
                $question->save();

                // APATOT KORA HOCCHE NA...
                // if($request->hasFile('image')) {
                //     $image    = $request->file('image');
                //     $filename = random_string(5) . time() .'.' . "webp";
                //     $location = public_path('images/questions/'. $filename);
                //     Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
                //     $questionimage              = new Questionimage;
                //     $questionimage->question_id = $question->id;
                //     $questionimage->image       = $filename;
                //     $questionimage->save();
                // }

                if($imageName) {
                    $questionimage              = new Questionimage;
                    $questionimage->question_id = $question->id;
                    $questionimage->image       = $imageName;
                    $questionimage->save();
                }
                

                if($collection['explanation'] != null) {
                    $questionexplanation              = new Questionexplanation;
                    $questionexplanation->question_id = $question->id;
                    $questionexplanation->explanation = $collection['explanation'];
                    $questionexplanation->save();
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
            }
        }
        
        Session::flash('success', 'Questions uploaded successfully!');
        return redirect()->route('dashboard.questions');
    }

    public function updateTopicIdsFromExcel(Request $request)
    {
        ini_set('memory_limit', '512000000');
        // ১. ফাইল ভ্যালিডেশন
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            // ২. ফাস্ট এক্সেল দিয়ে ফাইল রিড করা
            (new FastExcel)->import($request->file('file'), function ($row) {
                
                // ৩. শুধুমাত্র id এবং topic_id আছে কিনা নিশ্চিত করা
                if (isset($row['id']) && isset($row['topic_id'])) {
                    
                    // ৪. ডাটাবেসে আপডেট কুয়েরি (এটি দ্রুত কাজ করার জন্য সরাসরি DB ব্যবহার করা হয়েছে)
                    DB::table('questions')
                        ->where('id', $row['id'])
                        ->update(['topic_id' => $row['topic_id']]);
                }
            });

            // (new FastExcel)->import($request->file('file'), function ($row) {
            //     // শুধুমাত্র id এবং topic_id নিশ্চিত করা
            //     if (isset($row['id']) && isset($row['topic_id'])) {
                    
            //         $updateData = [
            //             'topic_id' => $row['topic_id']
            //         ];

            //         // যদি এক্সেল ফাইলে image_name থাকে এবং তা খালি না হয়
            //         if (!empty($row['image_name'])) {
            //             // ১. বর্তমান প্রশ্নটি ডাটাবেস থেকে নেওয়া

            //             // ২. প্রশ্নের সাথে HTML ইমেজ ট্যাগ যোগ করা
            //             $htmlImage = $row['question'] . '<br><img src="https://bcsexamaid.com/images/questions/' . $row['image_name'] . '">';
                        
            //             $updateData['question'] = $htmlImage;
            //         } else {
            //             $updateData['question'] = $row['question'];
            //         }

            //         // ৩. একবারে আপডেট করা
            //         DB::table('questions')
            //             ->where('id', $row['id'])
            //             ->update($updateData);
            //     }
            // });

            return back()->with('success', 'Topic IDs updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function updateQuestion(Request $request, $id)
    {
        // dd($request->topic_id);
        $this->validate($request,array(
            'topic_id'    => 'required|string|max:191',
            'question'    => 'required',
            'option1'     => 'required|string|max:191',
            'option2'     => 'required|string|max:191',
            'option3'     => 'required|string|max:191',
            'option4'     => 'required|string|max:191',
            'answer'      => 'required',
            'image'       => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3000',
            'explanation' => 'sometimes|max:2048',
        ));


        $question             = Question::findOrFail($id);
        $question->topic_id   = $request->topic_id;
        $question->question   = $request->question;
        $question->option1    = $request->option1;
        $question->option2    = $request->option2;
        $question->option3    = $request->option3;
        $question->option4    = $request->option4;
        $question->answer     = $request->answer;
        $question->save();

        // image upload
        if($request->hasFile('image')) {
            if($question->questionimage) {
                $image_path = public_path('images/questions/'. $question->questionimage->image);
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
                $question->questionimage->delete();
            }
            $image      = $request->file('image');
            $filename   = random_string(5) . time() .'.' . "webp";
            $location   = public_path('images/questions/'. $filename);
            Image::make($image)->resize(350, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $questionimage = new Questionimage;
            $questionimage->question_id = $question->id;
            $questionimage->image = $filename;
            $questionimage->save();
        }

        if($request->explanation) {
            if($question->questionexplanation) {
                $question->questionexplanation->explanation = $request->explanation;
                $question->questionexplanation->save();
            } else {
                $questionexplanation = new Questionexplanation;
                $questionexplanation->question_id = $question->id;
                $questionexplanation->explanation = $request->explanation;
                $questionexplanation->save();
            }
        }

        Session::flash('success', 'Question updated successfully!');
        // return redirect()->route('dashboard.questions');
        return redirect()->back();
        // dd(url()->previous());
        // if(request()->routeIs('dashboard.questionstopicbased')) {
        //     return redirect()->route('dashboard.questionstopicbased', $request->topic_id);
        // } else {
        //     return redirect()->route('dashboard.questions');
        // }
    }

    public function deleteQuestion($id)
    {
        $question = Question::find($id);
        if($question->questionimage) {
            $image_path = public_path('images/questions/'. $question->questionimage->image);
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
            $question->questionimage->delete();
        }
        if($question->questionexplanation) {
            $question->questionexplanation->delete();
        }
        $question->delete();

        Session::flash('success', 'Question deleted successfully!');
        return redirect()->route('dashboard.questions');
    }

    public function sendNotificationQuestion($id)
    {
        $question = Question::findOrFail($id);
        $answertext = $question['option' . $question->answer];
        // LIVE HOILE ETA DEOA HOBE
        // LIVE HOILE ETA DEOA HOBE
        $strippedquestion = strip_tags($question->question) != "" ? strip_tags($question->question) : 'ছবিতে প্রশ্নটি দেখুন ও উত্তর করুন!';
        OneSignal::sendNotificationToAll(
            "উত্তর দেখতে নোটিফিকেশনে ক্লিক করুন",
            $url = null, 
            $data = array("a" => 'answer', "b" => $answertext, 'c' => $question->questionexplanation ? $question->questionexplanation->explanation : '', "d" => $question->question),
            $buttons = null, 
            $schedule = null,
            $headings = $strippedquestion,
        );

        // $strippedquestion = strip_tags($question->question) != "" ? strip_tags($question->question) : 'ছবিতে প্রশ্নটি দেখুন ও উত্তর করুন!';
        // OneSignal::sendNotificationToUser(
        //     "উত্তর দেখতে নোটিফিকেশনে ক্লিক করুন",
        //     ['94c77039-3ea3-453f-9bc3-027138785563'], // 716ffeb3-f6c2-4a4a-a253-710f339aa863
        //     $url = null, 
        //     $data = array("a" => 'answer', "b" => $answertext, 'c' => $question->questionexplanation ? $question->questionexplanation->explanation : '', "d" => $question->question),
        //     $buttons = null, 
        //     $schedule = null,
        //     $headings = $strippedquestion,
        // );

        Session::flash('success', 'Question sent in Notification successfully!');
        return redirect()->back();
    }

    public function getOldQSTopicWise($topic_id)
    {
        // ১. ডাটাবেস থেকে নির্দিষ্ট topic_id এর প্রশ্নগুলো কুয়েরি করা
        // মেমোরি সেভ করার জন্য আমরা cursor() ব্যবহার করছি
        $questions = Question::where('topic_id', $topic_id)->get();

        // ২. যদি কোনো ডাটা না থাকে তবে ইউজারকে ফেরত পাঠানো (Optional)
        if ($questions->isEmpty()) {
            return back()->with('error', 'এই টপিকে কোনো প্রশ্ন পাওয়া যায়নি!');
        }

        // ৩. FastExcel ব্যবহার করে কাস্টম কলাম নেম সেট করে এক্সপোর্ট করা
        return (new FastExcel($questions))->download('questions_topic_' . $topic_id . '.xlsx', function ($question) {
            return [
                'topic_id' => strval($question->topic_id ?? ''),
                'question' => strval($question->question ?? ''),
                'option1'  => strval($question->option1 ?? ''),
                'option2'  => strval($question->option2 ?? ''),
                'option3'  => strval($question->option3 ?? ''),
                'option4'  => strval($question->option4 ?? ''),
                'answer'   => strval($question->answer ?? ''),
                'id'       => strval($question->id ?? ''),
            ];
        });
    }

    public function getFullPathAttribute()
    {
        $topics = Topic::get();

        echo '<table>';
        foreach($topics as $topic) {
            $path = collect([$topic->name]);
            $parent = $topic->parent;
            while ($parent) {
                $path->prepend($parent->name);
                $parent = $parent->parent;
            }
            echo '<tr><td>' . $path->join('→') . '</td><td>' . $topic->id . '</td></tr>';
        }
        echo '<table>';
        
    }

    protected const CACHE_KEY = 'denormalized_topic_paths';

    public function rebuildTopicsCache(string $secret)
    {
        $requiredSecret = env("TOPIC_PATH_SECRET");

        // STEP 1: Security check to ensure the URL is being hit by an authorized user
        if (empty($requiredSecret) || $secret !== $requiredSecret) {
            // Log this attempt and return a generic error
            logger()->warning('Unauthorized attempt to rebuild cache.', ['ip' => request()->ip()]);
            return response('Unauthorized.', 401);
        }

        // --- Start Cache Generation Logic ---

        // 1. Fetch all topics, eager loading the parent relationship for efficiency
        $topics = Topic::with('parent')->get();
        $cachedPaths = [];

        // 2. Iterate and build the full path for each topic
        foreach ($topics as $topic) {
            $path = collect([$topic->name]);
            $parent = $topic->parent;

            // Loop up the hierarchy until the top parent is reached
            while ($parent) {
                $path->prepend($parent->name);
                // Continue moving up the tree
                $parent = $parent->parent; 
            }

            // 3. Store the ID and the full path string (e.g., 'Category A → Sub B → Topic Name')
            $cachedPaths[] = [
                'id' => $topic->id,
                'text' => $path->join('→'),
            ];
        }

        // 4. Store the entire array in the cache permanently, overwriting the old one
        Cache::forever(self::CACHE_KEY, $cachedPaths);
        
        // --- End Cache Generation Logic ---

        $count = count($cachedPaths);
        
        // Return a successful, informative response
        // return response("Cache successfully rebuilt! **$count** topic paths were generated and stored in the cache key **" . self::CACHE_KEY . "**.", 200);
        Session::flash('success', "Cache successfully rebuilt! **$count** topic paths were generated and stored in the cache key **");
        return redirect()->route('dashboard.index');
    }

    public function readTopicsCache()
    {
        // Retrieve the data from the cache. Returns null if the key doesn't exist.
        $cachedData = Cache::get(self::CACHE_KEY);

        if ($cachedData === null) {
            return response('The topic path cache is empty or has expired.', 200);
        }

        $count = count($cachedData);

        // You can return the data as JSON for easy inspection
        return response()->json([
            'status' => 'success',
            'cache_key' => self::CACHE_KEY,
            'item_count' => $count,
            'data_sample' => array_slice($cachedData, 0, 10), // Show first 10 items
            'full_data' => $cachedData, // Show all data
        ], 200);
    }

    // searchTopic is placed under APIController
    // searchTopic is placed under APIController
    // searchTopic is placed under APIController

    public function updateAllTopicCounts($softtoken, $offset = 0, $limit = 20)
    {
        // Ensure limit is 20 for this specific process
        $limit = (int) $limit;
        $offset = (int) $offset;

        // 1. Token Check
        if ($softtoken !== env('SOFT_TOKEN')) {
            return response()->json(['success' => false, 'message' => 'Invalid token'], 401);
        }

        // 2. Count Total Topics
        $totalTopics = Topic::count();
        
        // 3. Fetch the specific chunk (20 topics)
        // We order by parent_id DESC and then by id to ensure a consistent, repeatable order across chunks.
        $topicsToProcess = Topic::orderBy('parent_id')
                                ->orderBy('id')
                                ->offset($offset)
                                ->limit($limit)
                                ->get();

        $updatedCount = 0;

        // 4. Iterate and Recalculate for the current chunk (Fast execution)
        foreach ($topicsToProcess as $topic) {
            // We run the heavy recursive logic only for the 20 topics in this batch
            $newAggregatedCount = $topic->getTotalQuestionCountAggregated();
            // dd($newAggregatedCount);
            
            if ($topic->total_questions_sum !== $newAggregatedCount) {
                DB::table('topics')
                    ->where('id', $topic->id)
                    ->update(['total_questions_sum' => $newAggregatedCount]);
                $updatedCount++;
            }
        }

        // 5. Determine Next Steps and Response
        $nextOffset = $offset + $limit;
        $hasMore = $nextOffset < $totalTopics;

        if (!$hasMore) {
            // 6. Final Step: Clear Cache ONLY after the last chunk is processed
            // Example: Cache::forget('topics_parent_aggregated_root');
        }

        return response()->json([
            'success' => true,
            'message' => $hasMore ? 'Chunk processed. Call next offset to continue.' : 'All topics processed successfully.',
            'total_topics_in_db' => $totalTopics,
            'topics_processed_in_chunk' => $topicsToProcess->count(),
            'topics_updated_in_chunk' => $updatedCount,
            'next_offset' => $hasMore ? $nextOffset : null,
            'has_more' => $hasMore
        ]);
    }
}
