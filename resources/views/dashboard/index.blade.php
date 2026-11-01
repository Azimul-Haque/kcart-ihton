@extends('layouts.app')

@section('title', 'ড্যাশবোর্ড - নথি ট্র্যাকিং সিস্টেম')

@section('third_party_stylesheets')
  <!-- চাইলে কাস্টম CSS এখানে দিতে পারেন -->
  <style>
      .info-box-text { font-weight: bold; font-size: 1.1rem; }
      .small-box h4 { font-weight: 700; }
      .desk-badge { font-size: 0.9rem; padding: 5px 10px; }
  </style>
@endsection

@section('content')
    @section('page-header') ড্যাশবোর্ড ও পরিসংখ্যান @endsection
    
    <div class="container-fluid">
        <!-- 1st Row: Summary Cards -->
        <div class="row">
          <!-- Total Files -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h4>{{ $totalFiles ?? 0 }} টি</h4>
                <p>মোট নথি/মোকদ্দমা (সর্বমোট)</p>
              </div>
              <div class="icon">
                <i class="fas fa-folder-open"></i>
              </div>
              <a href="{{ route('files.index') }}" class="small-box-footer">নথির তালিকা দেখুন <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <!-- Today's Hearings -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h4>{{ $todaysHearings ?? 0 }} টি</h4>
                <p>আজকের শুনানি ({{ date('d F, Y') }})</p>
              </div>
              <div class="icon">
                <i class="fas fa-gavel"></i>
              </div>
              <a href="{{ route('hearings.today') }}" class="small-box-footer">শুনানির তালিকা <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Pending on My Desk -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h4>{{ $myPendingFiles ?? 0 }} টি</h4>
                <p>আপনার ডেস্কে অপেক্ষমান</p>
              </div>
              <div class="icon">
                <i class="fas fa-inbox"></i>
              </div>
              <a href="{{ route('files.my_desk') }}" class="small-box-footer">আপনার ডেস্ক খুলুন <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Unreceived/Lost Files Alert -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h4>{{ $unreceivedFiles ?? 0 }} টি</h4>
                <p>ট্রানজিটে আছে (রিসিভ হয়নি)</p>
              </div>
              <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <a href="{{ route('files.transit') }}" class="small-box-footer">বিস্তারিত দেখুন <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- 2nd Row: Important Info Boxes & Actions -->
        <div class="row">
          <!-- ১. নতুন নথি এন্ট্রি বক্স -->
          <div class="col-md-6">
              <a href="{{ route('files.create') }}" class="info-box mb-3 bg-primary shadow-sm text-decoration-none">
                  <span class="info-box-icon"><i class="fas fa-plus-circle"></i></span>
                  <div class="info-box-content">
                      <span class="info-box-text font-weight-bold">নতুন নথি/মামলা এন্ট্রি করুন</span>
                      <span class="info-box-number font-weight-normal">QR কোড প্রিন্টসহ</span>
                  </div>
              </a>
          </div>

          <!-- ২. অ্যাপ সেটিং বক্স (পাশের বক্সের সাথে ১০০% হাইট ও ডিজাইন ম্যাচ করা) -->
          <div class="col-md-6">
              <div class="info-box mb-3 bg-white border shadow-sm style-pointer" data-toggle="modal" data-target="#appVersionModal" style="cursor: pointer;">
                  <span class="info-box-icon bg-success text-white"><i class="fas fa-mobile-alt"></i></span>
                  <div class="info-box-content">
                      <div class="d-flex justify-content-between align-items-center">
                          <span class="info-box-text font-weight-bold text-dark">অ্যাপ ভার্শন সেটিংস</span>
                          <span class="badge badge-success px-2 py-1">v{{ \Illuminate\Support\Facades\Cache::get('app_min_version', '1.0.0') }}</span>
                      </div>
                      <span class="info-box-number text-muted font-weight-normal small">
                          <i class="fas fa-cog text-muted mr-1"></i>ক্লিক করে ভার্শন ও লিংক আপডেট করুন
                      </span>
                  </div>
              </div>
          </div>

          <!-- অ্যাপ সেটিংস পপ-আপ মোডাল (স্ক্রিনের জায়গা নষ্ট করবে না) -->
          <div class="modal fade" id="appVersionModal" tabindex="-1" role="dialog" aria-labelledby="appVersionModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content border-0 shadow">
                      <div class="modal-header bg-success text-white">
                          <h5 class="modal-title font-weight-bold" id="appVersionModalLabel">
                              <i class="fas fa-mobile-alt mr-2"></i>মোবাইল অ্যাপ সেটিং (Nothi Tracker)
                          </h5>
                          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      
                      <form method="POST" action="{{ route('dashboard.app-version.update') }}">
                          @csrf
                          <div class="modal-body p-4">
                              <!-- ভার্শন ইনপুট -->
                              <div class="form-group mb-3">
                                  <label class="font-weight-bold text-dark small mb-1">সর্বনিম্ন প্রয়োজনীয় ভার্শন (Min Version):</label>
                                  <div class="input-group input-group-sm">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fas fa-code-branch text-muted"></i></span>
                                      </div>
                                      <input type="text" name="min_version" class="form-control" 
                                             value="{{ \Illuminate\Support\Facades\Cache::get('app_min_version', '1.0.0') }}" 
                                             required placeholder="e.g. 1.0.1">
                                  </div>
                                  <small class="form-text text-muted">এই ভার্সনের নিচের সব ইউজার অ্যাপ চালু করলে ফোর্স আপডেট পপ-আপ দেখতে পাবে।</small>
                              </div>

                              <!-- প্লে স্টোর ইউআরএল -->
                              <div class="form-group mb-3">
                                  <label class="font-weight-bold text-dark small mb-1">প্লে স্টোর লিংক (Play Store URL):</label>
                                  <div class="input-group input-group-sm">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text"><i class="fab fa-google-play text-muted"></i></span>
                                      </div>
                                      <input type="url" name="update_url" class="form-control" 
                                             value="{{ \Illuminate\Support\Facades\Cache::get('app_update_url', 'https://play.google.com/store/apps/details?id=com.orbachinujbuk.nothi_tracker') }}" required>
                                  </div>
                              </div>

                              <!-- সুইচার -->
                              <div class="custom-control custom-switch mb-2">
                                  <input type="checkbox" name="force_update" class="custom-control-input" id="forceUpdateCheck" value="1" 
                                         {{ \Illuminate\Support\Facades\Cache::get('app_force_update', true) ? 'checked' : '' }}>
                                  <label class="custom-control-label font-weight-bold text-dark small" for="forceUpdateCheck">
                                      বাধ্যতামূলক আপডেট চালু রাখুন (Force Update)
                                  </label>
                              </div>
                          </div>

                          <div class="modal-footer bg-light py-2">
                              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">বন্ধ করুন</button>
                              <button type="submit" class="btn btn-success btn-sm px-3">
                                  <i class="fas fa-save mr-1"></i> সেভ করুন
                              </button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
        </div>

        <!-- 3rd Row: Charts & Analytics -->
        <div class="row">
          <!-- Pending Files by Desk (Bar Chart) -->
          <div class="col-md-6">
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar"></i> ডেস্ক অনুযায়ী আটকে থাকা নথি</h3>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="deskPendingChart" style="min-height: 250px; height: 300px; max-height: 400px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>
          </div>
          
          <!-- File Registration Trend (Line Chart) -->
          <div class="col-md-6">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> নথির রুজু হওয়ার হার</h3>
                <div class="card-tools">
                  <small>সর্বশেষ ১৪ দিন</small>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="fileTrendChart" style="min-height: 250px; height: 300px; max-height: 400px; max-width: 100%;"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

    </div>
@endsection

@section('third_party_scripts')
  <!-- ChartJS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
  
  <script type="text/javascript">
    // 1. Desk Pending Bar Chart
    var ctxDesk = document.getElementById('deskPendingChart').getContext('2d');
    var deskChart = new Chart(ctxDesk, {
        type: 'bar',
        data: {
            labels: {!! $deskNames ?? "['সার্ভেয়ার', 'নাজির', 'কানুনগো', 'পেশকার']" !!}, // Controller থেকে আসবে
            datasets: [{
                label: 'পেন্ডিং নথির সংখ্যা',
                backgroundColor: 'rgba(255, 193, 7, 0.8)', // Warning Yellow
                borderColor: 'rgba(255, 193, 7, 1)',
                borderWidth: 1,
                data: {!! $deskPendingCounts ?? "[12, 5, 8, 20]" !!} // Controller থেকে আসবে
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 5 }
                }
            }
        }
    });

    // 2. File Initiation Trend Line Chart
    var ctxTrend = document.getElementById('fileTrendChart').getContext('2d');
    var trendChart = new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: {!! $trendDates ?? "['1 Oct', '2 Oct', '3 Oct', '4 Oct']" !!}, // Controller থেকে আসবে
            datasets: [{
                label: 'নতুন রুজুকৃত নথি',
                borderColor: "#00a65a", // Success Green
                backgroundColor: "rgba(0,166,90,0.1)",
                fill: true,
                borderWidth: 2,
                pointBackgroundColor: "#00a65a",
                pointRadius: 4,
                pointHoverRadius: 6,
                data: {!! $trendCounts ?? "[5, 8, 3, 10]" !!} // Controller থেকে আসবে
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
  </script>
@endsection