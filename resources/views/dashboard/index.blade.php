@extends('layouts.app')
@section('title') ড্যাশবোর্ড @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
	@section('page-header') ড্যাশবোর্ড @endsection
    <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h4>{{ ceil($totalpayment) }}<sup style="font-size: 20px">৳</sup></h4>

                <p>মোট আয়</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="{{ route('dashboard.payments') }}" class="small-box-footer">আয় পাতা <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h4>{{ $totalmonthlypayment->totalamount ? ceil($totalmonthlypayment->totalamount) : 0 }}<sup style="font-size: 20px">৳</sup></h4>

                <p>মাসিক আয় ({{ date('F Y') }})</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="{{ route('dashboard.payments') }}" class="small-box-footer">মাসিক আয় পাতা <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h4>{{ $totalexamsattendedtoday }} বার</h4>

                <p>আজ মোট পরীক্ষায়  অংশগ্রহণ ({{ date("F d, Y") }})</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="{{ route('dashboard.examstoday') }}" class="small-box-footer">পরীক্ষার্থী তালিকা <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h4>{{ $totalusers }}</h4>

                <p>মোট ব্যবহারকারী</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="{{ route('dashboard.users') }}" class="small-box-footer">ব্যবহারকারীগণ <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
        <div class="row">
          {{-- <div class="col-md-6">
            <a href="{{ route('dashboard.deposit.getlist', [date('Y-m-d'), 'All']) }}" class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-coins"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">দৈনিক জমা</span>
                <span class="info-box-number">৳ {{ 0 }}</span>
              </div>
            </a>
          </div>
          <div class="col-md-6">
            <a href="{{ route('dashboard.expenses.getlist', [date('Y-m-d'), 'All']) }}" class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-hand-holding-usd"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">দৈনিক খরচ</span>
                <span class="info-box-number">৳ {{ 0 }}</span>
              </div>
            </a>
          </div> --}}
        </div>
        <div class="row">
          <div class="col-md-6">
            <button class="btn btn-warning" data-toggle="modal" data-target="#clearQueryCacheModal">
              <i class="fas fa-tools"></i> সকল কোয়েরি ক্যাশ (API) ক্লিয়ার করুন
            </button>
            {{-- Modal Code --}}
            {{-- Modal Code --}}
            <!-- Modal -->
            <div class="modal fade" id="clearQueryCacheModal" tabindex="-1" role="dialog" aria-labelledby="clearQueryCacheModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="clearQueryCacheModalLabel">কোয়েরি ক্যাশ ক্লিয়ার</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                      আপনি কি নিশ্চিতভাবে সকল কোয়েরি ক্যাশ ক্লিয়ার করতে চান?<br/>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                    <a href="{{ route('dashboard.clearquerycache') }}" class="btn btn-warning">ক্যাশ ক্লিয়ার করুন</a>
                    </div>
                </div>
                </div>
            </div>
            {{-- Modal Code --}}
            {{-- Modal Code --}}

            <button class="btn btn-success" data-toggle="modal" data-target="#loadTopicCacheModal">
              <i class="fas fa-spinner"></i> টপিক ক্যাশ লোড করুন
            </button>
            {{-- Modal Code --}}
            {{-- Modal Code --}}
            <!-- Modal -->
            <div class="modal fade" id="loadTopicCacheModal" tabindex="-1" role="dialog" aria-labelledby="clearQueryCacheModalLabel" aria-hidden="true" data-backdrop="static">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                    <h5 class="modal-title" id="clearQueryCacheModalLabel">টপিক ক্যাশ লোড</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                      আপনি কি নিশ্চিতভাবে সকল টপিক ক্যাশ লোড করতে চান?<br/>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                    <a href="{{ route('dashboard.topics.requild.cache', env('TOPIC_PATH_SECRET')) }}" class="btn btn-success">টপিক ক্যাশ লোড করুন</a>
                    </div>
                </div>
                </div>
            </div>
            {{-- Modal Code --}}
            {{-- Modal Code --}}
            <br/>
            <br/>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">ব্যবহারকারী যোগদানের হার</h3>
                <div class="card-tools">
                  <small>সর্বশেষ দুই সপ্তাহ</small>
                </div>
              </div>
              <div class="card-body">
              <div class="chart">
                <canvas id="lineChart" style="min-height: 250px; height: 300px; max-height: 400px; max-width: 100%;"></canvas>
              </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">ক্রমবর্ধমান ব্যবহারকারী সংখ্যা</h3>
                <div class="card-tools">
                  <small>সর্বশেষ দুই সপ্তাহ</small>
                </div>
              </div>
              <div class="card-body">
              <div class="chart">
                <canvas id="lineChart2" style="min-height: 250px; height: 300px; max-height: 400px; max-width: 100%;"></canvas>
              </div>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection

@section('third_party_scripts')
  <script src=" https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js "></script>
  <script type="text/javascript">
    var ctx = document.getElementById('lineChart').getContext('2d');
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $daysforchartc !!},
            datasets: [{
                label: 'ব্যবহারকারী সংখ্যা',
                borderColor: "#3e95cd",
                fill: true,
                data: {!! $totalusersforchartc !!},
                borderWidth: 2,
                borderColor: "rgba(0,165,91,1)",
                borderCapStyle: 'butt',
                pointBorderColor: "rgba(0,165,91,1)",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(0,165,91,1)",
                pointHoverBorderColor: "rgba(0,165,91,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 5,
                pointHitRadius: 10,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            
        }
    });


    var ctx = document.getElementById('lineChart2').getContext('2d');
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $daysforchartc !!},
            datasets: [{
                label: 'ক্রমবর্ধমান ব্যবহারকারী সংখ্যা',
                borderColor: "#112E8A",
                fill: true,
                data: {!! $totaluserscumulitiveforchartc !!},
                borderWidth: 2,
                borderColor: "rgba(17,46,138,1)",
                borderCapStyle: 'butt',
                pointBorderColor: "rgba(17,46,138,1)",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(17,46,138,1)",
                pointHoverBorderColor: "rgba(17,46,138,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 5,
                pointHitRadius: 10,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            
        }
    });
  </script>
@endsection