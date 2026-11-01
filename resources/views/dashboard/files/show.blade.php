@extends('layouts.app')

@section('title', 'নথির বিবরণী, শুনানি ও ইতিহাস')

@section('third_party_stylesheets')
<!-- Flatpickr Datepicker CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    /* ১. আল্ট্রা-কম্প্যাক্ট সামারি বার স্টাইল */
    .summary-card { border-left: 4px solid #007bff; }
    .summary-card .card-body { padding: 8px 15px !important; }
    .summary-title { font-size: 1.05rem; font-weight: 700; color: #1a252f; }
    .compact-label { font-size: 0.8rem; color: #6c757d; font-weight: 600; }
    .compact-val { font-size: 0.88rem; color: #212529; font-weight: 600; }
    .mobile-text { font-size: 0.76rem; color: #6c757d; font-weight: normal; }

    /* ২. টাইমলাইন ডিজাইন (ডেস্ক গতিবিধি) */
    .timeline { position: relative; margin: 0 0 10px 0; padding: 0; list-style: none; }
    .timeline::before {
        content: ''; position: absolute; top: 0; bottom: 0; width: 3px;
        background: #e9ecef; left: 15px; margin: 0; border-radius: 2px;
    }
    .timeline > div { position: relative; margin-bottom: 10px; }
    .timeline > div > .timeline-item {
        box-shadow: 0 1px 2px rgba(0,0,0,.05); border: 1px solid #e2e8f0;
        border-radius: .25rem; background: #fff; color: #495057;
        margin-left: 36px; padding: 8px 12px; position: relative;
    }
    .timeline > div > i {
        width: 22px; height: 22px; font-size: 10px; line-height: 22px;
        position: absolute; color: #fff; border-radius: 50%;
        text-align: center; left: 4px; top: 4px; z-index: 1;
    }

    /* ৩. সোম ও বুধবার হাইলাইটক্লিয়ার ক্যালেন্ডার CSS */
    .flatpickr-day.court-day-highlight {
        background: #d1e7dd !important;
        border-color: #0f5132 !important;
        color: #0f5132 !important;
        font-weight: bold !important;
        position: relative;
    }
    .flatpickr-day.court-day-highlight::after {
        content: '';
        position: absolute;
        bottom: 2px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background-color: #0f5132;
    }
</style>
@endsection

@section('content')
    @section('page-header') নথির বিবরণী ও রেকর্ড গ্রহণ @endsection

    <div class="container-fluid">
        
        <!-- ১. অতি সংক্ষিপ্ত ও সুন্দর সামারি কার্ড (One-Line Height Sleek View) -->
        <div class="card shadow-sm mb-3 summary-card">
            <div class="card-body">
                <div class="row align-items-center">
                    
                    <!-- মামলা নং, ধরন ও UUID -->
                    <div class="col-lg-3 col-md-4 border-right">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="summary-title text-primary">
                                <i class="fas fa-folder mr-1"></i>{{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}
                            </span>
                            <span class="badge badge-primary px-2 py-1">{{ $file->type }}</span>
                        </div>
                        <div class="text-muted small mt-1">
                            <code>UUID: {{ substr($file->uuid, 0, 16) }}...</code>
                        </div>
                    </div>

                    <!-- বাদি, বিবাদি, বর্তমান অবস্থান ও স্ট্যাটাস -->
                    <div class="col-lg-5 col-md-5 border-right my-2 my-md-0">
                        <div class="d-flex justify-content-between mb-1">
                            <div>
                                <span class="compact-label">বাদি:</span> 
                                <span class="compact-val">{{ $file->badi_name ?? '-' }}</span>
                                @if($file->badi_mobile) <span class="mobile-text">({{ bangla($file->badi_mobile) }})</span> @endif
                            </div>
                            <div>
                                <span class="compact-label">বিবাদি:</span> 
                                <span class="compact-val">{{ $file->bibadi_name ?? '-' }}</span>
                                @if($file->bibadi_mobile) <span class="mobile-text">({{ bangla($file->bibadi_mobile) }})</span> @endif
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="compact-label">অবস্থান:</span> 
                                <span class="badge badge-warning px-2">{{ $file->currentDesk->designation ?? $file->currentDesk->name }}</span>
                            </div>
                            <div>
                                <span class="compact-label">স্ট্যাটাস:</span> 
                                <span class="badge badge-info px-2">{{ $file->status }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- পরবর্তী ধার্য তারিখ, শুনানি কাউন্ট ও প্রিন্ট বাটনসমূহ -->
                    <div class="col-lg-4 col-md-3 d-flex align-items-center justify-content-between pl-md-3">
                        <div>
                            <div class="compact-label">পরবর্তী তারিখ:</div>
                            <strong class="text-danger" style="font-size: 1rem;">
                                {{ $file->next_hearing_date ? bangla(date('d/m/Y', strtotime($file->next_hearing_date))) : 'ধার্য হয়নি' }}
                            </strong>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-success px-2 py-1 d-block mb-1">
                                <i class="fas fa-gavel"></i> {{ bangla($file->hearings->count()) }} টি শুনানি
                            </span>
                            <div class="btn-group">
                                <a href="{{ route('files.receipt', $file->id) }}" target="_blank" class="btn btn-outline-info btn-sm py-0 px-2 font-weight-bold" style="font-size: 11px;" title="QR স্টিকার প্রিন্ট করুন">
                                    <i class="fas fa-qrcode"></i> QR স্টিকার
                                </a>
                                <a href="{{ route('files.print_slip', $file->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm py-0 px-2 font-weight-bold" style="font-size: 11px;" title="শুনানি স্লিপ প্রিন্ট করুন">
                                    <i class="fas fa-print"></i> শুনানি স্লিপ
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ২. মূল ওয়ার্কস্পেস -->
        <div class="row">
            
            <!-- বাম দিক: নতুন শুনানি গ্রহণ ও ট্রানজিট ফর্ম -->
            <div class="col-md-5">
                <div class="card shadow-sm border-success mb-3">
                    <div class="card-header bg-success text-white py-2">
                        <h5 class="card-title m-0 font-weight-bold" style="font-size: 0.95rem;">
                            <i class="fas fa-gavel mr-1"></i> নতুন শুনানি গ্রহণ ও আদেশ প্রদান
                        </h5>
                    </div>
                    
                    <form action="{{ route('hearings.store', $file->id) }}" method="POST" class="card-body p-3">
                        @csrf
                        
                        <div class="row">
                            <div class="col-6 form-group mb-2">
                                <label class="small mb-1 font-weight-bold">শুনানির তারিখ <span class="text-danger">*</span></label>
                                <input type="text" name="hearing_date" id="hearing_date" class="form-control form-control-sm bg-white" value="{{ date('Y-m-d') }}" required readonly>
                            </div>
                            <div class="col-6 form-group mb-2">
                                <label class="small mb-1 font-weight-bold">পরবর্তী ধার্য তারিখ <small class="text-success">(সোম/বুধ)</small></label>
                                <input type="text" name="next_hearing_date" id="next_hearing_date" class="form-control form-control-sm bg-white" placeholder="তারিখ সিলেক্ট করুন" required readonly>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="small mb-1 font-weight-bold">নথির স্ট্যাটাস আপডেট <span class="text-danger">*</span></label>
                            <select name="status" class="form-control form-control-sm" required>
                                <option value="তদন্তাধীন" {{ $file->status == 'তদন্তাধীন' ? 'selected' : '' }}>তদন্তাধীন</option>
                                <option value="শুনানির জন্য" {{ $file->status == 'শুনানির জন্য' ? 'selected' : '' }}>শুনানির জন্য</option>
                                <option value="আদেশের জন্য" {{ $file->status == 'আদেশের জন্য' ? 'selected' : '' }}>আদেশের জন্য</option>
                                <option value="চূড়ান্ত শুনানি" {{ $file->status == 'চূড়ান্ত শুনানি' ? 'selected' : '' }}>চূড়ান্ত শুনানি</option>
                                <option value="নিষ্পত্তি" {{ $file->status == 'নিষ্পত্তি' ? 'selected' : '' }}>নিষ্পত্তি</option>
                            </select>
                        </div>

                        <!-- কার কাছে হস্তান্তর করবেন (ট্রানজিট) -->
                        <div class="form-group mb-2 p-2 bg-light rounded border">
                            <label class="small mb-1 font-weight-bold text-primary">
                                <i class="fas fa-paper-plane mr-1"></i> শুনানি শেষে ফাইল ফরোয়ার্ড করুন (ঐচ্ছিক):
                            </label>
                            <select name="to_user_id" class="form-control form-control-sm">
                                <option value="">নিজের ডেস্কেই রাখুন (No Transfer)</option>
                                @foreach($users as $user)
                                    @if($user->id != Auth::id())
                                        <option value="{{ $user->id }}">
                                            {{ $user->designation ?? $user->name }} ({{ $user->name }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1" style="font-size: 11px;">
                                * সিলেক্ট করলে ফাইলটি সরাসরি উক্ত কর্মকর্তার ডেস্কে পেন্ডিং হিসেবে জমা হবে।
                            </small>
                        </div>

                        <div class="form-group mb-2">
                            <label class="small mb-1 font-weight-bold">আদেশের সংক্ষিপ্ত বক্তব্য/নোট</label>
                            <textarea name="order_details" class="form-control form-control-sm" rows="3" placeholder="আদেশের বিবরণ লিখুন..."></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="send_sms" value="1" class="form-check-input" id="smsCheck">
                            <label class="form-check-label text-danger font-weight-bold small" for="smsCheck">
                                বাদি-বিবাদিকে পরবর্তী তারিখের SMS নোটিফিকেশন পাঠান
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success btn-block font-weight-bold btn-sm py-2">
                            <i class="fas fa-save mr-1"></i> আদেশ সংরক্ষণ ও ফরোয়ার্ড করুন
                        </button>
                    </form>
                </div>
            </div>

            <!-- ডান দিক: আদেশের ইতিহাস এবং নথির গতিবিধি -->
            <div class="col-md-7">
                <div class="card shadow-sm card-outline card-info">
                    
                    <div class="card-header p-2 bg-white border-bottom">
                        <ul class="nav nav-pills" id="fileDetailTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active py-1 px-3 font-weight-bold" id="hearings-tab" data-toggle="pill" href="#hearings-content" role="tab" aria-controls="hearings-content" aria-selected="true">
                                    <i class="fas fa-gavel mr-1"></i> আদেশের ইতিহাস ({{ bangla($file->hearings->count()) }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1 px-3 font-weight-bold" id="movements-tab" data-toggle="pill" href="#movements-content" role="tab" aria-controls="movements-content" aria-selected="false">
                                    <i class="fas fa-route mr-1"></i> ডেস্ক গতিবিধি ({{ bangla($file->movements->count()) }})
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-3">
                        <div class="tab-content" id="fileDetailTabsContent">
                            
                            <!-- ট্যাব ১: গৃহীত আদেশের ইতিহাস -->
                            <div class="tab-pane fade show active" id="hearings-content" role="tabpanel" aria-labelledby="hearings-tab">
                                @forelse($file->hearings->sortByDesc('created_at') as $index => $hearing)
                                    <div class="border-left border-primary pl-3 pb-3 mb-3 position-relative" style="border-left-width: 3px !important;">
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="font-weight-bold text-dark">
                                                শুনানি #{{ bangla($file->hearings->count() - $index) }} - {{ bangla(date('d F, Y', strtotime($hearing->hearing_date))) }}
                                            </span>
                                            
                                            <div>
                                                <span class="badge badge-light border text-muted small mr-1">
                                                    গ্রহণকারী: {{ $hearing->conductor->designation ?? ($hearing->conductor->name ?? 'অজ্ঞাত') }}
                                                </span>
                                                <!-- ডিলিট বাটন -->
                                                <form action="{{ route('hearings.destroy', $hearing->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই শুনানির তথ্যটি মুছে ফেলতে চান?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm p-0 px-1" title="শুনানি ডিলিট করুন" style="font-size: 11px;">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <p class="mb-2 text-secondary bg-light p-2 rounded" style="font-size: 0.88rem; white-space: pre-line;">
                                            {{ $hearing->order_details ?? 'কোনো আদেশের বিবরণ লেখা হয়নি।' }}
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center small text-muted">
                                            <span>
                                                পরবর্তী ধার্য তারিখ: 
                                                <strong class="text-danger">
                                                    {{ $hearing->next_hearing_date ? bangla(date('d/m/Y', strtotime($hearing->next_hearing_date))) : 'অনির্ধারিত' }}
                                                </strong>
                                            </span>
                                            @if($hearing->sms_sent)
                                                <span class="text-success font-weight-bold"><i class="fas fa-comment-dots mr-1"></i> SMS প্রেরিত</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">
                                        <i class="fas fa-gavel fa-2x mb-2 d-block opacity-50"></i>
                                        এখনো কোনো আদেশ রেকর্ড করা হয়নি।
                                    </div>
                                @endforelse
                            </div>

                            <!-- ট্যাব ২: নথির ডেস্ক স্থানান্তর গতিবিধি (Timeline) -->
                            <div class="tab-pane fade" id="movements-content" role="tabpanel" aria-labelledby="movements-tab">
                                <div class="timeline">
                                    @forelse($file->movements->sortByDesc('created_at') as $movement)
                                    <div>
                                        <i class="fas {{ $movement->is_received ? 'fa-check bg-success' : 'fa-shipping-fast bg-warning' }}"></i>
                                        <div class="timeline-item">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="font-weight-bold m-0 text-dark" style="font-size: 0.80rem;">
                                                    <!-- প্রেরক (From User) -->
                                                    {{ $movement->fromUser->name ?? 'অজ্ঞাত' }}
                                                    @if(!empty($movement->fromUser->designation))
                                                        <small class="text-muted font-weight-normal">({{ $movement->fromUser->designation }})</small>
                                                    @endif

                                                    <!-- তীর চিহ্ন -->
                                                    <i class="fas fa-arrow-right text-muted mx-1" style="font-size: 11px;"></i>

                                                    <!-- প্রাপক (To User) -->
                                                    {{ $movement->toUser->name ?? 'অজ্ঞাত' }}
                                                    @if(!empty($movement->toUser->designation))
                                                        <small class="text-muted font-weight-normal">({{ $movement->toUser->designation }})</small>
                                                    @endif
                                                </h6>
                                                <span class="text-muted small">
                                                    <i class="far fa-clock mr-1"></i>{{ bangla(date('d M, Y - h:i A', strtotime($movement->created_at))) }}
                                                </span>
                                            </div>
                                            <div class="small">
                                                @if($movement->is_received)
                                                    <span class="text-success font-weight-bold"><i class="fas fa-qrcode mr-1"></i> গ্রহণ করা হয়েছে</span>
                                                    @if($movement->received_at)
                                                        <small class="text-muted">({{ bangla(date('h:i A', strtotime($movement->received_at))) }})</small>
                                                    @endif
                                                @else
                                                    <span class="text-danger font-weight-bold"><i class="fas fa-hourglass-half mr-1"></i> ট্রানজিটে আছে (রিসিভ করা হয়নি)</span>
                                                @endif
                                                @if($movement->remarks)
                                                    <div class="mt-1 text-secondary bg-light p-1 rounded">নোট: {{ $movement->remarks }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-4 text-muted">
                                        <i class="fas fa-route fa-2x mb-2 d-block opacity-50"></i>
                                        কোনো ডেস্ক ট্রান্সফার পাওয়া যায়নি।
                                    </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@section('third_party_scripts')
<!-- Flatpickr Datepicker JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // ১. শুনানির তারিখ ডেটপিকার
        flatpickr("#hearing_date", {
            dateFormat: "Y-m-d",
            defaultDate: "today"
        });

        // ২. পরবর্তী ধার্য তারিখ (সোম ও বুধবার স্বয়ংক্রিয় হাইলাইট)
        flatpickr("#next_hearing_date", {
            dateFormat: "Y-m-d",
            minDate: "today",
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                var day = dayElem.dateObj.getDay();
                if (day === 1 || day === 3) { // ১ = সোমবার, ৩ = বুধবার
                    dayElem.classList.add("court-day-highlight");
                    dayElem.title = "ধার্য দিন (সোম/বুধবার)";
                }
            }
        });

    });
</script>
@endsection