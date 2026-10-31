<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\userRepository;

use App\Models\User;
use App\Models\Userotp;
use App\Models\Examcategory;
use App\Models\Question;
use App\Models\Course;
use App\Models\Courseexam;
use App\Models\Exam;
use App\Models\Examquestion;
use App\Models\Topic;
use App\Models\Package;
use App\Models\Temppayment;
use App\Models\Message;
use App\Models\Material;
use App\Models\Meritlist;
use App\Models\Reportedquestion;
use App\Models\PayoutRequest;
use App\Models\AmbassadorProfile;
use Illuminate\Support\Facades\Http;

use Hash;
use Carbon\Carbon;
use DB;
use OneSignal;
use Cache;

class APIController extends Controller
{
    public function test()
    {
        
    } 

    public function generateOTP(Request $request)
    {
        // return response()->json(['success' => false, 'message' => 'Blocking temporarily.'], 404);
        
        $this->validate($request,array(
            'mobile'            => 'required',
            'captcha_token'     => 'required',
            'device_id'     => 'required',
            'softtoken'         => 'required|max:191'
        ));

        // ১. ক্লাউডফ্লেয়ার ভেরিফিকেশন (সবার আগে)
        $captchaToken = $request->input('captcha_token');
        $verify = \Illuminate\Support\Facades\Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret'   => config('services.turnstile.secret'), // Cloudflare থেকে পাওয়া Secret Key
            'response' => $captchaToken,
            'remoteip' => $request->ip(),
        ]);
        if (!$verify->json('success')) {
            return response()->json([
                'success' => false,
                'message' => 'হিউম্যান ভেরিফিকেশন সফল হয়নি! অ্যাপটি পুনরায় চালু করুন।'
            ], 403);
        }

        // ২. ডিভাইস ভিত্তিক ব্লক চেক
        $deviceId = $request->input('device_id');
        $device_key = 'otp_limit:' . $deviceId;

        // ৫ বার চেষ্টার পর ৩ দিনের জন্য ব্লক (২৫৯২০০ সেকেন্ড)
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($device_key, 5)) {
            return response()->json(['message' => 'বার বার সার্ভারে হিট করার জন্য আপনার ডিভাইস ৩ দিনের জন্য ব্লক করা হলো!'], 429);
        }

        // ৩. হিট কাউন্টার এখানে বসাতে হবে (টার্নস্টাইল পাস হওয়ার ঠিক পরে)
        \Illuminate\Support\Facades\RateLimiter::hit($device_key, 259200); // হিট কাউন্ট!

        if($request->softtoken == env('SOFT_TOKEN')) {

            $pool = '0123456789';
            $otp = substr(str_shuffle(str_repeat($pool, 4)), 0, 4);

            $mobile_number = 0;
            if(strlen($request->mobile) == 11) {
                $mobile_number = $request->mobile;
            } elseif(strlen($request->mobile) > 11) {
                if (strpos($request->mobile, '+') !== false) {
                    $mobile_number = substr($request->mobile, -11);
                }
            }

            // SPAM PREVENTION Layers 1
            $ip_address = $request->ip(); // 🌟 Get the current IP address 🌟

            // 🌟 NEW SPAM CHECK: IMMEDIATE COOLDOWN ON IP (Layer 1.1) 🌟
            $last_request_time = Userotp::where('ip_address', $ip_address)
                        ->latest() // Get the most recent attempt
                        ->value('created_at');

            if ($last_request_time && Carbon::parse($last_request_time)->diffInSeconds(Carbon::now()) < 60) {
                return response()->json([
                    'success' => false, 
                    'message' => 'একই নেটওয়ার্ক থেকে ১ মিনিটের ভেতরে বার বার OTP পাঠাচ্ছেন! পরে চেষ্টা করুন।'
                ], 429);
            }

            // 🌟 NEW SPAM PREVENTION Layer 1.2: IP Rate Limit (3 attempts per 2 hour from any IP)
            $ip_requests_last_hour = Userotp::where('ip_address', $ip_address)
                ->where('created_at', '>=', Carbon::now()->subHours(2)->toDateTimeString())
                ->count();
            
            if($ip_requests_last_hour > 3) {
                 return response()->json(['success' => false, 'message' => 'আপনি বার বার চেষ্টা করছেন! পরে চেষ্টা করুন।'], 429);
            }

            // SPAM PREVENTION Layer 2
            $triedlastfivedays = Userotp::where('mobile', $mobile_number)
                                        ->where('created_at', '>=', Carbon::now()->subDays(3)->toDateTimeString())
                                        ->count();

            if($triedlastfivedays < 3) {
                // SPAM PREVENTION Layer 2
                $triedlasttwentyminutes = Userotp::where('mobile', $mobile_number)
                                        ->where('created_at', '>=', Carbon::now()->subMinutes(30)->toDateTimeString())
                                        ->count();

                if($triedlasttwentyminutes < 1) {
                    // FOR PLAY CONSOLE TESTING PURPOSE
                    // FOR PLAY CONSOLE TESTING PURPOSE
                    if($mobile_number == '01751398392' || $mobile_number == '01842828885') {
                       $otp = env('SMS_GATEWAY_PLAY_CONSOLE_TEST_OTP');
                    }

                    // TEXT MESSAGE OTP
                    // TEXT MESSAGE OTP

                    // NEW PANEL
                    $url = config('sms.url2');
                    $api_key = config('sms.api_key');
                    $senderid = config('sms.senderid');
                    $number = $mobile_number;
                    $message = $otp . ' is your pin for BCS Exam Aid App.';

                    $data = [
                        "api_key" => $api_key,
                        "senderid" => $senderid,
                        "number" => $number,
                        "message" => $message,
                    ];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $jsonresponse = json_decode($response);

                    if($jsonresponse->response_code == 202) {
                        // Session::flash('success', 'SMS সফলভাবে পাঠানো হয়েছে!');
                    } elseif($jsonresponse->response_code == 1007) {
                        // Session::flash('warning', 'অপর্যাপ্ত SMS ব্যালেন্সের কারণে SMS পাঠানো যায়নি!');
                    } else {
                        // Session::flash('warning', 'দুঃখিত! SMS পাঠানো যায়নি!');
                    }

                    // Userotp::where('mobile', $number)->delete(); // এটাকার প্রিভেন্ট করার জন্য ডিলেট ক্রতেসি না...

                    $newOTP = new Userotp();
                    $newOTP->mobile = $number;
                    $newOTP->otp = $otp;
                    $newOTP->ip_address = $ip_address; // 🌟 Save the IP 🌟
                    $newOTP->save();

                    // return $otp;
                    return response()->json(['success' => true, 'message' => 'OTP পাঠানো হয়েছে!']);
                } else {
                    // return 'Requested within 30 minutes!';
                    return response()->json(['success' => false, 'message' => '৩০ মিনিট পরে চেষ্টা করুন!'], 429);
                }
                // SPAM PREVENTION Layer 2
            } else {
                // return 'Requested too many times!';
                return response()->json(['success' => false, 'message' => 'Requested too many times!'], 429);

            }
            // SPAM PREVENTION Layer 2
            
        } else {
            // return 'Invalid Soft Token';
            return response()->json(['success' => false, 'message' => 'Invalid Soft Token!'], 403);
        }
    }

    public function loginOrCreate(Request $request)
    {    
        $userotp = Userotp::where('mobile', $request['mobile'])
                          ->orderBy('id', 'DESC')
                          ->first(); // latest টা নেওয়া হচ্ছে, এটাকার প্রিভেন্ট করার জন্য OTP ডিলেট ক্রতেসি না...
        if($userotp->otp == $request['otp']) {
            $user = User::where('mobile', $request['mobile'])->first();
            if ($user) {
                // $user->is_verified = 1;
                // $user->save();
                // $this->deleteOTP($request['mobile']); // এটাকার প্রিভেন্ট করার জন্য ডিলেট ক্রতেসি না...
                // $userTokenHandler = new UserTokenHandler();
                // $user = $userTokenHandler->regenerateUserToken($user);
                // $user->load('roles');
                $userdata = [
                    'success' => true,
                    'user' => $user,
                    'message' => 'লগইন সফল হয়েছে!',
                ];
                // if($user && Hash::check($request['password'], $user->password)){
                //     $userTokenHandler = new UserTokenHandler();
                //     $user = $userTokenHandler->regenerateUserToken($user);
                //     $user->load('roles');
                //     return $user;
                // }
            } else {
                $newUser = new User();
                DB::beginTransaction();
                try {
                    $newUser->uid = $request['mobile'];
                    $newUser->mobile = $request['mobile'];
                    $newUser->name = 'No Name';
                    $package_expiry_date = Carbon::now()->addDays(3)->format('Y-m-d') . ' 23:59:59';
                    $newUser->package_expiry_date = $package_expiry_date;
                    $newUser->role = 'user';
                    $newUser->password = Hash::make('secret123');
                    $newUser->save();
                } catch (\Exception $e) {
                    DB::rollBack();
                    // throw new \Exception($e->getMessage());
                    $userdata = [
                        'success' => false,
                        'message' => 'দুঃখিত! আবার চেষ্টা করুন।',
                    ];
                }
                DB::commit();
                $user = User::where('mobile', $request['mobile'])->first();
                // $user->save();
                // $this->deleteOTP($request['mobile']); // এটাকার প্রিভেন্ট করার জন্য ডিলেট ক্রতেসি না...
                $userdata = [
                    'success' => true,
                    'user' => $user,
                    'message' => 'রেজিস্ট্রেশন সফল হয়েছে!',
                ];
            }
        }  else {
            $userdata = [
                'success' => false,
                'message' => 'সঠিক OTP প্রদান করুন!',
            ];
            // throw new \Exception('Invalid OTP');
        }

        if ($userdata) {
            return response()->json($userdata, 200);
        } else {
            return response()->json(['message' => 'Invalild Credentials'], 401);
        }
        return null;
    }

    public function deleteOTP($mobile)
    {
        Userotp::where('mobile', $mobile)->delete();
    }

    public function checkUid($softtoken, $phonenumber)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $user = User::where('mobile', substr($phonenumber, -11))->first();
            // dd($user);

            if($user) {
                return response()->json([
                    'success' => true,
                    'uid' => $user->uid,
                    'name' => $user->name,
                    'mobile' => $user->mobile,
                    'role' => $user->role,
                    'referral_code' => $user->referral_code,
                ]);
            } else {
                return response()->json([
                    'success' => false
                ]);
            }
            
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function checkPackageValidity($softtoken, $phonenumber)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $user = User::where('mobile', substr($phonenumber, -11))->first();
            if($user) {
               return response()->json([
                    'success' => true,
                    'package_expiry_date' => date('Y-m-d H:i:s', strtotime($user->package_expiry_date)),
                ]); 
           } else {
                return response()->json([
                    'success' => false
                ]);
           }
            
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function addUser(Request $request)
    {
        $this->validate($request,array(
            'uid'         => 'required|max:191|unique:users,uid',
            'name'        => 'required|max:191',
            'mobile'      => 'required|max:191',
            'onesignal_id'      => 'sometimes|max:191',
            'softtoken'   => 'required|max:191'
        ));

        if($request->softtoken == env('SOFT_TOKEN'))
        {
            $user = new User;
            $user->uid = $request->uid;
            $user->onesignal_id = $request->onesignal_id;
            $package_expiry_date = Carbon::now()->addDays(1)->format('Y-m-d') . ' 23:59:59';
            // dd($package_expiry_date);
            $user->package_expiry_date = $package_expiry_date;
            $user->name = $request->name;
            $user->role = 'user';
            $user->mobile = substr($request->mobile, -11);
            $user->password = Hash::make('12345678');
            $user->save();
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function addOneSignalData(Request $request)
    {
        $this->validate($request,array(
            'uid'         => 'required',
            'mobile'      => 'required|max:191',
            'onesignal_id'      => 'sometimes|max:191',
            'softtoken'   => 'required|max:191'
        ));

        $user = User::where('mobile', $request->mobile)->first();

        if($user && $request->softtoken == env('SOFT_TOKEN'))
        {
            $user->uid = $request->uid;
            $user->onesignal_id = $request->onesignal_id;
            $user->save();
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function updateUser(Request $request)
    {
        $this->validate($request,array(
            'mobile'         => 'required',
            'uid'         => 'required',
            'onesignal_id'         => 'sometimes',
            'name'        => 'required|max:191',
            'softtoken'   => 'required|max:191'
        ));
        // DB::beginTransaction();
        $user = User::where('mobile', $request->mobile)->first();

        if($user && $request->softtoken == env('SOFT_TOKEN'))
        {

            $user->name = $request->name;
            $user->uid = $request->uid;
            $user->onesignal_id = $request->onesignal_id;
            $user->save();
            // DB::commit();
            return response()->json([
                'success' => true
            ]); 
        } else {
            // DB::commit();
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getCourses($softtoken, $coursetype)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $courses = Cache::remember('courses'.$coursetype, 10 * 24 * 60 * 60, function () use ($coursetype) {
                 $courses = Course::select('id', 'name')
                             ->where('status', 1) // take only active courses
                             ->where('type', $coursetype) // 1 = Course, 2 = BJS MT, 3 = Bar MT, 4 = Free MT, 5 = QB
                             ->orderBy('priority', 'asc')
                             ->get();
                 foreach($courses as $course) {
                     $course->examcount = $course->courseexams->count();
                     $course->makeHidden('courseexams');
                 }
                 return $courses;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'courses' => $courses,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getCoursesCategoryWise($softtoken, $category)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $courses = Cache::remember('categorywisecourses'.$category, 10 * 24 * 60 * 60, function () use ($category) {
                 $courses = Course::select('id', 'name')
                             // status check korar dorkar nai, live check korbo, status active শুধু চলমান কোর্সে যাবে
                             ->where('category', $category) // 1 = BCS, 2 = Primary, 3 = Bank, 4 = NTRCA, 5 = NSI/DGFI and Others, 6 = QB
                             ->where('live', 1) // live থাকলে কোর্স ক্যাটাগরির ভেতরে শো করবে
                             ->orderBy('serial', 'asc') // priority ব্যবহৃত হবে চলমান কোর্সসমূহ বার এ, serial ব্যবহৃত হবে কোর্স্ ক্যাটাগরিতে
                             ->get();
                 foreach($courses as $course) {
                     $course->examcount = $course->courseexams->count();
                     $course->makeHidden('courseexams');
                 }
                 return $courses;
            });
            
            // dd($courses);
            return response()->json([
                'success' => true,
                'courses' => $courses,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function testCache()
    {
        // echo Cache::forget('courseexams');
    }

    public function getCourseExams($softtoken, $id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $courseexams = Cache::remember('courseexams_' . $id, 10 * 24 * 60 * 60, function () use ($id) {

            // 1. Fetch the base records from courseexams, eager load relationships, and join the exams table.
            $courseexams = Courseexam::select(
                    'courseexams.course_id', 
                    'courseexams.exam_id',
                    'exams.serial'
                )
                // Eager load relationships needed in the foreach loop to prevent N+1 query problem
                ->with('exam.examquestions') 
                
                // 2. Join the exams table to access the 'serial' column for sorting
                ->join('exams', 'exams.id', '=', 'courseexams.exam_id')
                
                // Ensure we only get exams for the specific course
                ->where('course_id', $id)
                
                // 3. APPLY TWO-TIER CONDITIONAL SORTING LOGIC:
                //    a) Primary Sort: By serial from the exams table (non-zero serials appear first)
                ->orderBy('exams.serial', 'desc')
                
                //    b) Secondary Sort: Fallback to exam_id descending (used when serials are tied, e.g., all 0)
                ->orderBy('courseexams.exam_id', 'desc')
                
                // Fetch the results
                ->get();

                // 4. Process the results for the frontend (add derived properties and hide redundant data)
                foreach ($courseexams as $courseexam) {
                    // Accessing $courseexam->exam->... is now highly efficient due to EAGER LOADING (with('exam'))
                    // Add derived properties
                    $courseexam->name = $courseexam->exam->name;
                    $courseexam->start = $courseexam->exam->available_from;
                    $courseexam->questioncount = $courseexam->exam->examquestions->count();
                    $courseexam->syllabus = $courseexam->exam->syllabus ?? 'N/A'; // Use null coalescing for cleaner check
                    $courseexam->alltimeavailability = $courseexam->exam->alltimeavailability;
                    $courseexam->lecture_url = $courseexam->exam->lecture_url;
                    
                    // Hide unnecessary exam details from the resulting JSON structure
                    $courseexam->exam->makeHidden([
                        'id', 'name', 'examcategory_id', 'price_type', 
                        'available_from', 'available_to', 'syllabus', 
                        'created_at', 'updated_at', 'examquestions', 'lecture_url',
                        'alltimeavailability'
                    ]);
                    
                    // The join makes the 'exam_id' available, but the 'exam' relationship itself is what holds the objects
                }
                
                return $courseexams;
            });

            return response()->json([
                'success' => true,
                'exams' => $courseexams,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getOtherCourseExams($softtoken, $coursetype)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $course = Course::select('id')
                             ->where('status', 1) // take only active courses
                             ->where('type', $coursetype) // 1 = Course, 2 = BJS MT, 3 = Bar MT, 4 = Free MT, 5 = QB
                             ->first(); 


            $courseexams = Cache::remember('courseexams'.$course->id, 10 * 24 * 60 * 60, function () use ($course) {
                $courseexams = Courseexam::select('course_id', 'exam_id')
                                         ->where('course_id', $course->id)
                                         // ->orderBy('exam_id', 'desc')
                                         ->join('exams', 'exams.id', '=', 'courseexams.exam_id')
                                         ->orderBy('exams.available_from', 'asc')
                                         ->get();

                foreach($courseexams as $courseexam) {
                    $courseexam->name = $courseexam->exam->name;
                    $courseexam->start = $courseexam->exam->available_from;
                    $courseexam->questioncount = $courseexam->exam->examquestions->count();
                    $courseexam->syllabus = $courseexam->exam->syllabus ? $courseexam->exam->syllabus : 'N/A';
                    $courseexam->alltimeavailability = $courseexam->exam->alltimeavailability;
                    $courseexam->exam->makeHidden('id', 'name', 'examcategory_id', 'price_type', 'available_from', 'available_to', 'syllabus', 'created_at', 'updated_at', 'examquestions', 'alltimeavailability');
                }
                return $courseexams;
            });
            // dd($courseexams);

            return response()->json([
                'success' => true,
                'exams' => $courseexams,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getCourseExamQuestions($softtoken, $id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $examquestions = Examquestion::select('exam_id', 'question_id')
                                     ->where('exam_id', $id)
                                     ->get();

            foreach($examquestions as $examquestion) {
                $examquestion = $examquestion->makeHidden(['question_id']);
                if($examquestion->question->questionexplanation) {
                    $examquestion->question->explanation = $examquestion->question->questionexplanation->explanation;
                }if($examquestion->question->questionimage) {
                    $examquestion->question->image = $examquestion->question->questionimage->image;
                }
                $examquestion->question = $examquestion->question->makeHidden(['topic_id', 'difficulty', 'created_at', 'updated_at', 'questionexplanation', 'questionimage']);
            }
            $exam = Exam::findOrFail($id);
            $exam->participation++;
            $exam->save();

            return response()->json([
                'success' => true,
                'questions' => $examquestions,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getTopicExamQuestions($softtoken, $id)
    {
        // ১. টোকেন ভেরিফিকেশন (সরাসরি চেক, দ্রুততর)
        if ($softtoken !== env('SOFT_TOKEN')) {
            return response()->json(['success' => false], 401);
        }

        try {
            // ২. টপিক এবং তার চিলড্রেনদের রিকার্সিভলি ইগার লোড করা
            // এটি ডাটাবেসে মাত্র ১-২টি কুয়েরি করবে, লুপ করবে না।
            $topic = Topic::with('children')->findOrFail($id);

            // ৩. মডেলের এক্সেসর ব্যবহার করে সকল চাইল্ড আইডি বের করা
            // আপনার মডেলের 'getDescendantIdsAttribute' মেথডটি এখানে ব্যবহার হচ্ছে
            $allTopicIds = $topic->descendant_ids;

            // ৪. কোয়েশ্চেন রিট্রিভাল (Atomic Select & Eager Loading)
            // অপ্রয়োজনীয় কলাম বাদ দিয়ে শুধু যা লাগবে তা সিলেক্ট করা হয়েছে (Performance Boost)
            $topicquestions = Question::whereIn('topic_id', $allTopicIds)
                ->with([
                    'questionexplanation:question_id,explanation', 
                    'questionimage:question_id,image'
                ])
                ->inRandomOrder()
                ->take(25)
                ->get(['id', 'question', 'option1', 'option2', 'option3', 'option4', 'answer']);

            // ৫. ডাটা ম্যাপিং (Eloquent Object-এর বদলে সরাসরি Array রিটার্ন করা দ্রুততর)
            $questions = $topicquestions->map(function ($q) {
                return [
                    'id'          => $q->id,
                    'question'    => $q->question,
                    'option1'     => $q->option1,
                    'option2'     => $q->option2,
                    'option3'     => $q->option3,
                    'option4'     => $q->option4,
                    'answer'      => $q->answer,
                    'explanation' => $q->questionexplanation->explanation ?? null,
                    'image'       => $q->questionimage->image ?? null,
                ];
            });

            // ৬. পার্টিসিপেশন কাউন্ট আপডেট (Atomic Increment - দ্রুততর এবং সেফ)
            Topic::where('id', $id)->increment('participation');

            return response()->json([
                'success'   => true,
                'questions' => $questions,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not found or server error'
            ], 404);
        }
    }

    public function getTopics($softtoken)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $topics = Cache::remember('topics', 10 * 24 * 60 * 60, function () {
                // $topics = Topic::limit(12)->get();
                $topics = Topic::where('parent_id', null)->orderBy('id', 'asc')->get();
                return $topics;
            });
            return response()->json([
                'success' => true,
                'topics' => $topics,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getPackages($softtoken)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $packages = Package::select('id', 'name', 'tagline', 'duration', 'price', 'strike_price', 'suggested')
                               ->where('status', 1)->get();

            return response()->json([
                'success' => true,
                'packages' => $packages,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function paymentProceed(Request $request)
    {
        $this->validate($request,array(
            'user_number'    =>   'required',
            'package_id'     =>   'required',
            'amount'         =>   'required',
            'trx_id'         =>   'required'
        ));

        $user = User::where('mobile', substr($request->user_number, -11))->first();
        $package = Package::findOrFail($request->package_id);
        
        if($request->softtoken == env('SOFT_TOKEN')) {
            if($user) {
                $temppayment = new Temppayment;
                $temppayment->user_id = $user->id;
                $temppayment->package_id = $request->package_id;
                $temppayment->uid = $user->uid;
                $temppayment->trx_id = $request->trx_id;
                $temppayment->amount = $request->amount;
                $temppayment->save();

                return response()->json([
                    'success' => true
                ]);
            } else {
                return response()->json([
                    'success' => false
                ]);
            }
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function storeMessage(Request $request)
    {
        $this->validate($request,array(
            'mobile'    =>   'required',
            'message'    =>   'required',
        ));

        $user = User::where('mobile', $request->mobile)->first();

        $message = new Message;
        $message->user_id = $user->id;
        $message->message = $request->message;
        $message->save();
        
        return response()->json([
            'success' => true
        ]);
    }

    public function getPaymentHistory($softtoken, $phonenumber)
    {
        $user = User::where('mobile', substr($phonenumber, -11))->first();

        if($user && $softtoken == env('SOFT_TOKEN'))
        {
            foreach($user->payments as $payment) {
                $payment->makeHidden(['id', 'user_id', 'package_id', 'uid', 'payment_status', 'card_type', 'store_amount', 'updated_at']);
            }
            // dd($user->payments);
            return response()->json([
                'success' => true,
                'paymenthistory' => $user->payments,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function sendSingleNotification(Request $request)
    {
        $this->validate($request,array(
            'mobile'               => 'required',
            'onesignal_id'         => 'required',
            'headings'             => 'required',
            'message'              => 'required',
            'softtoken'            => 'required|max:191'
        ));

        if($request->softtoken == env('SOFT_TOKEN'))
        {

            // $user = User::where('mobile', substr($request->mobile, -11))->first();
            
            OneSignal::sendNotificationToUser(
                $request->message,
                // ["a1050399-4f1b-4bd5-9304-47049552749c", "82e84884-917e-497d-b0f5-728aff4fe447"],
                $request->onesignal_id, // user theke na, direct input theke...
                $url = null, 
                $data = null, // array("answer" => $charioteer->answer), // to send some variable
                $buttons = null, 
                $schedule = null,
                $headings = $request->headings,
            );
        }
        return response()->json([
            'success' => true,
            'onesignal_id' => $request->onesignal_id
        ]); 
    }

    // public function getMaterials($softtoken)
    // {
    //     if($softtoken == env('SOFT_TOKEN'))
    //     {
    //         $materials = Cache::remember('lecturematerials', 10 * 24 * 60 * 60, function () {
    //             $materials = Material::where('status', 1) // 1 = active, 0 = inactive
    //                                  ->orderBy('id', 'desc')
    //                                  // ->select('id', 'type', 'title', 'author', 'author_desc')
    //                                  ->get();

    //             foreach($materials as $material) {
    //                 $material->makeHidden('status', 'updated_at'); // 'id', 
    //             }
    //             return $materials;
    //         });
    //         // dd($materials);
    //         $materials = $materials->sortByDesc('start');
    //         // return 'Test';
    //         return response()->json([
    //             'success' => true,
    //             'materials' => $materials,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'success' => false
    //         ]);
    //     }
    // }

    // ETA PORE USE KORA HOBE
    // ETA PORE USE KORA HOBE
    // ETA PORE USE KORA HOBE
    public function getMaterials($softtoken)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $materials = Cache::remember('lecturematerials', 10 * 24 * 60 * 60, function () {
                $materials = Material::where('status', 1) // 1 = active, 0 = inactive
                                     ->orderBy('id', 'desc')
                                     ->select('id', 'type', 'title', 'author', 'author_desc')
                                     ->get();

                // foreach($materials as $material) {
                //     $material->makeHidden('id', 'status', 'updated_at');
                // }
                return $materials;
            });
            // dd($materials);
            // $materials = $materials->sortByDesc('start');
            // return 'Test';
            return response()->json([
                'success' => true,
                'materials' => $materials,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getSingleMaterial($softtoken, $id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $material = Cache::remember('singlelecturematerial' . $id, 10 * 24 * 60 * 60, function () use ($id) {
                $material = Material::where('id', $id)
                                     ->select('id', 'type', 'title', 'author', 'author_desc', 'content', 'url', 'count', 'created_at')
                                     ->first();
                return $material;
            });            
            
            $materialforsave = Material::findOrFail($id);
            $materialforsave->count++;
            $materialforsave->save();

            return response()->json([
                'success' => true,
                'material' => $material,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function addExamResult(Request $request)
    {
        // return $request->all();
        $this->validate($request,array(
            'mobile'      => 'required',
            'course_id'   => 'required',
            'exam_id'     => 'required',
            'marks'       => 'required',
            'softtoken'   => 'required'
        ));

        if($request->softtoken == env('SOFT_TOKEN'))
        {
            $user = User::where('mobile', substr($request->mobile, -11))->first();

            $oldexamresultcheck = Meritlist::where('course_id', $request->course_id)
                                           ->where('exam_id', $request->exam_id)
                                           ->where('user_id', $user->id)
                                           ->first();

            if($oldexamresultcheck) {
                return response()->json(['success' => false], 401);
            } else {
                $examresult = new Meritlist;
                $examresult->course_id = $request->course_id;
                $examresult->exam_id = $request->exam_id;
                $examresult->user_id = $user->id;
                $examresult->marks = $request->marks;
                $examresult->save();

                Cache::forget('meritlist'.$request->course_id.$request->exam_id);
                Cache::forget('exam'.$request->exam_id);
            }

            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ]);
    }

    public function getMeritList($softtoken, $course_id, $exam_id)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $meritlists = Cache::remember('meritlist'.$course_id.$exam_id, 10 * 24 * 60 * 60, function () use ($course_id, $exam_id) {
                 $meritlists = Meritlist::where('course_id', $course_id)
                                        ->where('exam_id', $exam_id)
                                        ->get();

                 $rank = 1;
                 $previous = null;
                 foreach ($meritlists->sortByDesc('marks') as $score) {
                     if ($previous && $previous->marks != $score->marks) {
                         $rank++;
                     }
                     $score->rank = $rank;
                     $previous = $score;
                 }
                 foreach($meritlists as $meritlist) {
                     $meritlist->name = $meritlist->user->name;
                     $meritlist->makeHidden('id', 'created_at', 'updated_at', 'user_id', 'user');
                 }
                 return $meritlists;
            });
            $exam = Cache::remember('exam' . $exam_id, 10 * 24 * 60 * 60, function () use ($exam_id) {
                 $exam = Exam::select('name', 'participation', 'cutmark')->where('id', $exam_id)->first();
                 $exam->makeHidden('id', 'examcategory_id', 'duration');
                 return $exam;
            });
            
            return response()->json([
                'success' => true,
                'meritlists' => $meritlists,
                'exam' => $exam,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getExamCategories($softtoken)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            $examcategories = Cache::remember('examcategories', 21 * 24 * 60 * 60, function () {
                $examcategories = Examcategory::orderBy('id', 'asc')->get();
                return $examcategories;
            });
            return response()->json([
                'success' => true,
                'examcategories' => $examcategories,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function getQBCatWise($softtoken, $getexamcategory)
    {
        if($softtoken == env('SOFT_TOKEN'))
        {
            // $course = Course::select('id')
            //                  ->where('status', 1) // take only active courses
            //                  ->where('type', $coursetype) // 1 = Course, 2 = BJS MT, 3 = Bar MT, 4 = Free MT, 5 = QB
            //                  ->first();
            // UPORER TA THEKE ID ASBE 6, SETA HOCCHE QB ER COURSE ID

            $courseexamsreturn = Cache::remember('questionbank'.$getexamcategory, 10 * 24 * 60 * 60, function () use ($getexamcategory) {
                $allcatcourseexams = Courseexam::select('course_id', 'exam_id')
                                         ->where('course_id', 6) // MANUALLY BOSAY DILAM
                                         ->orderBy('id', 'desc') // DESC, aage exam_id chilo, pore eta priority variable diye kora hobe,apatoto id diye kaaj choltese...
                                         ->get();
                $courseexams = collect();
                foreach($allcatcourseexams as $courseexam) {
                    if($courseexam->exam->examcategory_id == $getexamcategory) {
                        $courseexam->name = $courseexam->exam->name;
                        $courseexam->start = $courseexam->exam->available_from;
                        $courseexam->questioncount = $courseexam->exam->examquestions->count();
                        $courseexam->syllabus = $courseexam->exam->syllabus ? $courseexam->exam->syllabus : 'N/A';
                        $courseexam->exam->makeHidden('id', 'name', 'examcategory_id', 'price_type', 'available_from', 'available_to', 'syllabus', 'created_at', 'updated_at', 'examquestions');
                        $courseexams->push($courseexam);
                    }
                }
                // dd($courseexams);
                return $courseexams;
            });
            // dd($courseexams);

            return response()->json([
                'success' => true,
                'exams' => $courseexamsreturn,
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }


    public function reportQuestion(Request $request)
    {
        // return $request->all();
        $this->validate($request,array(
            'mobile'      => 'required',
            'question'    => 'sometimes',
            'id'          => 'sometimes',
            'message'     => 'sometimes',
            'softtoken'   => 'required',
        ));


        if($request->softtoken == env('SOFT_TOKEN'))
        {
            $user = User::where('mobile', substr($request->mobile, -11))->first();
            if(isset($request->id)) {
                $reportedquestion = new Reportedquestion;
                $reportedquestion->question_id = $request->id;
                $reportedquestion->user_id = $user->id;
                $reportedquestion->message = $request->message;
                $reportedquestion->status = 0;
                $reportedquestion->save();
            } else {

                $question = Question::where('question', 'LIKE', "%$request->question%")->first();
                if($question) {
                   $reportedquestion = new Reportedquestion;
                   $reportedquestion->question_id = $question->id;
                   $reportedquestion->user_id = $user->id;
                   $reportedquestion->message = $request->message;
                   $reportedquestion->status = 0;
                   $reportedquestion->save();
                }
            }

            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    protected const CACHE_KEY = 'denormalized_topic_paths';

    public function searchTopics(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query) || strlen($query) < 3) {
            // Respect the minimum input length set in the frontend
            return response()->json(['results' => []]);
        }
        
        // 1. Get the full cached data
        $cachedData = Cache::get(self::CACHE_KEY, []);

        $lowerQuery = strtolower($query);

        // 2. Filter the cached array
        $results = array_filter($cachedData, function ($item) use ($lowerQuery) {
            // Check if the lowercase query exists in the lowercase text path
            return str_contains(strtolower($item['text']), $lowerQuery);
        });
        
        // 3. Re-index the array and limit results for performance
        $results = array_values($results);
        $results = array_slice($results, 0, 50); // Limit to top 50 matches

        return response()->json([
            'query' => $query,
            'results' => $results,
            'count' => count($results)
        ]);
    }

    // public function getParentWiseTopics($softtoken, $parent_id)
    // {
    //     if($softtoken == env('SOFT_TOKEN')) {
    //         $parentId = $parent_id;

    //         if($parentId == 0) {
    //             $parentId = null;
    //         }
            
    //         $cacheKey = 'topics_parent_' . ($parentId ?? 'root');
                
    //         $cacheDuration = 30 * 24 * 60 * 60; 

    //         $topics = Cache::remember($cacheKey, $cacheDuration, function () use ($parentId) {
                
    //             return Topic::select('id', 'name', 'parent_id')
    //                 ->where('parent_id', $parentId)
    //                 ->withCount('questions')
    //                 ->get();
    //         });


    //         return response()->json([
    //             'topics' => $topics
    //         ]);
    //     }
    // }

    public function getParentWiseTopics($softtoken, $parent_id)
    {
        if ($softtoken == env('SOFT_TOKEN')) {
            $parentId = $parent_id;

            if($parentId == 0) {
                $parentId = null;
            }

            $cacheKey = 'topics_parent_dynamic_aggregated_' . ($parentId ?? 'root');
            $cacheDuration = 30 * 24 * 60 * 60; 

            $topics = Cache::remember($cacheKey, $cacheDuration, function () use ($parentId) {
                
                // 1. Fetch topics at the current level, eager loading only one level of children
                //    This is needed to start the efficient recursive ID collection in the Topic model.
                $topics = Topic::with('children') 
                               ->where('parent_id', $parentId)
                               ->get();

                // 2. Map and calculate the total questions dynamically for each topic
                return $topics->map(function ($topic) {
                    
                    return [
                        'id' => $topic->id,
                        'name' => $topic->name,
                        'parent_id' => $topic->parent_id,
                        // Use a clean, consistent field name for Flutter
                        'total_questions_aggregated' => $topic->total_questions_sum,
                        'descendant_ids' => $topic->descendant_ids,
                    ];
                })->toArray();
            });
            
            return response()->json([
                'topics' => $topics
            ]);
        } else {
             return response()->json(['success' => false, 'message' => 'Invalid token'], 401);
        }
    }

    public function getTopicWiseQuestions(string $softtoken, int $topicId)
    {
        // 1. Token Validation
        if($softtoken !== env('SOFT_TOKEN')) {
            return response()->json(['success' => false], 401);
        }

        // 2. Caching Setup (Cache questions for a specific topic_id)
        $cacheKey = "questions_topic_{$topicId}";
        // Cache duration: 10 days
        $cacheDuration = 10 * 24 * 60 * 60; 

        $topicquestions = Cache::remember($cacheKey, $cacheDuration, function () use ($topicId) {
            
            // Eager load the explanation and image relationships to avoid N+1 queries
            $questions = Question::with(['questionexplanation', 'questionimage'])
                ->where('topic_id', $topicId)
                // ->inRandomOrder()
                // ->take(20)
                ->get();
            
            // Map and structure the data for the API response
            return $questions->map(function ($question) {
                // Attach nested data directly to the main question object
                $question->explanation = $question->questionexplanation->explanation ?? null;
                $question->image = $question->questionimage->image ?? null;

                // Hide unnecessary pivot columns for a clean payload
                return $question->makeHidden([
                    'topic_id', 
                    'difficulty', 
                    'created_at', 
                    'updated_at', 
                    'questionexplanation', // The original relationship objects
                    'questionimage'
                ])->toArray(); // Convert to array for final JSON
            })->values()->all(); // Ensure keys are reset for a JSON array
        });

        return response()->json([
            'success' => true,
            'questions' => $topicquestions,
        ]);
    }

    public function getAmbassadorProfile($softtoken, $mobile) {
        if($softtoken == env('SOFT_TOKEN')) {
            $user = User::where('mobile', $mobile)->first();
            $profile = $user->ambassadorProfile; // Relationship assuming
            $payouts = $user->payoutRequests()->latest()->take(10)->get();

            return response()->json([
                'success' => true,
                'balance' => $profile->balance,
                'total_earned' => $profile->total_earned,
                'payouts' => $payouts
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
        
    }

    public function requestAmbassadorPayout(Request $request) {
        try {
            // ১. বেসিক ভ্যালিডেশন
            if ($request->amount < 500 || $request->amount > 2000) {
                return response()->json(['success' => false, 'message' => '৫০০ থেকে ২০০০ টাকার মধ্যে রিকোয়েস্ট করুন']);
            }

            // ২. ট্রানজেকশন শুরু
            return DB::transaction(function () use ($request) {
                $user = User::find($request->user_id);
                
                if (!$user) {
                    throw new Exception("User not found in database.");
                }

                $profile = $user->ambassadorProfile;

                // ৩. ব্যালেন্স চেক
                if (!$profile || $profile->balance < $request->amount) {
                    return response()->json(['success' => false, 'message' => 'আপনার পর্যাপ্ত ব্যালেন্স নেই!']);
                }

                // ৪. পে-আউট রিকুয়েস্ট তৈরি করা
                // মনে রাখবেন: PayoutRequest মডেলে $fillable এ কলামগুলো থাকতে হবে
                PayoutRequest::create([
                    'user_id'        => $request->user_id,
                    'amount'         => $request->amount,
                    'payment_method' => $request->payment_method, 
                    'payment_number' => $request->payment_number,
                    'status'         => 0, 
                ]);
                
                // ৫. ব্যালেন্স ডিডাক্ট করা
                $profile->decrement('balance', $request->amount);

                return response()->json([
                    'success' => true, 
                    'message' => 'রিকোয়েস্ট সফল হয়েছে এবং ব্যালেন্স সমন্বয় করা হয়েছে।'
                ]);
            });

        } catch (Exception $e) {
            // এই লাইনটি আপনাকে বলে দেবে আসলে সমস্যাটা কোথায় (যেমন: Column not found)
            return response()->json([
                'success' => false, 
                'message' => 'Server Error: ' . $e->getMessage()
            ]);
        }
    }

    public function checkPromoCode(Request $request) {
        // ৬ অক্ষরের ভ্যালিডেশন
        $request->validate(['code' => 'required|size:6']);

        $owner = User::where('referral_code', $request->code)->first();

        if (!$owner) {
            return response()->json(['success' => false, 'message' => 'দুঃখিত, ভুল প্রোমো কোড!']);
        }

        if ($owner->id == $request->user_id) {
            return response()->json(['success' => false, 'message' => 'নিজের কোড ব্যবহার করা যাবে না!']);
        }

        // পেমেন্ট সফল হলে % বা দিন বাড়ানো বা ব্যালেন্সের কাজটি হবে

        return response()->json([
            'success' => true,
            'role' => $owner->role, // ambassador or user
            'message' => $owner->role == 'ambassador' 
                ? 'অভিনন্দন! আপনি ১০% ডিসকাউন্ট পাচ্ছেন।' 
                : 'সফল! পেমেন্টের পর আপনারা উভয়েই ১০ দিন বোনাস পাবেন।'
        ]);
    }










    public function testNotification()
    {
        OneSignal::sendNotificationToUser(
            'test',
            // ["a1050399-4f1b-4bd5-9304-47049552749c", "82e84884-917e-497d-b0f5-728aff4fe447"],
            "13cc498f-ebf7-4bb1-9ea6-2c8da09e0b31",
            $url = null, 
            $data = null, // array("answer" => $charioteer->answer), // to send some variable
            $buttons = null, 
            $schedule = null,
            $headings = 'Test',
        ); 

        
    }
}
