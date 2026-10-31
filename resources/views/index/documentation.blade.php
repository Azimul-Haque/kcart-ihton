@extends('layouts.index')
@section('title') ডকুমেন্টেশন | BCS Exam AID | বিসিএস-সহ সরকারি চাকরি পরীক্ষা প্রস্তুতির জন্য সেরা অনলাইন প্ল্যাটফর্ম @endsection

@section('third_party_stylesheets')
	<!-- Structured data JSON-LD (optional but highly recommended) -->
  <script type="application/ld+json">
    {
    "@context": "https://schema.org",
    "@type": "Website",
    "headline": "BCS Exam Aid: সরকারি চাকরির প্রস্তুতির সেরা অ্যাপের ডকুমেন্টেশন",
    "description": "পূর্ণাঙ্গ কোর্স, মডেল টেস্ট, প্রশ্নব্যাংক ও শীর্ষ ক্যাডারদের তৈরি ভিডিও-পিডিএফ লেকচার ম্যাটেরিয়াল। সব পরীক্ষার প্রস্তুতির বিস্তারিত গাইড।",
    "image": "{{ asset('images/bcs-exam-aid-banner.png') }}",
    "url": "{{ url()->current() }}",
    "author": {
        "@type": "Person",
        "name": "A. H. M. Azimul Haque"
      }
    }
</script>
@endsection

@section('content')
<section style="padding-top: 150px; padding-bottom: 50px; background-color: var(--light-3);">

	<!-- Main Content Layout (Sidebar and Body) -->
	    <div class="container">
	        <div class="row">
	            
	            <!-- Sidebar (Table of Contents for large screens) -->
	            <div class="col-lg-3 d-none d-lg-block">
	                <div class="sticky-top sidebar p-4 rounded-3 shadow-sm" style="top: 80px;">
	                    <h5 class="mb-3 text-primary">সূচিপত্র</h5>
	                    <ul class="list-unstyled">
	                        <li class="mb-2"><a href="#mission" class="text-decoration-none text-dark sidebar-link p-2 d-block">১. লক্ষ্য ও উদ্দেশ্য</a></li>
	                        <li class="mb-2"><a href="#comprehensive" class="text-decoration-none text-dark sidebar-link p-2 d-block">২. পূর্ণাঙ্গ কোর্স</a></li>
	                        <li class="mb-2"><a href="#model-test" class="text-decoration-none text-dark sidebar-link p-2 d-block">৩. মডেল টেস্ট ব্যবস্থা</a></li>
	                        <li class="mb-2"><a href="#question-bank" class="text-decoration-none text-dark sidebar-link p-2 d-block">৪. প্রশ্নব্যাংক অনুশীলন</a></li>
	                        <li class="mb-2"><a href="#materials" class="text-decoration-none text-dark sidebar-link p-2 d-block">৫. বিশেষ লেকচার ম্যাটেরিয়াল</a></li>
	                    </ul>
	                </div>
	            </div>

	            <!-- Documentation Body -->
	            <div class="col-lg-9">
	                
	                <!-- 1. Mission and Target -->
	                <section id="mission" class="mb-5 section-anchor">
	                    <h2 class="pb-2 border-bottom text-primary mb-4 fw-bold">১. আমাদের লক্ষ্য ও উদ্দেশ্য</h2>
	                    <p class="fs-5 text-secondary">আমাদের মূল লক্ষ্য হলো— কর্মব্যস্ত বিসিএস এবং অন্যান্য সরকারি চাকরি প্রার্থীদের দোরগোড়ায় সর্বাধুনিক অনলাইন প্রস্তুতি সেবা পৌঁছে দেওয়া। দেশের যেকোন প্রান্তে বসে প্রতিযোগিতামূলক পরীক্ষার প্রস্তুতি নেওয়ার সুযোগ নিশ্চিত করাই আমাদের প্রধান অঙ্গীকার।</p>
	                    
	                    <div class="alert alert-info border-primary border-3 border-start py-3 mt-4">
	                        <h4 class="alert-heading text-primary">কারা ব্যবহার করবেন?</h4>
	                        <p class="mb-0">এই অ্যাপটি বিশেষভাবে ডিজাইন করা হয়েছে যারা **বাংলাদেশ সিভিল সার্ভিস (BCS)**, **ব্যাংক রিক্রুটমেন্ট**, **প্রাথমিক শিক্ষক নিয়োগ**, **NSI, দুদক** সহ অন্যান্য সকল সরকারি চাকরির পরীক্ষার প্রস্তুতি নিচ্ছেন।</p>
	                    </div>
	                </section>

	                <hr class="my-5">

	                <!-- 2. Core Features -->
	                <section id="features" class="mb-5">
	                    <h2 class="pb-2 border-bottom text-primary mb-4 fw-bold">২. প্ল্যাটফর্মের মূল বৈশিষ্ট্যসমূহ</h2>
	                    <div class="row g-4">
	                        
	                        <!-- Feature 1: Full Course -->
	                        <div class="col-md-6">
	                            <div id="comprehensive" class="card p-4 h-100 feature-card shadow-sm">
	                                <div class="icon-circle">
	                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open-check"><path d="M8 2h4c3.3 0 6 2.7 6 6v14a2 2 0 0 1-2 2h-6c-3.3 0-6-2.7-6-6V6a2 2 0 0 1 2-2h2"/><path d="M10 2c3.3 0 6 2.7 6 6"/><path d="M12 2c3.3 0 6 2.7 6 6"/><path d="m14 11 2 2 4-4"/></svg>
	                                </div>
	                                <h5 class="fw-bold text-dark">সম্পূর্ণ সিলেবাস শেষ করার ব্যবস্থা</h5>
	                                <p class="text-secondary mb-0">পূর্ণাঙ্গ কোর্স মডিউলের মাধ্যমে পুরো বিসিএস পরীক্ষার সিলেবাস শুরু থেকে শেষ পর্যন্ত ধাপে ধাপে সম্পন্ন করার ব্যবস্থা রয়েছে।</p>
	                            </div>
	                        </div>

	                        <!-- Feature 2: Model Tests -->
	                        <div class="col-md-6">
	                            <div id="model-test" class="card p-4 h-100 feature-card shadow-sm">
	                                <div class="icon-circle">
	                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-check"><rect width="8" height="4" x="8" y="2"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
	                                </div>
	                                <h5 class="fw-bold text-dark">প্রতিযোগিতামূলক মডেল টেস্ট</h5>
	                                <p class="text-secondary mb-0">বিসিএস, ব্যাংক রিক্রুটমেন্ট, প্রাথমিক শিক্ষক নিয়োগ সহ NSI ও দুদক পরীক্ষার জন্য নিয়মিত ও ফ্রি মডেল টেস্ট দেওয়ার ব্যবস্থা।</p>
	                            </div>
	                        </div>
	                        
	                        <!-- Feature 3: Question Bank -->
	                        <div class="col-md-6">
	                            <div id="question-bank" class="card p-4 h-100 feature-card shadow-sm">
	                                <div class="icon-circle">
	                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-database"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14a9 3 0 0 0 18 0V5"/><path d="M3 12h18"/><path d="M3 19h18"/></svg>
	                                </div>
	                                <h5 class="fw-bold text-dark">সকল পরীক্ষার প্রশ্নব্যাংক</h5>
	                                <p class="text-secondary mb-0">বিসিএস, ব্যাংক, শিক্ষক নিয়োগ সহ সকল বিগত বছরের পরীক্ষার প্রশ্নব্যাংক থেকে সরাসরি অনুশীলন ও মডেল টেস্ট দেওয়ার সুযোগ।</p>
	                            </div>
	                        </div>

	                        <!-- Feature 4: Subject Discussion -->
	                        <div class="col-md-6">
	                            <div class="card p-4 h-100 feature-card shadow-sm">
	                                <div class="icon-circle">
	                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap"><path d="M21.42 10.96V18a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7.04"/><path d="M22 6c0 1-9 5-10 5s-10-4-10-5 10-5 10-5 10 4 10 5z"/><path d="M3.24 10.64l8.76 4.38 8.76-4.38"/><path d="M12 15v8"/></svg>
	                                </div>
	                                <h5 class="fw-bold text-dark">বিষয়ভিত্তিক আলোচনা ও অনুশীলন</h5>
	                                <p class="text-secondary mb-0">প্রতিটি বিষয়ের দুর্বলতা কাটাতে গভীর আলোচনা এবং অনুশীলনের জন্য বিশেষ মডিউল। ফ্রি মডেল টেস্ট-এর ব্যবস্থাও এখানে রয়েছে।</p>
	                            </div>
	                        </div>

	                    </div>
	                </section>

	                <hr class="my-5">

	                <!-- 3. Study Materials Section -->
	                <section id="materials" class="mb-5 section-anchor">
	                    <h2 class="pb-2 border-bottom text-primary mb-4 fw-bold">৩. বিশেষ লেকচার ম্যাটেরিয়াল</h2>
	                    <p class="lead text-secondary">সেরা ফলপ্রসূ প্রস্তুতির জন্য শীর্ষ ক্যাডারদের তৈরি লেকচার ম্যাটেরিয়াল (Lecture Material) সরবরাহ করা হয়। এই উপকরণগুলো আপনার প্রস্তুতিকে ভিন্ন মাত্রা দেবে।</p>
	                    
	                    <div class="row g-4 mt-4">
	                        <div class="col-md-4">
	                            <div class="card bg-light p-3 text-center h-100 shadow-sm">
	                                <h3 class="text-danger">ভিডিও ক্লাস</h3>
	                                <p class="text-dark mb-0">কঠিন টপিকগুলো সহজে বোঝার জন্য উচ্চ মানের ভিডিও ক্লাস।</p>
	                            </div>
	                        </div>
	                        <div class="col-md-4">
	                            <div class="card bg-light p-3 text-center h-100 shadow-sm">
	                                <h3 class="text-success">অডিও ক্লাস</h3>
	                                <p class="text-dark mb-0">যাতায়াতের সময় বা কাজের ফাঁকে শোনার জন্য বিশেষ অডিও ক্লাস।</p>
	                            </div>
	                        </div>
	                        <div class="col-md-4">
	                            <div class="card bg-light p-3 text-center h-100 shadow-sm">
	                                <h3 class="text-info">পিডিএফ শিট</h3>
	                                <p class="text-dark mb-0">অফলাইনে পড়ার জন্য প্রতিটি লেকচারের পিডিএফ (PDF) শিট ডাউনলোড করার ব্যবস্থা।</p>
	                            </div>
	                        </div>
	                    </div>
	                </section>
	                
	                <hr class="my-5">

	                <!-- Conclusion -->
	                <section class="mb-5">
	                    <h2 class="pb-2 text-primary mb-4 fw-bold">ব্যবহার নিশ্চিত করুন</h2>
	                    <p class="fs-5 text-dark">আমাদের এন্ড্রয়েড অ্যাপটি ব্যবহার করে দেশের যেকোন প্রান্তে বসে প্রতিযোগিতামূলক বিসিএস ও অন্যান্য সরকারি চাকরি পরীক্ষার প্রস্তুতি নিতে পারবেন খুব সহজেই। অ্যাপটির সর্বোচ্চ ব্যবহার নিশ্চিত করুন এবং নিয়োগ পরীক্ষায় আত্মবিশ্বাসী হয়ে যান!</p>
	                    <a href="https://play.google.com/store/apps/details?id=com.orbachinujbuk.bcs" class="btn btn-primary btn-lg mt-3 shadow-lg">অ্যাপটি ডাউনলোড করুন!</a>
	                </section>

	            </div>
	        </div>
	    </div>

    
</section>
@endsection

@section('third_party_scripts')

@endsection