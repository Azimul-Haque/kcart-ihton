@extends('layouts.app')

@section('title', 'নথির বিবরণী, শুনানি ও ইতিহাস')

@section('third_party_stylesheets')
<!-- Flatpickr Datepicker CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    /* কম্প্যাক্ট ইনফো টেবিল ও টেক্সট স্টাইল */
    .compact-card .card-body { padding: 10px 15px !important; }
    .info-table td, .info-table th { padding: 4px 8px; vertical-align: middle; border-top: none; font-size: 0.88rem; }
    .info-label { font-weight: 600; color: #495057; white-space: nowrap; }
    .mobile-small { font-size: 0.78rem; color: #6c757d; font-weight: normal; margin-left: 3px; }
    
    /* টাইমলাইন কাস্টম স্টাইল */
    .timeline { position: relative; margin: 0 0 15px 0; padding: 0; list-style: none; }
    .timeline::before {
        content: ''; position: absolute; top: 0; bottom: 0; width: 3px;
        background: #dee2e6; left: 15px; margin: 0; border-radius: 2px;
    }
    .timeline > div { position: relative; margin-right: 0; margin-bottom: 12px; }
    .timeline > div > .timeline-item {
        box-shadow: 0 1px 3px rgba(0,0,0,.1); border: 1px solid #e9ecef;
        border-radius: .25rem; background: #fff; color: #495057;
        margin-left: 38px; margin-right: 0; padding: 8px 12px; position: relative;
    }
    .timeline > div > i {
        width: 24px; height: 24px; font-size: 11px; line-height: 24px;
        position: absolute; color: #fff; border-radius: 50%;
        text-align: center; left: 3px; top: 4px; z-index: 1;
    }

    /* ফ্ল্যাটপিকার সোম ও বুধবার হাইলাইট কাস্টম CSS */
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
    @section('page-header') নথির বিস্তারিত ও আদেশ রেকর্ড @endsection

    <div class="container-fluid">
        
        <!-- ১. নথির মূল সারসংক্ষেপ (কম্প্যাক্ট ও স্মার্ট ভিউ) -->
        <div class="card card-outline card-primary shadow-sm mb-2 compact-card">
            <div class="card-body">
                <div class="row align-items-center">
                    
                    <!-- তথ্য টেবিল -->
                    <div class="col-md-10">
                        <table class="table table-sm info-table mb-0">
                            <tbody>
                                <tr>
                                    <td width="35%">
                                        <span class="info-label">মামলা নম্বর:</span> 
                                        <strong class="text-primary font-weight-bold" style="font-size: 1rem;">
                                            {{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}
                                        </strong>
                                        <span class="badge badge-primary ml-1">{{ $file->type }}</span>
                                    </td>
                                    <td width="35%">
                                        <span class="info-label">বর্তমান অবস্থান:</span> 
                                        <span class="badge badge-warning px-2">{{ $file->currentDesk->designation ?? $file->currentDesk->name }}</span>
                                    </td>
                                    <td width="30%">
                                        <span class="info-label">স্ট্যাটাস:</span> 
                                        <span class="badge badge-info px-2">{{ $file->status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="info-label">বাদি:</span> 
                                        <span class="font-weight-bold text-dark">{{ $file->badi_name ?? '-' }}</span>
                                        @if($file->badi_mobile) <span class="mobile-small">({{ bangla($file->badi_mobile) }})</span> @endif
                                    </td>
                                    <td>
                                        <span class="info-label">বিবাদি:</span> 
                                        <span class="font-weight-bold text-dark">{{ $file->bibadi_name ?? '-' }}</span>
                                        @if($file->bibadi_mobile) <span class="mobile-small">({{ bangla($file->bibadi_mobile) }})</span> @endif
                                    </td>
                                    <td>
                                        <span class="info-label">পরবর্তী তারিখ:</span> 
                                        <strong class="text-danger">
                                            {{ $file->next_hearing_date ? bangla(date('d/m/Y', strtotime($file->next_hearing_date))) : 'ধার্য হয়নি' }}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-muted small">
                                        <strong>মন্তব্য:</strong> {{ $file->remarks ?? 'নেই' }} | 
                                        <code>UUID: {{ substr($file->uuid, 0, 18) }}...</code>
                                    </td>
                                    <td class="text-right">
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fas fa-gavel"></i> শুনানি গ্রহণ: <strong>{{ bangla($file->hearings->count()) }}</strong> টি
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- প্রিন্ট বাটন -->
                    <div class="col-md-2 text-md-right mt-2 mt-md-0 border-left">
                        <a href="{{ route('files.receipt', $file->id) }}" target="_blank" class="btn btn-outline-info btn-sm btn-block">
                            <i class="fas fa-print mr-1"></i> QR স্টিকার
                        </a>
                    </div>

                </div>
            </div>
        </div>

        <!-- ২. মূল ওয়ার্কস্পেস (বামপাশে শুনানি ও ট্রানজিট ফর্ম, ডানপাশে ইতিহাস) -->
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
                                <input type="text" name="next_hearing_date" id="next_hearing_date" class="form-control form-control-sm bg-white" placeholder="তারিখ বেছে নিন" readonly>
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
                                @foreach($users as$user)
                                    @if($user->id != Auth::id())
                                        <option value="{{ $user->id }}">
                                            {{ $user->designation ?? $user->name }} ({{$user->name }})
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

            <!-- ডান দিক: আদেশের ইতিহাস এবং নথির গতিবিধি (ট্যাব সিস্টেমে) -->
            <div class="col-md-7">
                <div class="card shadow-sm card-outline card-info">
                    
                    <div class="card-header p-2 bg-white border-bottom-0">
                        <ul class="nav nav-pills" id="historyTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active py-1 px-3 font-weight-bold" id="hearings-tab" data-toggle="pill" href="#hearings" role="tab" aria-controls="hearings" aria-selected="true">
                                    <i class="fas fa-gavel mr-1"></i> আদেশের ইতিহাস ({{ bangla($file->hearings->count()) }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1 px-3 font-weight-bold" id="movements-tab" data-toggle="pill" href="#movements" role="tab" aria-controls="movements" aria-selected="false">
                                    <i class="fas fa-route mr-1"></i> ডেস্ক গতিবিধি ({{ bangla($file->movements->count()) }})
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-3">
                        <div class="tab-content" id="historyTabContent">
                            
                            <!-- ট্যাব ১: গৃহীত আদেশের ইতিহাস -->
                            <div class="tab-pane fade show active" id="hearings" role="tabpanel" aria-labelledby="hearings-tab">
                                @forelse($file->hearings->sortByDesc('created_at') as $index =>$hearing)
                                    <div class="border-left border-primary pl-3 pb-3 mb-3 position-relative" style="border-left-width: 3px !important;">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="font-weight-bold text-dark">
                                                শুনানি #{{ bangla($file->hearings->count() - $index) }} - {{ bangla(date('d F, Y', strtotime($hearing->hearing_date))) }}
                                            </span>
                                            <span class="badge badge-light border text-muted small">
                                                গ্রহণকারী: {{ $hearing->conductor->designation ?? ($hearing->conductor->name ?? 'অজ্ঞাত') }}
                                            </span>
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
                                        <i class="fas fa-gavel fa-2x mb-2 d-block opacity-