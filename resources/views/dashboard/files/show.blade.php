@extends('layouts.app')

@section('title', 'নথির বিবরণী ও ইতিহাস')

@section('third_party_stylesheets')
<style>
    .timeline { position: relative; margin: 0 0 30px 0; padding: 0; list-style: none; }
    .timeline::before {
        content: ''; position: absolute; top: 0; bottom: 0; width: 4px;
        background: #dee2e6; left: 31px; margin: 0; border-radius: 2px;
    }
    .timeline > div { position: relative; margin-right: 10px; margin-bottom: 15px; }
    .timeline > div > .timeline-item {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        border-radius: .25rem; background: #fff; color: #495057;
        margin-left: 60px; margin-right: 0; padding: 0; position: relative;
    }
    .timeline > div > i {
        width: 30px; height: 30px; font-size: 15px; line-height: 30px;
        position: absolute; color: #fff; background: #6c757d; border-radius: 50%;
        text-align: center; left: 18px; top: 0;
    }
</style>
@endsection

@section('content')
    @section('page-header') নথির বিস্তারিত ও ট্র্যাকিং হিস্ট্রি @endsection

    <div class="container-fluid">
        <div class="row">
            
            <!-- বাম দিক: নথির সারসংক্ষেপ ও বর্তমান তথ্য -->
            <div class="col-md-5">
                <div class="card card-primary card-outline shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge badge-primary px-3 py-1" style="font-size: 1rem;">{{ $file->type }}</span>
                            <a href="{{ route('files.receipt', $file->id) }}" target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-print mr-1"></i> QR স্টিকার
                            </a>
                        </div>

                        <h3 class="font-weight-bold text-dark mb-1">
                            মামলা নং: {{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}
                        </h3>
                        <p class="text-muted small">আইডি (UUID): <code>{{ $file->uuid }}</code></p>

                        <hr>

                        <div class="mb-2"><strong>বর্তমান অবস্থান / ডেস্ক:</strong> 
                            <span class="badge badge-warning px-2 py-1">{{ $file->currentDesk->designation ?? $file->currentDesk->name }}</span>
                        </div>
                        <div class="mb-2"><strong>বর্তমান স্ট্যাটাস:</strong> 
                            <span class="badge badge-info px-2 py-1">{{ $file->status }}</span>
                        </div>
                        <div class="mb-2"><strong>পরবর্তী ধার্য তারিখ:</strong> 
                            <span class="text-danger font-weight-bold">
                                {{ $file->next_hearing_date ? bangla(date('d F, Y', strtotime($file->next_hearing_date))) : 'ধার্য করা হয়নি' }}
                            </span>
                        </div>

                        <hr>

                        <p class="mb-1"><strong>বাদির বিবরণ:</strong> {{ $file->badi_name ?? '-' }} ({{ bangla($file->badi_mobile) ?? 'ফোন নেই' }})</p>
                        <p class="mb-1"><strong>বিবাদির বিবরণ:</strong> {{ $file->bibadi_name ?? '-' }} ({{ bangla($file->bibadi_mobile) ?? 'ফোন নেই' }})</p>
                        <p class="mb-0"><strong>মন্তব্য:</strong> {{ $file->remarks ?? 'কোনো মন্তব্য নেই' }}</p>
                    </div>
                </div>

                <!-- নতুন শুনানি ও আদেশ এন্ট্রি বাটন/কার্ড (এসি ল্যান্ড বা দায়িত্বপ্রাপ্তদের জন্য) -->
                <div class="card bg-light shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title m-0"><i class="fas fa-gavel mr-1"></i> নতুন শুনানি / আদেশ এন্ট্রি করুন</h5>
                    </div>
                    <form action="{{ route('hearings.store', $file->id) }}" method="POST" class="card-body">
                        @csrf
                        <div class="form-group mb-2">
                            <label>শুনানির তারিখ</label>
                            <input type="date" name="hearing_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>নথির স্ট্যাটাস পরিবর্তন করুন</label>
                            <select name="status" class="form-control" required>
                                <option value="তদন্তাধীন">তদন্তাধীন</option>
                                <option value="শুনানির জন্য">শুনানির জন্য</option>
                                <option value="আদেশের জন্য">আদেশের জন্য</option>
                                <option value="চূড়ান্ত শুনানি">চূড়ান্ত শুনানি</option>
                                <option value="নিষ্পত্তি">নিষ্পত্তি</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label>পরবর্তী ধার্য তারিখ</label>
                            <input type="date" name="next_hearing_date" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label>আদেশ/বিবরণ</label>
                            <textarea name="order_details" class="form-control" rows="2" placeholder="আদেশের সংক্ষিপ্ত বক্তব্য..."></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="send_sms" value="1" class="form-check-input" id="smsCheck">
                            <label class="form-check-label text-danger font-weight-bold" for="smsCheck">বাদি-বিবাদিকে পরবর্তী তারিখের SMS পাঠান</label>
                        </div>
                        <button type="submit" class="btn btn-success btn-block font-weight-bold">আদেশ সংরক্ষণ করুন</button>
                    </form>
                </div>
            </div>

            <!-- ডান দিক: নথির ট্র্যাকিং টাইমলাইন (গতিবিধি ও শুনানির ইতিহাস) -->
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="card-title font-weight-bold"><i class="fas fa-history text-primary mr-1"></i> নথির গতিবিধি ও আদেশের ইতিহাস</h4>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            
                            @foreach($file->movements as $movement)
                            <div>
                                <i class="fas {{ $movement->is_received ? 'fa-check bg-success' : 'fa-shipping-fast bg-warning' }}"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock mr-1"></i>{{ bangla(date('d M, Y - h:i A', strtotime($movement->created_at))) }}</span>
                                    <h3 class="timeline-header font-weight-bold" style="font-size: 0.95rem;">
                                        {{ $movement->fromUser->designation ?? $movement->fromUser->name }} 
                                        ➔ 
                                        {{ $movement->toUser->designation ?? $movement->toUser->name }}
                                    </h3>
                                    <div class="timeline-body py-2">
                                        @if($movement->is_received)
                                            <span class="text-success font-weight-bold"><i class="fas fa-qrcode"></i> স্ক্যান করে রিসিভ করা হয়েছে</span>
                                            <small class="text-muted">({{ bangla(date('h:i A', strtotime($movement->received_at))) }})</small>
                                        @else
                                            <span class="text-danger font-weight-bold"><i class="fas fa-hourglass-half"></i> রিসিভ অপেক্ষমান (ট্রানজিটে আছে)</span>
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
@endsection