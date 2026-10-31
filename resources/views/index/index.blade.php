@extends('layouts.index')
@section('title') BCS Exam Aid - বিসিএস ও সরকারি চাকরির সেরা প্ল্যাটফর্ম@endsection

@section('third_party_stylesheets')
<style type="text/css">
  .pricing-style-fourteen .currency {
      font-weight: 200;
      color: var(--dark-3);
      font-size: 22px;
      position: absolute;
      left: -40px;
      top: 6px; 
  }
  .pricing-style-fourteen .amount {
      font-size: 45px;
  }
  .pricing-style-fourteen .duration {
      font-size: 16px;
  }
</style>
@endsection

@section('content')
    <div class="modal fade" id="offerModalMain" data-bs-backdrop="static">
      <div class="modal-dialog modal-lg modal-success">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">NSI নিয়োগের প্রস্তুতি এখানেই!</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <!-- Modal body -->
          <div class="modal-body">
            <b>BCS Exam Aid</b> অ্যাপ এ <b>NSI নিয়োগ</b>-এর ৪৫টি পরীক্ষার কোর্স করুন। অ্যাপ্টি ইন্সটল করতে নিচের গুগল প্লে-স্টোর বাটনে ক্লিক করুন!

            <div class="button">
              {{-- <a href="javascript:void(0)" class="btn primary-btn">Get Started</a> --}}
              <a href='https://play.google.com/store/apps/details?id=com.orbachinujbuk.bcs' target="_blank"><img alt='Get it on Google Play' class="img-responsive" style="max-width: 250px; width: auto;" src='https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png'/></a>
            </div>
            <a href="{{ route('blog.single', 'nsi-job-circular-2025-faq') }}" title="NSI নিয়োগ ২০২৫: পরীক্ষার ধাপ, প্রস্তুতি ও গুরুত্বপূর্ণ তথ্য">NSI নিয়োগ ২০২৫: পরীক্ষার ধাপ, প্রস্তুতি ও গুরুত্বপূর্ণ তথ্য</a>
          </div>
          <!-- Modal footer -->
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">বন্ধ করুন</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Start header Area -->
    <section id="hero-area" class="header-area header-eight">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 col-md-12 col-12">
            <div class="header-content">
              <h1 style="padding-top: 7px;">বিসিএস-সহ সরকারি চাকরি পরীক্ষার প্রস্তুতির সেরা অনলাইন প্ল্যাটফর্ম!</h1>
              <p>
                <b>BCS EXAM AID</b> বিসিএস, ব্যাংক, প্রাইমারি, NTRCA, NSI, DGFI, দুদক সহ সকল সরকারি চাকরির পূর্ণাঙ্গ প্রস্তুতির সেরা অ্যাপ। ১,০০,০০০+ প্রশ্নব্যাংক, ২২০০+ সাব-টপিক এবং স্পেশাল মডেল টেস্টের মাধ্যমে ঘরে বসেই আপনার প্রস্তুতিকে করুন আরও নির্ভুল ও আত্মবিশ্বাসী।
              </p>
              <div class="button">
                {{-- <a href="javascript:void(0)" class="btn primary-btn">Get Started</a> --}}
                <a href='https://play.google.com/store/apps/details?id=com.orbachinujbuk.bcs' target="_blank"><img alt='Get it on Google Play' class="img-responsive" style="max-width: 250px; width: auto;" src='https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png'/></a>
                <a href="https://www.youtube.com/watch?v=S1K1XRFOeNs"
                  class="glightbox video-button">
                  <span class="btn icon-btn rounded-full">
                    <i class="lni lni-play"></i>
                  </span>
                  <span class="text">ভিডিও প্রোমো</span>
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-md-12 col-12">
            <div class="header-image">
              <img src="{{ asset('/images/header/header-image-device-screenshots.png') }}" alt="Header image: Device Screenshots" />
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End header Area -->

    <!--====== ABOUT FIVE PART START ======-->

    <section class="about-area about-five">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 col-12">
            <div class="about-image-five">
              <svg class="shape" width="106" height="134" viewBox="0 0 106 134" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <circle cx="1.66654" cy="1.66679" r="1.66667" fill="#DADADA" />
                <circle cx="1.66654" cy="16.3335" r="1.66667" fill="#DADADA" />
                <circle cx="1.66654" cy="31.0001" r="1.66667" fill="#DADADA" />
                <circle cx="1.66654" cy="45.6668" r="1.66667" fill="#DADADA" />
                <circle cx="1.66654" cy="60.3335" r="1.66667" fill="#DADADA" />
                <circle cx="1.66654" cy="88.6668" r="1.66667" fill="#DADADA" />
                <circle cx="1.66654" cy="117.667" r="1.66667" fill="#DADADA" />
                <circle cx="1.66654" cy="74.6668" r="1.66667" fill="#DADADA" />
                <circle cx="1.66654" cy="103" r="1.66667" fill="#DADADA" />
                <circle cx="1.66654" cy="132" r="1.66667" fill="#DADADA" />
                <circle cx="16.3333" cy="1.66679" r="1.66667" fill="#DADADA" />
                <circle cx="16.3333" cy="16.3335" r="1.66667" fill="#DADADA" />
                <circle cx="16.3333" cy="31.0001" r="1.66667" fill="#DADADA" />
                <circle cx="16.3333" cy="45.6668" r="1.66667" fill="#DADADA" />
                <circle cx="16.333" cy="60.3335" r="1.66667" fill="#DADADA" />
                <circle cx="16.333" cy="88.6668" r="1.66667" fill="#DADADA" />
                <circle cx="16.333" cy="117.667" r="1.66667" fill="#DADADA" />
                <circle cx="16.333" cy="74.6668" r="1.66667" fill="#DADADA" />
                <circle cx="16.333" cy="103" r="1.66667" fill="#DADADA" />
                <circle cx="16.333" cy="132" r="1.66667" fill="#DADADA" />
                <circle cx="30.9998" cy="1.66679" r="1.66667" fill="#DADADA" />
                <circle cx="74.6665" cy="1.66679" r="1.66667" fill="#DADADA" />
                <circle cx="30.9998" cy="16.3335" r="1.66667" fill="#DADADA" />
                <circle cx="74.6665" cy="16.3335" r="1.66667" fill="#DADADA" />
                <circle cx="30.9998" cy="31.0001" r="1.66667" fill="#DADADA" />
                <circle cx="74.6665" cy="31.0001" r="1.66667" fill="#DADADA" />
                <circle cx="30.9998" cy="45.6668" r="1.66667" fill="#DADADA" />
                <circle cx="74.6665" cy="45.6668" r="1.66667" fill="#DADADA" />
                <circle cx="31" cy="60.3335" r="1.66667" fill="#DADADA" />
                <circle cx="74.6668" cy="60.3335" r="1.66667" fill="#DADADA" />
                <circle cx="31" cy="88.6668" r="1.66667" fill="#DADADA" />
                <circle cx="74.6668" cy="88.6668" r="1.66667" fill="#DADADA" />
                <circle cx="31" cy="117.667" r="1.66667" fill="#DADADA" />
                <circle cx="74.6668" cy="117.667" r="1.66667" fill="#DADADA" />
                <circle cx="31" cy="74.6668" r="1.66667" fill="#DADADA" />
                <circle cx="74.6668" cy="74.6668" r="1.66667" fill="#DADADA" />
                <circle cx="31" cy="103" r="1.66667" fill="#DADADA" />
                <circle cx="74.6668" cy="103" r="1.66667" fill="#DADADA" />
                <circle cx="31" cy="132" r="1.66667" fill="#DADADA" />
                <circle cx="74.6668" cy="132" r="1.66667" fill="#DADADA" />
                <circle cx="45.6665" cy="1.66679" r="1.66667" fill="#DADADA" />
                <circle cx="89.3333" cy="1.66679" r="1.66667" fill="#DADADA" />
                <circle cx="45.6665" cy="16.3335" r="1.66667" fill="#DADADA" />
                <circle cx="89.3333" cy="16.3335" r="1.66667" fill="#DADADA" />
                <circle cx="45.6665" cy="31.0001" r="1.66667" fill="#DADADA" />
                <circle cx="89.3333" cy="31.0001" r="1.66667" fill="#DADADA" />
                <circle cx="45.6665" cy="45.6668" r="1.66667" fill="#DADADA" />
                <circle cx="89.3333" cy="45.6668" r="1.66667" fill="#DADADA" />
                <circle cx="45.6665" cy="60.3335" r="1.66667" fill="#DADADA" />
                <circle cx="89.3333" cy="60.3335" r="1.66667" fill="#DADADA" />
                <circle cx="45.6665" cy="88.6668" r="1.66667" fill="#DADADA" />
                <circle cx="89.3333" cy="88.6668" r="1.66667" fill="#DADADA" />
                <circle cx="45.6665" cy="117.667" r="1.66667" fill="#DADADA" />
                <circle cx="89.3333" cy="117.667" r="1.66667" fill="#DADADA" />
                <circle cx="45.6665" cy="74.6668" r="1.66667" fill="#DADADA" />
                <circle cx="89.3333" cy="74.6668" r="1.66667" fill="#DADADA" />
                <circle cx="45.6665" cy="103" r="1.66667" fill="#DADADA" />
                <circle cx="89.3333" cy="103" r="1.66667" fill="#DADADA" />
                <circle cx="45.6665" cy="132" r="1.66667" fill="#DADADA" />
                <circle cx="89.3333" cy="132" r="1.66667" fill="#DADADA" />
                <circle cx="60.3333" cy="1.66679" r="1.66667" fill="#DADADA" />
                <circle cx="104" cy="1.66679" r="1.66667" fill="#DADADA" />
                <circle cx="60.3333" cy="16.3335" r="1.66667" fill="#DADADA" />
                <circle cx="104" cy="16.3335" r="1.66667" fill="#DADADA" />
                <circle cx="60.3333" cy="31.0001" r="1.66667" fill="#DADADA" />
                <circle cx="104" cy="31.0001" r="1.66667" fill="#DADADA" />
                <circle cx="60.3333" cy="45.6668" r="1.66667" fill="#DADADA" />
                <circle cx="104" cy="45.6668" r="1.66667" fill="#DADADA" />
                <circle cx="60.333" cy="60.3335" r="1.66667" fill="#DADADA" />
                <circle cx="104" cy="60.3335" r="1.66667" fill="#DADADA" />
                <circle cx="60.333" cy="88.6668" r="1.66667" fill="#DADADA" />
                <circle cx="104" cy="88.6668" r="1.66667" fill="#DADADA" />
                <circle cx="60.333" cy="117.667" r="1.66667" fill="#DADADA" />
                <circle cx="104" cy="117.667" r="1.66667" fill="#DADADA" />
                <circle cx="60.333" cy="74.6668" r="1.66667" fill="#DADADA" />
                <circle cx="104" cy="74.6668" r="1.66667" fill="#DADADA" />
                <circle cx="60.333" cy="103" r="1.66667" fill="#DADADA" />
                <circle cx="104" cy="103" r="1.66667" fill="#DADADA" />
                <circle cx="60.333" cy="132" r="1.66667" fill="#DADADA" />
                <circle cx="104" cy="132" r="1.66667" fill="#DADADA" />
              </svg>
              <img src="{{ asset('/') }}images/about/promotional-poster.jpg" alt="about" />
            </div>
          </div>
          <div class="col-lg-6 col-12">
            <div class="about-five-content">
              <h6 class="small-title text-lg">আমাদের সম্পর্কে</h6>
              <h2 class="main-title fw-bold">অভিজ্ঞতা ও প্রয়োজনীয়তা বিশ্লেষণ করে সমাধান প্রণয়নকারী</h2>
              <div class="about-five-tab">
                <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-who-tab" data-bs-toggle="tab" data-bs-target="#nav-who"
                      type="button" role="tab" aria-controls="nav-who" aria-selected="true">আমরা কারা?</button>
                    <button class="nav-link" id="nav-vision-tab" data-bs-toggle="tab" data-bs-target="#nav-vision"
                      type="button" role="tab" aria-controls="nav-vision" aria-selected="false">আমাদের লক্ষ্য</button>
                    {{-- <button class="nav-link" id="nav-history-tab" data-bs-toggle="tab" data-bs-target="#nav-history"
                      type="button" role="tab" aria-controls="nav-history" aria-selected="false">our History</button> --}}
                  </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="nav-who" role="tabpanel" aria-labelledby="nav-who-tab">
                    <p>আমরা কয়েকজন বাংলাদেশ সিভিল সার্ভিসের প্রশাসন, পুলিশসহ শীর্ষ ক্যাডারের সদস্য এবং বিসিএস ও অন্যান্য সরকারি চাকরির পরীক্ষার এক্সপার্টদের সমন্বয়ে গঠিত টিম। বিগত বছরগুলোর প্রশ্ন বিশ্লেষণ, প্রশ্নের প্যাটার্ন অনুসন্ধান  ও সর্বোচ্চ স্ট্র্যাটেজি অনুধাবন করে আমরা বিসিএস ও অন্যান্য সরকারি চাকরির পরীক্ষার্থীদের জন্য সর্বোত্তম সমাধানটি নিয়ে এসেছি।</p>
                    <p>আমাদের এন্ড্রয়েড অ্যাপটি ব্যবহার করে দেশের যেকোন প্রান্তে বসে প্রতিযোগিতামূলক বিসিএস-সহ যেকোন সরকারি চাকরির পরীক্ষার প্রস্তুতি নিতে পারবেন খুব সহজেই! </p>
                  </div>
                  <div class="tab-pane fade" id="nav-vision" role="tabpanel" aria-labelledby="nav-vision-tab">
                    <p>গতানুগতিক অন্যান্য চাকুরির (যেমন বিসিএস) প্রস্তুতির জন্য প্রচুর রিসোর্স অনলাইনেই পাওয়া যায়। কিন্তু সেগুলো থেকে চাকরির সর্বোচ্চ প্রস্তুতি কতটুকু হলো তা বোঝার জন্য হাজার হাজার টাকা খরচ করে কোচিং-এ যেতে হয়!</p>
                    <p>কর্মব্যস্ত চাকরিজীবী থেকে শুরু করে বিশ্ববিদ্যালয়ের হলে বসে প্রস্তুতি নিতে থাকা চাকরি প্রার্থী যেন সরকারি চাকরির পরীক্ষার প্রস্তুতি ঘরে বসেই নিতে পারেন, তাদের দোর গোড়ায় এ সেবাটি পৌঁছে দেওয়াই আমাদের লক্ষ্য।</p>
                  </div>
                  {{-- <div class="tab-pane fade" id="nav-history" role="tabpanel" aria-labelledby="nav-history-tab">
                    <p>It is a long established fact that a reader will be distracted by the readable content of a page
                      when
                      looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal
                      distribution of letters, look like readable English.</p>
                    <p>There are many variations of passages of Lorem Ipsum available, but the majority have in some
                      form,
                      by injected humour.</p>
                  </div> --}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- container -->
    </section>

    <!--====== ABOUT FIVE PART ENDS ======-->

    <!-- ===== service-area start ===== -->
    <section id="services" class="services-area services-eight">
      <!--======  Start Section Title Five ======-->
      <div class="section-title-five">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="content">
                <h6>সেবাসমূহ</h6>
                <h2 class="fw-bold">আমাদের অ্যাপের সেবাসমূহ</h2>
                <p>
                  বিসিএস ও অন্যান্য সরকারি চাকরির পরীক্ষার প্রস্তুতি নিতে অ্যাপটির সর্বোচ্চ ব্যবহার নিশ্চিত করুন এবং নিয়োগ পরীক্ষায় আত্মবিশ্বাসী হয়ে যান!
                </p>
              </div>
            </div>
          </div>
          <!-- row -->
        </div>
        <!-- container -->
      </div>
      <!--======  End Section Title Five ======-->
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <div class="single-services">
              <div class="service-icon">
                <i class="lni lni-capsule"></i>
              </div>
              <div class="service-content">
                <h4>বিসিএস প্রিলিমিনারি প্রস্তুতি</h4>
                <p>
                  ৫০ ও ৫১তম বিসিএস স্পেশাল: নির্ভুল প্রিলিমিনারি মডেল টেস্ট
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="single-services">
              <div class="service-icon">
                <i class="lni lni-bootstrap"></i>
              </div>
              <div class="service-content">
                <h4>অন্যান্য সরকারি চাকরি প্রস্তুতি</h4>
                <p>
                  ব্যাংক, প্রাইমারি, NTRCA, NSI, DGFI: সকল সরকারি চাকরির পূর্ণাঙ্গ সমাধান
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="single-services">
              <div class="service-icon">
                <i class="lni lni-shortcode"></i>
              </div>
              <div class="service-content">
                <h4>২২০০+ সাব-টপিক</h4>
                <p>
                  ২২০০+ সাব-টপিক: অধ্যায়ভিত্তিক প্রস্তুতির মাধ্যমে দুর্বলতা দূর করুন
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="single-services">
              <div class="service-icon">
                <i class="lni lni-dashboard"></i>
              </div>
              <div class="service-content">
                <h4>টার্গেট ভিত্তিক স্পেশাল কোর্স</h4>
                <p>
                  বিসিএস ক্র্যাশ কোর্স: নির্দিষ্ট সময়ে সিলেবাস শেষ করার স্মার্ট পরিকল্পনা
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="single-services">
              <div class="service-icon">
                <i class="lni lni-layers"></i>
              </div>
              <div class="service-content">
                <h4>সম্পূর্ণ এক্সেস</h4>
                <p>
                  প্রিমিয়াম এক্সেস: ১,০০,০০০+ প্রশ্ন ও ক্যাডারদের লেকচার শিট এক জায়গায়
                </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="single-services">
              <div class="service-icon">
                <i class="lni lni-reload"></i>
              </div>
              <div class="service-content">
                <h4>মেগা প্রশ্নব্যাংক</h4>
                <p>
                  ব্যাখ্যাসহ বিসিএস সহ সকল চাকরি পরীক্ষার প্রশ্নব্যাংক ও লাইভ জব সল্যুশন
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ===== service-area end ===== -->


    <!-- Start Pricing  Area -->
    <section id="pricing" class="pricing-area pricing-fourteen">
      <!--======  Start Section Title Five ======-->
      <div class="section-title-five">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="content">
                <h6>Pricing</h6>
                <h2 class="fw-bold">Pricing & Plans</h2>
                <p>
                  সব থেকে কম মূল্যে আমাদের প্যাকেজগুলো কিনে আপনি আপনার বিসিএস ও অন্যান্য সরকারি চাকরির পরীক্ষার প্রস্তুতিকে আরও শাণিত করুন।
                </p>
              </div>
            </div>
          </div>
          <!-- row -->
        </div>
        <!-- container -->
      </div>
      <!--======  End Section Title Five ======-->
      <div class="container">
        <div class="row">
          @foreach($packages as $package)
            <div class="col-lg-3 col-md-6 col-12">
              <div class="pricing-style-fourteen @if($package->suggested == 1) middle @endif">
                <div class="table-head">
                  <h6 class="title">{{ $package->name }}</h6>
                    <p>{{ $package->tagline }}</p>
                    <div class="price">
                      <h2 class="amount">
                        <span class="currency"><strike>৳ {{ bangla($package->strike_price) }}</strike></span> ৳ {{ bangla($package->price) }}<span class="duration">/{{ $package->duration }}</span>
                      </h2>
                    </div>
                </div>

                <div class="light-rounded-buttons">
                  <button type="button" data-bs-toggle="modal" data-bs-target="#packageModal{{ $package->id }}" class="btn @if($package->suggested == 1) primary-btn @else primary-btn-outline @endif">
                    শুরু করুন!
                  </button>
                </div>

                <div class="table-content">
                  <ul class="table-list">
                    <li> <i class="lni lni-checkmark-circle"></i> অ্যাপের ফিচারসমূহের এক্সেস</li>
                    <li> <i class="lni lni-checkmark-circle"></i> সম্পূর্ণ বিসিএস কোর্স এক্সেস</li>
                    <li> <i class="lni lni-checkmark-circle"></i> অন্যান্য চাকরির কোর্স এক্সেস</li>
                    <li> <i class="lni lni-checkmark-circle"></i> মডেল টেস্ট ও সাবজেক্টিভ প্রস্তুতি</li>
                    {{-- <li> <i class="lni lni-checkmark-circle deactive"></i> মডেল টেস্ট ও সাবজেক্টিভ প্রস্তুতি</li> --}}
                  </ul>
                </div>
              </div>
            </div>
            <div class="modal fade" id="packageModal{{ $package->id }}" data-bs-backdrop="static">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">

                  <!-- Modal Header -->
                  <div class="modal-header">
                    <h4 class="modal-title">{{ $package->name }} প্ল্যান সাবস্ক্রাইব করুন</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>

                  <form method="post" action="{{ route('index.payment.proceed') }}">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                      <b>প্যাকেজঃ</b> {{ $package->name }} ({{ $package->tagline }})<br/>
                      <b>প্যাকেজের মেয়াদঃ</b> {{ $package->duration }}<br/><br/>
                      <big><b>প্যাকেজের মূল্যঃ</b> <small><strike>৳ {{ bangla($package->strike_price) }}</strike></small> ৳ {{ bangla($package->price) }}</big><br/><br/>
                      <b>ফিচারসমূহঃ</b>
                      <div class="table-content">
                        <ul class="table-list">
                          <li> <i class="lni lni-checkmark-circle"></i> অ্যাপের ফিচারসমূহের এক্সেস</li>
                          <li> <i class="lni lni-checkmark-circle"></i> সম্পূর্ণ বিসিএস কোর্স এক্সেস</li>
                          <li> <i class="lni lni-checkmark-circle"></i> অন্যান্য চাকরির কোর্স এক্সেস</li>
                          <li> <i class="lni lni-checkmark-circle"></i> মডেল টেস্ট ও সাবজেক্টিভ প্রস্তুতি</li>
                          {{-- <li> <i class="lni lni-checkmark-circle deactive"></i> মডেল টেস্ট ও সাবজেক্টিভ প্রস্তুতি</li> --}}
                        </ul>
                      </div><br/>
                      <label>
                        অ্যাপে ব্যবহৃত ১১ ডিজিটের মোবাইল নম্বরটি লিখুন<br/>
                        @if(Auth::guest())
                          <span style="color: green; font-size: 10px;">রেজিস্ট্রেশন না করে থাকলে <a href="#!">ক্লিক করুন</a></span> 
                        @endif
                      </label>
                      <input type="number" name="user_number" onkeypress="if(this.value.length==11) return false;" class="form-control" placeholder="অ্যাপে ব্যবহৃত মোবাইল নাম্বারটি লিখুন" @if(!Auth::guest()) value="{{ Auth::user()->mobile }}" @endif required><br/>

                      <small>
                        <a href="{{ route('index.terms-and-conditions') }}" target="_blank">Terms & Conditions</a>, <a href="{{ route('index.privacy-policy') }}" target="_blank">Privacy Policy</a> & <a href="{{ route('index.refund-policy') }}" target="_blank">Refund Policy</a>
                      </small>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ফিরে যান</button> --}}
                        <input type="hidden" name="amount" value="{{ $package->price }}" required>
                        <input type="hidden" name="package_id" value="{{ $package->id }}" required>
                        <button type="submit" class="btn primary-btn-outline">৳ {{ bangla($package->price) }} পরিশোধ করুন</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <script type="application/ld+json">
              {
                "@context": "https://schema.org/",
                "@type": "Product",
                "name": "{{ $package->name }}",
                "image": "https://bcsexamaid.com/images/packages/{{ $package->image }}",
                "description": "{{ $package->tagline }}",
                "offers": {
                  "@type": "Offer",
                  "price": "{{ $package->price }}",
                  "priceCurrency": "BDT",
                  "priceSpecification": {
                    "@type": "UnitPriceSpecification",
                    "priceType": "https://schema.org/StrikethroughPrice",
                    "price": "{{ $package->strike_price }}",
                    "priceCurrency": "BDT"
                  }
                },
                "brand": {
                  "@type": "Brand",
                  "name": "BCS Exam Aid"
                },
                "review": {
                  "@type": "Review",
                  "reviewRating": {
                    "@type": "Rating",
                    "ratingValue": 4.5,
                    "bestRating": 5
                  },
                  "author": {
                    "@type": "Person",
                    "name": "Fred Benson"
                  }
                },
                "aggregateRating": {
                  "@type": "AggregateRating",
                  "ratingValue": 4.5,
                  "reviewCount": 110
                }
              }
            </script>
          @endforeach
        </div>
      </div>
    </section>
    <!--/ End Pricing  Area -->

    <section id="blog" class="pricing-area pricing-fourteen">
      <div class="section-title-five">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="content">
                <h6>Latest Updates</h6>
                <h2 class="fw-bold">আমাদের সাম্প্রতিক ব্লগ পোস্ট</h2>
                <p>
                  আমাদের ব্লগে বিসিএস এবং অন্যান্য সরকারি চাকরির পরীক্ষার প্রস্তুতির জন্য গুরুত্বপূর্ণ তথ্য ও টিপস পেতে পারেন।
                </p>
              </div>
            </div>
          </div>
          </div>
        </div>
      <div class="container">
        <div class="row">
          @foreach($blogs as $blog)
            <div class="col-lg-4 col-md-6 col-12">
                <div class="blogs-style-fourteen"> {{-- Reusing pricing box style for consistent look --}}
                  <a class="blog-image" href="{{ route('blog.single', $blog->slug) }}">
                      <img src="{{ asset('images/blogs/'.$blog->featured_image) }}" alt="{{ $blog->title }}" class="img-fluid blog-image-full-width">
                  </a>
                    <div class="table-head" style="padding: 0;">
                        @if($blog->featured_image != null)
                            
                        @endif
                        <h6 class="title" style="font-size: 20px; text-align: left; padding: 20px 20px 0px 20px;"> <a href="{{ route('blog.single', $blog->slug) }}" style="color: inherit; text-decoration: none;">{{ $blog->title }}</a>
                        </h6>
                    </div>
                    <div class="table-content" style="padding-top: 10px;">
                        <p style="padding: 0px 20px;">
                          @if($blog->description != '')
                            {{ $blog->description }}
                          @else
                            @if(strlen(strip_tags($blog->body))>200)
                                {{ mb_substr(strip_tags($blog->body), 0, stripos($blog->body, " ", stripos(strip_tags($blog->body), " ")+200))."... " }}
                            @else
                                {{ strip_tags($blog->body) }}
                            @endif
                          @endif
                        </p>
                    </div>
                    <div class="light-rounded-buttons" style="margin-top: 20px; margin-bottom: 40px;">
                      <center><a href="{{ route('blog.single', $blog->slug) }}" class="btn primary-btn-outline">বিস্তারিত পড়ুন</a></center>
                    </div>
                </div>
            </div>
            <script type="application/ld+json">
                {
                "@context": "https://schema.org",
                "@type": "Article",
                "headline": "{{ $blog->title }}",
                "description": "{{ $blog->description ?? mb_substr(strip_tags($blog->body), 0, 200) }}",
                "image": "{{ asset('images/blogs/'.$blog->featured_image) ?? asset('images/bcs-exam-aid-banner.png') }}",
                "url": "{{ url('/') . '/blog/' . $blog->slug }}",
                "author": {
                  "@type": "Person",
                  "name": "{{ $blog->user->name ?? 'এ. এইচ. এম. আজিমুল হক' }}"
                },
                "datePublished": "{{ $blog->created_at ?? now()->toIso8601String() }}",
                "dateModified": "{{ $blog->updated_at ?? now()->toIso8601String() }}"
                }
            </script>
          @endforeach
        </div>
        <style>
          .blogs-style-fourteen {
              border: 1px solid var(--light-1);
              border-radius: 10px;
              margin-top: 30px;
              background-color: var(--white);
              transition: all 0.4s ease;
              /* padding: 50px 35px; */
              /* text-align: center; */
              z-index: 0;
              min-height: 530px;
          }
          .blogs-style-fourteen:hover {
            box-shadow: var(--shadow-4);
          }
          .blogs-style-fourteen .blog-image img {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
          }
        </style>

        <div class="row">
          <div class="col-12 text-center mt-5">
            <a href="{{ route('blogs.index') }}" class="btn primary-btn">সকল ব্লগ দেখুন</a>
          </div>
        </div>
      </div>
    </section>

    <section id="tutorials" class="services-area services-eight" style="background-color: #ffffff;">
      <style>
        .video-card-style {
          background: #fff;
          border-radius: 15px;
          overflow: hidden;
          box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
          transition: all 0.3s ease;
          border: 1px solid #eee;
          padding: 10px;
        }

        .video-card-style:hover {
          transform: translateY(-10px);
          box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        /* 9:16 Aspect Ratio for Shorts */
        .video-container {
          position: relative;
          padding-bottom: 177.77%; /* (16/9)*100 */
          height: 0;
          overflow: hidden;
          border-radius: 10px;
          background: #000;
        }

        .video-container iframe {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
        }

        .border-primary-highlight {
          border: 2px solid var(--primary); /* আপনার সাইটের প্রাইমারি কালার */
        }
      </style>
      <div class="section-title-five">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="content">
                <h6>ভিডিও গাইড</h6>
                <h2 class="fw-bold">টিউটোরিয়াল ও টিপস</h2>
                <p>
                  আমাদের অ্যাপের ব্যবহার এবং বিসিএস প্রস্তুতির বিশেষ কৌশলগুলো দেখে নিন এই শর্ট ভিডিওগুলোর মাধ্যমে।
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container">
        <div class="row g-4 justify-content-center">
          <div class="col-lg-3 col-md-6 col-sm-10">
            <div class="video-card-style">
              <div class="video-container">
                <iframe src="https://www.youtube.com/embed/6boe3Ui0LKY" title="BCS Exam Aid Tutorial 1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 col-sm-10">
            <div class="video-card-style">
              <div class="video-container">
                <iframe src="https://www.youtube.com/embed/IZXtGjRSrys" title="BCS Exam Aid Tutorial 2" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 col-sm-10">
            <div class="video-card-style">
              <div class="video-container">
                <iframe src="https://www.youtube.com/embed/tmqSxbAQKFM" title="BCS Exam Aid Tutorial 3" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 col-sm-10">
            <div class="video-card-style">
              <div class="video-container">
                <iframe src="https://www.youtube.com/embed/ezrSC30VuKg" title="BCS Exam Aid Tutorial 4" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-md-6 col-sm-10 mt-4">
            <div class="video-card-style border-primary-highlight">
              <div class="video-container">
                <iframe src="https://www.youtube.com/embed/GAy-0gd1_Jw" title="BCS Exam Aid Tutorial 5" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"></iframe>
              </div>
            </div>
          </div>
        </div>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "ItemList",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "item": {
                "@type": "VideoObject",
                "name": "BCS Exam Aid: চলমান কোর্সসমূহ",
                "description": "বিসিএস প্রিলিমিনারি ও অন্যান্য সরকারি নিয়োগ পরীক্ষার জন্য বর্তমানে অ্যাপে চলমান সকল স্পেশাল কোর্স এবং প্রিমিয়াম ফিচার সম্পর্কে বিস্তারিত জানুন।",
                "thumbnailUrl": "https://img.youtube.com/vi/6boe3Ui0LKY/maxresdefault.jpg",
                "uploadDate": "2026-04-24T10:00:00+06:00",
                "embedUrl": "https://www.youtube.com/embed/6boe3Ui0LKY"
              }
            },
            {
              "@type": "ListItem",
              "position": 2,
              "item": {
                "@type": "VideoObject",
                "name": "BCS Exam Aid: প্রশ্নব্যাংক বিস্তারিত",
                "description": "বিগত বছরের সকল বিসিএস ও সরকারি চাকরির নিয়োগ পরীক্ষার প্রশ্ন এবং সেগুলোর নির্ভুল ও বিস্তারিত ব্যাখ্যা সম্বলিত প্রশ্নব্যাংক ব্যবহারের গাইড।",
                "thumbnailUrl": "https://img.youtube.com/vi/IZXtGjRSrys/maxresdefault.jpg",
                "uploadDate": "2026-04-23T10:00:00+06:00",
                "embedUrl": "https://www.youtube.com/embed/IZXtGjRSrys"
              }
            },
            {
              "@type": "ListItem",
              "position": 3,
              "item": {
                "@type": "VideoObject",
                "name": "BCS Exam Aid: বিষয়ভিত্তিক অনুশীলন",
                "description": "বিসিএস সিলেবাসের প্রতিটি বিষয় সহজভাবে আয়ত্ত করতে বিষয়ভিত্তিক অনুশীলন পদ্ধতি এবং অ্যাপের বিশেষ টেকনিকগুলো সম্পর্কে জানুন।",
                "thumbnailUrl": "https://img.youtube.com/vi/tmqSxbAQKFM/maxresdefault.jpg",
                "uploadDate": "2026-04-22T10:00:00+06:00",
                "embedUrl": "https://www.youtube.com/embed/tmqSxbAQKFM"
              }
            },
            {
              "@type": "ListItem",
              "position": 4,
              "item": {
                "@type": "VideoObject",
                "name": "BCS Exam Aid: বিষয়ভিত্তিক পরীক্ষা",
                "description": "নিজের প্রস্তুতি যাচাই করতে BCS Exam Aid অ্যাপের বিষয়ভিত্তিক পরীক্ষা ফিচারের ব্যবহার দেখে নিন এবং নিয়োগ পরীক্ষায় এগিয়ে থাকুন।",
                "thumbnailUrl": "https://img.youtube.com/vi/ezrSC30VuKg/maxresdefault.jpg",
                "uploadDate": "2026-04-21T10:00:00+06:00",
                "embedUrl": "https://www.youtube.com/embed/ezrSC30VuKg"
              }
            },
            {
              "@type": "ListItem",
              "position": 5,
              "item": {
                "@type": "VideoObject",
                "name": "BCS Exam Aid: রেফারেল সিস্টেম ও ফেসবুক",
                "description": "BCS Exam Aid অ্যাপের রেফারেল সিস্টেম ব্যবহার করে বোনাস পাওয়ার উপায় এবং আমাদের ফেসবুক কমিউনিটির সাথে যুক্ত হয়ে প্রস্তুতিকে আরও সমৃদ্ধ করার কৌশল জানুন।",
                "thumbnailUrl": "https://img.youtube.com/vi/GAy-0gd1_Jw/maxresdefault.jpg",
                "uploadDate": "2026-04-20T10:00:00+06:00",
                "embedUrl": "https://www.youtube.com/embed/GAy-0gd1_Jw"
              }
            }
          ]
        }
        </script>
      </div>
    </section>

    <section id="course-showcase" class="courses-section services-eight" style="background-color: #FAFAFA;">
      <style>
          /* কনটেক্সচুয়াল কালার ভ্যারিয়েবল */
          :root {
              --color-cat-1: #0052D4; /* বিসিএস: রিচ রয়্যাল ব্লু (Professional & Authoritative) */
              --color-cat-2: #009432; /* প্রাইমারি: ব্রাইট গ্রাস গ্রিন (Growth & Foundation) */
              --color-cat-3: #FF4500; /* ব্যাংক: ভাইব্রেন্ট অরেঞ্জ-রেড (Fast & Financial) */
              --color-cat-4: #00CEC9; /* NTRCA: ইলেকট্রিক টিল/সিয়ান (Academic & Fresh) */
              --color-cat-5: #2C3E50; /* NSI/DGFI: মিডনাইট ব্ল্যাক/স্লেট (Secure & Executive) */
              --color-cat-6: #8E44AD; /* প্রশ্ন ব্যাংক: অ্যামেথিস্ট পার্পল (Archive & Wisdom) */
          }

          /* গ্রেডিয়েন্ট ইফেক্ট আরও শার্প করার জন্য হেডার স্টাইল আপডেট */
          .card-header-visual {
              /* আমরা এখন কালার ভ্যারিয়েবলের সাথে একটা ডার্ক মিক্স দেব যাতে টেক্সট একদম ক্লিয়ার থাকে */
              background: linear-gradient(145deg, var(--cat-main-color) 0%, #000000 130%);
              padding: 40px 20px;
              text-align: center;
              color: #fff;
              position: relative;
              height: 200px;
              display: flex;
              flex-direction: column;
              justify-content: center;
              align-items: center;
              border-bottom: 4px solid rgba(255,255,255,0.2);
          }

          .category-name {
              background: rgba(255, 255, 255, 0.25); /* অপাসিটি বাড়ালাম যাতে আরও ফুটে ওঠে */
              color: #fff;
              padding: 4px 12px;
              border-radius: 4px; /* রাউন্ডেড থেকে একটু শার্প করলাম প্রফেশনাল লুকের জন্য */
              font-size: 12px;
              font-weight: 700;
              margin-bottom: 12px;
              text-transform: uppercase;
              letter-spacing: 1px;
          }

          /* ক্যাটাগরি অনুযায়ী কালার সেট করা */
          .single-course-card.cat-1 { --cat-main-color: var(--color-cat-1); }
          .single-course-card.cat-2 { --cat-main-color: var(--color-cat-2); }
          .single-course-card.cat-3 { --cat-main-color: var(--color-cat-3); }
          .single-course-card.cat-4 { --cat-main-color: var(--color-cat-4); }
          .single-course-card.cat-5 { --cat-main-color: var(--color-cat-5); }
          .single-course-card.cat-6 { --cat-main-color: var(--color-cat-6); }

          /* কার্ডের মূল স্টাইল */
          .single-course-card {
              background: #fff;
              border-radius: 20px;
              overflow: hidden;
              box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
              transition: all 0.3s ease-out;
              border: 1px solid #eee;
          }
          .single-course-card:hover {
              box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
              border-color: var(--cat-main-color);
          }
          .category-icon-bg {
              font-size: 50px;
              opacity: 0.2;
              position: absolute;
              bottom: -20px;
              right: 20px;
              color: #fff;
              transform: rotate(-15deg);
          }
          
          .visual-title {
              font-size: 20px;
              color: #fff;
              margin: 0;
              line-height: 1.4;
              font-weight: 700;
          }

          /* কার্ডের টেক্সট কন্টেন্ট */
          .card-body-content .course-title {
              font-size: 18px;
              color: #1a237e;
              font-weight: 700;
              margin-bottom: 0;
              margin-top: 10px;
          }
          .course-meta ul {
              display: flex;
              flex-wrap: wrap;
              margin: 0;
              padding: 0;
              list-style: none;
          }
          .course-meta ul li {
              display: flex;
              align-items: center;
              margin-right: 15px;
              margin-bottom: 10px;
          }
          .course-meta ul li .meta-icon {
              font-size: 16px;
              color: var(--cat-main-color);
              margin-right: 8px;
          }
          .course-meta ul li .meta-text p {
              font-size: 14px;
              margin: 0;
              color: #636e72;
          }
          .course-details {
              font-size: 14px;
              color: #636e72;
              line-height: 1.8;
          }
          /* বিবরণ অংশের কাস্টম স্ক্রলবার */
          .course-details .description {
              max-height: 150px; /* Optional: limit description height with overflow */
              overflow-y: auto;
              padding-right: 5px;
          }
          .course-details .description::-webkit-scrollbar {
              width: 5px;
          }
          .course-details .description::-webkit-scrollbar-thumb {
              background-color: #eee;
              border-radius: 5px;
          }
      </style>
        <div class="container">
            <div class="section-title-five">
              <div class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="content">
                      <h6>Course List</h6>
                      <h2 class="fw-bold">আমাদের চলমান কোর্সসমূহ</h2>
                      <p>
                        বিসিএস ও সরকারি চাকরির প্রস্তুতি সহজ করতে আমাদের বিশেষায়িত কোর্সগুলো বিস্তারিত দেখে নিন এখানেই!
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach($courses as $course)
                    @php
                        // ক্যাটাগরি অনুযায়ী কনটেক্সচুয়াল কালার ও আইকন সেট করা
                        $catColorClass = '';
                        $catIcon = '';
                        $catName = '';

                        if($course->category == 1) {
                            $catColorClass = 'cat-1'; // ব্লু - বিসিএস
                            $catIcon = 'lni-book';
                            $catName = 'বিসিএস';
                        } elseif($course->category == 2) {
                            $catColorClass = 'cat-2'; // গ্রিন - প্রাইমারি
                            $catIcon = 'lni-graduation';
                            $catName = 'প্রাইমারি';
                        } elseif($course->category == 3) {
                            $catColorClass = 'cat-3'; // অরেঞ্জ - ব্যাংক
                            $catIcon = 'lni-money-location';
                            $catName = 'ব্যাংক';
                        } elseif($course->category == 4) {
                            $catColorClass = 'cat-4'; // সিয়ান - NTRCA
                            $catIcon = 'lni-pencil';
                            $catName = 'NTRCA';
                        } elseif($course->category == 5) {
                            $catColorClass = 'cat-5'; // ডার্ক - NSI, DGFI ও অন্যান্য
                            $catIcon = 'lni-shield';
                            $catName = 'NSI, DGFI ও অন্যান্য';
                        } elseif($course->category == 6) {
                            $catColorClass = 'cat-6'; // পার্পল - প্রশ্ন ব্যাংক
                            $catIcon = 'lni-archive';
                            $catName = 'প্রশ্ন ব্যাংক';
                        }
                    @endphp

                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="single-course-card h-100 {{ $catColorClass }}">
                            
                            <div class="card-header-visual">
                                <div class="category-icon-bg">
                                    <i class="lni {{ $catIcon }}"></i>
                                </div>
                                <span class="category-name">{{ $catName }}</span>
                                <h4 class="visual-title">{{ $course->name }}</h4>
                            </div>

                            <div class="card-body-content p-4">
                                <h3 class="course-title">{{ $course->name }}</h3>
                                
                                <div class="course-meta pt-10">
                                    <ul>
                                        <li>
                                            <div class="meta-icon">
                                                <i class="lni lni-pencil-alt"></i>
                                            </div>
                                            <div class="meta-text">
                                                <p>মোট পরীক্ষা: {{ bangla($course->courseexams->count()) }} টি</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "ItemList",
          "name": "বিসিএস ও সরকারি চাকরি প্রস্তুতি কোর্সসমূহ - BCS Exam Aid",
          "description": "বিসিএস, ব্যাংক, প্রাইমারি এবং অন্যান্য সরকারি চাকরির জন্য ক্যাডারদের দ্বারা পরিচালিত পূর্ণাঙ্গ অনলাইন কোর্স।",
          "itemListElement": [
            @foreach($courses as $index => $course)
            @php
                // ক্যাটাগরি নামগুলো আগেই ভেরিয়েবলে সেট করে নিচ্ছি যাতে JSON ক্লিন থাকে
                $categoryNames = [
                    1 => 'Bangladesh Civil Service (BCS)',
                    2 => 'Primary Teacher Recruitment',
                    3 => 'Bank Job Preparation',
                    4 => 'NTRCA Teacher Registration',
                    5 => 'NSI and DGFI Preparation',
                    6 => 'Previous Year Question Bank'
                ];
                $catName = $categoryNames[$course->category] ?? 'General Government Job Preparation';
            @endphp
            {
              "@type": "ListItem",
              "position": {{ $index + 1 }},
              "item": {
                "@type": "Course",
                "url": "{{ url('/') }}#course-{{ $course->id }}",
                "name": "{{ $course->name }}",
                "description": "{{ $course->name }} - {{ $catName }}",
                "provider": {
                  "@type": "Organization",
                  "name": "BCS Exam Aid",
                  "sameAs": "{{ url('/') }}"
                },
                "hasCourseInstance": {
                  "@type": "CourseInstance",
                  "courseMode": "Online",
                  "courseWorkload": "মোট পরীক্ষা: {{ $course->courseexams->count() }} টি"
                },
                "about": {
                  "@type": "Thing",
                  "name": "{{ $catName }}",
                  "description": "{{ $catName }} ক্যাটাগরির পরীক্ষাসমূহ"
                }
              }
            }@if(!$loop->last),@endif
            @endforeach
          ]
        }
        </script>
    </section>


    <!-- Start Cta Area -->
    <section id="call-action" class="call-action">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-9">
            <div class="inner-content">
              <h2>বাংলাদেশ সিভিল সার্ভিস পরীক্ষা অথবা <br />অন্যান্য সরকারি চাকরির প্রস্তুতি নিয়ে চিন্তিত?</h2>
              <p>
                জ্যাম ঠেলে সময় খরচ করে কোচিং এ গিয়ে পরীক্ষার প্রস্তুতি নেওয়ার থেকে বাসায় বসে সে সময়টুকু কাজে লাগান, পাশাপাশি আমাদের অ্যাপের কোর্সগুলোতে অংশ নিয়ে পরীক্ষার প্রস্তুতিকে  শাণিত করুন! 
              </p>
              <div class="light-rounded-buttons">
                {{-- <a href="javascript:void(0)" class="btn primary-btn-outline">Get Started</a> --}}
                <a href='https://play.google.com/store/apps/details?id=com.orbachinujbuk.bcs' target="_blank"><img alt='Get it on Google Play' class="img-responsive" style="max-width: 300px; width: auto;" src='https://play.google.com/intl/en_us/badges/static/images/badges/en_badge_web_generic.png'/></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Cta Area -->

    <!-- ========================= contact-section start ========================= -->
    <section id="contact" class="contact-section">
      <div class="container">
        <div class="row">
          <div class="col-xl-4">
            <div class="contact-item-wrapper">
              <div class="row">
                <div class="col-12 col-md-6 col-xl-12">
                  <div class="contact-item">
                    <div class="contact-icon">
                      <i class="lni lni-phone"></i>
                    </div>
                    <div class="contact-content">
                      <h4>Contact</h4>
                      <p>+8801842828885</p>
                      <p>bcsexamaid.edu@gmail.com</p>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-xl-12">
                  <div class="contact-item">
                    <div class="contact-icon">
                      <i class="lni lni-map-marker"></i>
                    </div>
                    <div class="contact-content">
                      <h4>Address</h4>
                      <p>31/13, Block C, Tajmahal Road, Mohammadpur, Dhaka</p>
                      <p>Bangladesh</p>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-6 col-xl-12">
                  <div class="contact-item">
                    <div class="contact-icon">
                      <i class="lni lni-alarm-clock"></i>
                    </div>
                    <div class="contact-content">
                      <h4>Schedule</h4>
                      <p>24 Hours / 7 Days Open</p>
                      <p>Office time: 10 AM - 5:30 PM</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-8">
            <div class="contact-form-wrapper">
              <div class="row">
                <div class="col-xl-10 col-lg-8 mx-auto">
                  <div class="section-title text-center">
                    <span> Get in Touch </span>
                    <h2>
                      Account/Info Delete Request
                    </h2>
                    <p>
                      Send us messages.
                    </p>
                  </div>
                </div>
              </div>
              <form method="post" action="{{ route('index.account.deletion.request') }}" class="contact-form">
                @csrf
                <div class="row">
                  <div class="col-md-6">
                    <input type="text" name="name" id="name" placeholder="Name" required />
                  </div>
                  <div class="col-md-6">
                    <input type="email" name="email" id="email" placeholder="Email" required />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <input type="text" name="phone" id="phone" placeholder="Phone" required />
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="subject" id="email" placeholder="Specify the types of data" required />
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <textarea name="message" id="message" placeholder="Type Message (The reason you would like to delete your data. We are happy to help)" rows="5"></textarea>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="button text-center rounded-buttons">
                      <button type="submit" class="btn primary-btn rounded-full">
                        Send Message
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ========================= contact-section end ========================= -->

    <!-- ========================= map-section end ========================= -->
    <section class="map-section map-style-9">
      <div class="map-container">
        <object style="border:0; height: 500px; width: 100%;"
          data="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14606.067929552457!2d90.35101528681905!3d23.76459799164188!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c09f9ba3d447%3A0x1babce9f1c6c95a3!2sMohammadpur%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1655394465359!5m2!1sen!2sbd"></object>
      </div>
      </div>
    </section>
    <!-- ========================= map-section end ========================= -->
@endsection

@section('third_party_scripts')
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  {{-- <script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            // Open the modal
            $('#offerModalMain').modal('show');
        }, 500); // 1000ms = 1 second
    });
  </script> --}}

@endsection