@extends('layouts.app')

@section('title', 'নথির বিবরণী, শুনানি ও ইতিহাস')

@section('third_party_stylesheets')
<style>
    .info-label { font-weight: 600; color: #555; font-size: 0.85rem; }
    .info-value { font-size: 0.95rem; color: #111; }
    .mobile-small { font-size: 0.78rem; color: #6c757d; font-weight: normal; }
    
    /* Timeline styling */
    .timeline { position: relative; margin: 0 0 20px 0; padding: 0; list-style: none; }
    .timeline::before {
        content: ''; position: absolute; top: 0; bottom: 0; width: 3px;
        background: #dee2e6; left: 20px; margin: 0; border-radius: 2px;
    }
    .timeline > div { position: relative; margin-right: 5px; margin-bottom: 12px; }
    .timeline > div > .timeline-item {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 2px rgba(0,0,0,.1);
        border-radius: .25rem; background: #fff; color: #495057;
        margin-left: 45px; margin-right: 0; padding: 0; position: relative;
    }
    .timeline > div > i {
        width: 24px; height: 24px; font-size: 12px; line-height: 24px;
        position: absolute; color: #fff; background: #6c757d; border-radius: 50%;
        text-align: center; left: 8px; top: 2px;
    }
</style>
@endsection

@section('content')
    @section('page-header') নথির বিস্তারিত ও আদেশ রেকর্ড @endsection

    <div class="container-fluid">
        
        <!-- ১. নথির মূল সারসংক্ষেপ (কম্প্যাক্ট ২-কলাম তথ্য বক্স) -->
        <div class="card card-outline card-primary shadow-sm mb-3">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    
                    <!-- বাম কলাম: নথির শিরোনাম, আইডি ও স্ট্যাটাস -->
                    <div class="col-md-6 border-right">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h4 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-folder text-primary mr-1"></i>
                                মামলা নং: {{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}
                            </h4>
                            <span class="badge badge-primary px-2 py-1">{{ $file->type }}</span>
                        </div>
                        
                        <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                            <span class="mr-3"><span class="info-label">বর্তমান অবস্থান:</span> 
                                <span class="badge badge-warning px-2 py-1">{{ $file->currentDesk->designation ?? $file->currentDesk->name }}</span>
                            </span>
                            <span class="mr-3"><span class="info-label">স্ট্যাটাস:</span> 
                                <span class="badge badge-info px-2 py-1">{{ $file->status }}</span>
                            </span>
                            <span><span class="info-label">পরবর্তী তারিখ:</span> 
                                <span class="text-danger font-weight-bold">
                                    {{ $file->next_hearing_date ? bangla(date('d/m/Y', strtotime($file->next_hearing_date))) : 'ধার্য করা হয়নি' }}
                                </span>
                            </span>
                        </div>

                        <div class="text-muted small">
                            <code>UUID: {{ $file->uuid }}</code> | 
                            <a href="{{ route('files.receipt', $file->id) }}" target="_blank" class="text-info font-weight-bold ml-1">
                                <i class="fas fa-print"></i> QR স্টিকার
                            </a>
                        </div>
                    </div>

                    <!-- ডান কলাম: বাদি-বিবাদীর তথ্য ও মোট শুনানি সংখ্যা -->
                    <div class="col-md-6 pl-md-4 mt-2 mt-md-0">
                        <div class="row">
                            <div class="col-6 mb-1">
                                <div class="info-label">বাদি (আবেদনকারী):</div>
                                <div class="info-value font-weight-bold text-dark">
                                    {{ $file->badi_name ?? '-' }}
                                    @if($file->badi_mobile)
                                        <div class="mobile-small"><i class="fas fa-phone-alt"></i> {{ bangla($file->badi_mobile) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6 mb-1">
                                <div class="info-label">বিবাদি (অপর পক্ষ):</div>
                                <div class="info-value font-weight-bold text-dark">
                                    {{ $file->bibadi_name ?? '-' }}
                                    @if($file->bibadi_mobile)
                                        <div class="mobile-small"><i class="fas fa-phone-alt"></i> {{ bangla($file->bibadi_mobile) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                            <small class="text-muted"><strong>মন্তব্য:</strong> {{ $file->remarks ?? 'নেই' }}</small>
                            <span class="badge badge-success px-2 py-1 font-weight-normal">
                                <i class="fas fa-gavel mr-1"></i> মোট শুনানি গ্রহণ: <strong>{{ bangla($file->hearings->count()) }}</strong> টি
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- ২. মূল ওয়ার্কস্পেস (বামপাশে শুনানি ও ট্রানজিট ফর্ম, ডানপাশে ইতিহাস) -->
        <div class="row">
            
            <!-- বাম দিক: নতুন শুনানি গ্রহণ ও ট্রানজিট ফর্ম (উপরে স্থাপন করা হয়েছে) -->
            <div class="col-md-5">
                <div class="card shadow-sm border-success mb-3">
                    <div class="card-header bg-success text-white py-2">
                        <h5 class="card-title m-0 font-weight-bold" style="font-size: 1rem;">
                            <i class="fas fa-gavel mr-1"></i> নতুন শুনানি গ্রহণ ও আদেশ প্রদান
                        </h5>
                    </div>
                    
                    <form action="{{ route('hearings.store', $file->id) }}" method="POST" class="card-body p-3">
                        @csrf
                        
                        <div class="row">
                            <div class="col-6 form-group mb-2">
                                <label class="small mb-1 font-weight-bold">শুনানির তারিখ <span class="text-danger">*</span></label>
                                <input type="date" name="hearing_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-6 form-group mb-2">
                                <label class="small mb-1 font-weight-bold">পরবর্তী ধার্য তারিখ</label>
                                <input type="date" name="next_hearing_date" class="form-control form-control-sm">
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
                                <i class="fas fa-paper-plane mr-1"></i> শুনানি শেষে ফাইল ফরোয়ার্ড করুন (ঐচ্ছিক):
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
                                * সিলেক্ট করলে ফাইলটি সরাসরি উক্ত কর্মকর্তার ডেস্কে পেন্ডিং হিসেবে চলে যাবে।
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
                            <i class="fas fa-save mr-1"></i> আদেশ সংরক্ষণ ও ফরোয়ার্ড করুন
                        </button>
                    </form>
                </div>
            </div>

            <!-- ডান দিক: আদেশের ইতিহাস এবং নথির গতিবিধি (ট্যাব সিস্টেমে) -->
            <div class="col-md-7">
                <div class="card shadow-sm card-outline card-info">
                    
                    <div class="card-header p-2 bg-white">
                        <ul class="nav nav-pills" id="historyTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active py-1 px-3 font-weight-bold" id="hearings-tab" data-toggle="pill" href="#hearings" role="tab">
                                    <i class="fas fa-gavel mr-1"></i> আদেশের ইতিহাস ({{ bangla($file->hearings->count()) }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link py-1 px-3 font-weight-bold" id="movements-tab" data-toggle="pill" href="#movements" role="tab">
                                    <i class="fas fa-route mr-1"></i> ডেস্ক গতিবিধি ({{ bangla($file->movements->count()) }})
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-3">
                        <div class="tab-content" id="historyTabContent">
                            
                            <!-- ট্যাব ১: গৃহীত আদেশের ইতিহাস -->
                            <div class="tab-pane fade show active" id="hearings" role="tabpanel">
                                @forelse($file->hearings->sortByDesc('created_at') as $index => $hearing)
                                    <div class="border-left border-primary pl-3 pb-3 mb-3 position-relative" style="border-left-width: 3px !important;">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="font-weight-bold text-dark">
                                                শুনানি #{{ bangla($file->hearings->count() - $index) }} - {{ bangla(date('d F, Y', strtotime($hearing->hearing_date))) }}
                                            </span>
                                            <span class="badge badge-light border text-muted small">
                                                গ্রহণকারী: {{ $hearing->conductor->designation ?? $hearing->conductor->name }}
                                            </span>
                                        </div>

                                        <p class="mb-2 text-secondary bg-light p-2 rounded" style="font-size: 0.9rem; white-space: pre-line;">
                                            {{ $hearing->order_details ?? 'কোনো আদেশের বিবরণ লেখা হয়নি।' }}
                                        </p>

                                        <div class="d-flex justify-content-between align-items-center small text-muted">
                                            <span>
                                                পরবর্তী তারিখ: 
                                                <strong class="text-danger">
                                                    {{ $hearing->next_hearing_date ? bangla(date('d/m/Y', strtotime($hearing->next_hearing_date))) : 'অনির্ধারিত' }}
                                                </strong>
                                            </span>
                                            @if($hearing->sms_sent)
                                                <span class="text-success"><i class="fas fa-comment-dots mr-1"></i> SMS পাঠানো হয়েছে</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">
                                        <i class="fas fa-gavel fa-2x mb-2 d-block opacity-50"></i>
                                        এখনো কোনো আদেশ রেকর্ড করা হয়নি।
                                    </div>
                                @endforelse
                            </div>

                            <!-- ট্যাব ২: নথির ডেস্ক স্থানান্তর গতিবিধি (Timeline) -->
                            <div class="tab-pane fade" id="movements" role="tabpanel">
                                <div class="timeline">
                                    @foreach($file->movements as $movement)
                                    <div>
                                        <i class="fas {{ $movement->is_received ? 'fa-check bg-success' : 'fa-shipping-fast bg-warning' }}"></i>
                                        <div class="timeline-item">
                                            <span class="time small"><i class="fas fa-clock mr-1"></i>{{ bangla(date('d M, Y - h:i A', strtotime($movement->created_at))) }}</span>
                                            <h3 class="timeline-header font-weight-bold py-1 px-2" style="font-size: 0.88rem;">
                                                {{ $movement->fromUser->designation ?? $movement->fromUser->name }} 
                                                ➔ 
                                                {{ $movement->toUser->designation ?? $movement->toUser->name }}
                                            </h3>
                                            <div class="timeline-body p-2 small">
                                                @if($movement->is_received)
                                                    <span class="text-success font-weight-bold"><i class="fas fa-qrcode"></i> স্ক্যান করে রিসিভ করা হয়েছে</span>
                                                    <small class="text-muted">({{ bangla(date('h:i A', strtotime($movement->received_at))) }})</small>
                                                @else
                                                    <span class="text-danger font-weight-bold"><i class="fas fa-hourglass-half"></i> ট্রানজিটে আছে (রিসিভ করা হয়নি)</span>
                                                @endif
                                                @if($movement->remarks)
                                                    <div class="mt-1 text-secondary">নোট: {{ $movement->remarks }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection