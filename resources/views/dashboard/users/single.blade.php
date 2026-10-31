@extends('layouts.app')

@section('title') প্রোফাইল | {{ $user->name }} @endsection

@section('third_party_stylesheets')
  <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
  <style type="text/css">
    .profile-card { border-radius: 15px; overflow: hidden; transition: 0.3s; }
    .info-box { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important; }
    .stat-pill { background: #f1f3f9; padding: 5px 12px; border-radius: 50px; font-weight: 600; color: #4e73df; font-size: 14px; display: inline-block; margin-top: 5px; border: 1px solid #d1d9e6; }
    .table-modern thead th { background-color: #f8f9fa; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; border-bottom: 2px solid #eee; }
    .table-modern td { vertical-align: middle !important; padding: 12px 15px; }
    .card-title-icon { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; margin-right: 10px; }
    .bg-soft-success { background: #e8f5e9; color: #2e7d32; }
    .bg-soft-primary { background: #e3f2fd; color: #1565c0; }
    .bg-soft-warning { background: #fffde7; color: #f57f17; }
    .pulse-green { animation: pulse-green 2s infinite; }
    @keyframes pulse-green { 0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); } 100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); } }
  </style>
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark"><i class="fas fa-user-circle text-primary"></i> {{ $user->name }}</h1>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card profile-card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="bg-primary p-4 text-center">
                        {{-- <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff&size=128" class="img-circle elevation-2 mb-3" style="width: 100px; border: 3px solid #fff;"> --}}
                        <h4 class="text-white font-weight-bold mb-0">{{ $user->name }}</h4>
                        <span class="badge badge-light badge-pill px-3">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div class="p-3">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item border-0">
                                <i class="fas fa-phone-alt text-muted mr-2"></i> <b>মোবাইল:</b> <span class="float-right text-dark">{{ $user->mobile }}</span>
                            </li>
                            <li class="list-group-item border-0">
                                <i class="fas fa-calendar-alt text-muted mr-2"></i> <b>যোগদান:</b> <span class="float-right text-dark">{{ bangla(date('d F, Y', strtotime($user->created_at))) }}</span>
                            </li>
                            <li class="list-group-item border-0">
                                <i class="fas fa-clock text-danger mr-2"></i> <b>মেয়াদ:</b> <span class="float-right text-danger font-weight-bold">{{ bangla(date('d F, Y', strtotime($user->package_expiry_date))) }}</span>
                            </li>
                            {{-- <li class="list-group-item border-0">
                                <i class="fas fa-shopping-cart text-muted mr-2"></i> <b>মোট ক্রয়:</b> <span class="float-right badge badge-primary">{{ bangla($user->payments->count()) }} বার</span>
                            </li> --}}
                        </ul>
                    </div>
                </div>
            </div>

            <div class="info-box bg-soft-primary">
                <span class="info-box-icon"><i class="fas fa-file-signature text-primary"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">পরীক্ষা তথ্য</span>
                    <span class="info-box-number text-primary h4 mb-0">{{ bangla($user->meritlists->count()) }} টি</span>
                    <small class="text-muted font-italic">সফলভাবে অংশগ্রহণ করেছেন</small>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title font-weight-bold mb-0">
                        <span class="card-title-icon bg-soft-primary"><i class="fas fa-list-ul"></i></span> পরীক্ষার ইতিহাস
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>পরীক্ষার নাম ও কোর্স</th>
                                    <th>সময়</th>
                                    <th class="text-center">প্রাপ্ত নম্বর</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->meritlists as $examdata)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $examdata->exam->name }}</div>
                                        <small class="text-muted"><i class="fas fa-book-open fa-xs"></i> {{ $examdata->course->name }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ date('F d Y h:i a', strtotime($examdata->created_at)) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info px-3 py-2" style="font-size: 14px;">{{ bangla($examdata->marks) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4 text-muted font-italic">এখনো কোনো পরীক্ষায় অংশগ্রহণ করা হয়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('third_party_scripts')
  <script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
        $('.table-modern').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "pageLength": 5
        });
    });
  </script>
  <script>
  function confirmPayout(userId) {
      // ১. প্রথমে ফর্মটি সিলেক্ট করুন
      var form = document.getElementById('payoutForm' + userId);

      // ২. ব্রাউজারের নেটিভ ভ্যালিডেশন চেক করুন (required, min, max ইত্যাদি)
      if (form.checkValidity()) {
          // ৩. যদি সব ফিল্ড ঠিক থাকে, তবেই Swal দেখাবে
          Swal.fire({
              title: "আপনি কি নিশ্চিত?",
              text: "টাকাটি পাঠানো হয়েছে এবং আপনি এই রেকর্ডটি ডাটাবেসে সেভ করতে চাচ্ছেন?",
              icon: "warning",
              showCancelButton: true,
              confirmButtonColor: "#28a745",
              cancelButtonColor: "#6c757d",
              confirmButtonText: "হ্যাঁ, কনফার্ম করুন!",
              cancelButtonText: "ফিরে যান",
              reverseButtons: true
          }).then((result) => {
              if (result.isConfirmed) {
                  form.submit(); // সব ঠিক থাকলে সাবমিট হবে
              }
          });
      } else {
          // ৪. যদি কোনো ফিল্ড বাকি থাকে, তবে ব্রাউজারকে এরর দেখাতে বলুন
          form.reportValidity();
      }
  }
  </script>
@endsection