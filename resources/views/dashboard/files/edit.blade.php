@extends('layouts.app')

@section('title', 'ড্যাশবোর্ড | নথি তথ্য সংশোধন')

@section('third_party_stylesheets')
<!-- Flatpickr Datepicker CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
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
    @section('page-header') নথির তথ্য সংশোধন করুন @endsection

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-warning shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title text-dark"><i class="fas fa-edit mr-1"></i> নথির তথ্য হালনাগাদ</h3>
                    </div>
                    
                    <form action="{{ route('files.update', $file->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>নথির ধরন <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control" required>
                                        @php
                                            $types = ['বিবিধ মোকদ্দমা', 'দেওয়ানী মোকদ্দমা', '১৪৪/১৪৫ মামলা', 'CR Case', 'SF (স্টেটমেন্ট অফ ফ্যাক্টস)', 'অভিযোগ / আবেদন'];
                                        @endphp
                                        @foreach($types as $t)
                                            <option value="{{ $t }}" {{ $file->type == $t ? 'selected' : '' }}>{{ $t }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>মোকদ্দমা / নথি নম্বর</label>
                                    <input type="text" name="case_number" class="form-control" value="{{ $file->case_number }}" placeholder="যেমন: ১২/২০২৬-২৭">
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>আবেদনকারী / বাদির নাম</label>
                                    <input type="text" name="badi_name" class="form-control" value="{{ $file->badi_name }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>বাদির মোবাইল নম্বর</label>
                                    <input type="text" name="badi_mobile" class="form-control" value="{{ $file->badi_mobile }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>বিবাদি / অপর পক্ষের নাম</label>
                                    <input type="text" name="bibadi_name" class="form-control" value="{{ $file->bibadi_name }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>বিবাদির মোবাইল নম্বর</label>
                                    <input type="text" name="bibadi_mobile" class="form-control" value="{{ $file->bibadi_mobile }}">
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>বর্তমান অবস্থা (Status) <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        @php
                                            $statuses = ['রুজু হয়েছে', 'তদন্তাধীন', 'শুনানির জন্য', 'আদেশের জন্য', 'চূড়ান্ত শুনানি', 'নিষ্পত্তি'];
                                        @endphp
                                        @foreach($statuses as $st)
                                            <option value="{{ $st }}" {{ $file->status == $st ? 'selected' : '' }}>{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>পরবর্তী শুনানির তারিখ</label>
                                    <input type="date" name="next_hearing_date" id="next_hearing_date" class="form-control" value="{{ $file->next_hearing_date ? date('Y-m-d', strtotime($file->next_hearing_date)) : '' }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>অতিরিক্ত মন্তব্য / বিষয়বস্তু</label>
                                <textarea name="remarks" class="form-control" rows="3">{{ $file->remarks }}</textarea>
                            </div>

                        </div>

                        <div class="card-footer bg-light d-flex justify-content-between">
                            <a href="{{ route('files.index') }}" class="btn btn-secondary">বাতিল</a>
                            <button type="submit" class="btn btn-primary font-weight-bold">
                                <i class="fas fa-sync-alt mr-1"></i> তথ্য হালনাগাদ করুন
                            </button>
                        </div>
                    </form>
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
        
        // ২. পরবর্তী ধার্য তারিখ (সোম ও বুধবার স্বয়ংস্ক্রিয় হাইলাইট)
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