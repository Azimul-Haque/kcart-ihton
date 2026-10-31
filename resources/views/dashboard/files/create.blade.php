@extends('layouts.app')

@section('title', 'ড্যাশবোর্ড | নতুন নথি এন্ট্রি')

@section('content')
    @section('page-header') নতুন নথি বা মোকদ্দমা রুজু করুন @endsection

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-primary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-folder-plus mr-1"></i> নথির প্রাথমিক বিবরণী</h3>
                    </div>
                    
                    <form action="{{ route('files.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            
                            <!-- নথির ধরন ও মামলা নং -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>নথির ধরন <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control" required>
                                        <option value="বিবিধ মোকদ্দমা" selected>বিবিধ মোকদ্দমা</option>
                                        <option value="দেওয়ানী মোকদ্দমা">দেওয়ানী মোকদ্দমা</option>
                                        <option value="১৪৪/১৪৫ মামলা">১৪৪/১৪৫ মামলা</option>
                                        <option value="CR Case">CR Case</option>
                                        <option value="SF (স্টেটমেন্ট অফ ফ্যাক্টস)">SF (স্টেটমেন্ট অফ ফ্যাক্টস / জবাব)</option>
                                        <option value="অভিযোগ / আবেদন">অভিযোগ / আবেদন</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>মোকদ্দমা / নথি নম্বর <small class="text-muted">(ফাঁকা রাখলে পরে দেওয়া যাবে)</small></label>
                                    <input type="text" name="case_number" class="form-control" placeholder="যেমন: ১২/২০২৬-২৭">
                                </div>
                            </div>

                            <hr>

                            <!-- বাদি ও বিবাদির তথ্য -->
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>আবেদনকারী / বাদির নাম</label>
                                    <input type="text" name="badi_name" class="form-control" placeholder="বাদির নাম">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>বাদির মোবাইল নম্বর</label>
                                    <input type="phone" name="badi_mobile" class="form-control" placeholder="01700000000">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>বিবাদি / অপর পক্ষের নাম</label>
                                    <input type="text" name="bibadi_name" class="form-control" placeholder="বিবাদির নাম">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>বিবাদির মোবাইল নম্বর</label>
                                    <input type="phone" name="bibadi_mobile" class="form-control" placeholder="01700000000">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>অতিরিক্ত মন্তব্য / বিষয়বস্তু</label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="নথি সংক্রান্ত সংক্ষিপ্ত বিবরণ বা আদেশ..."></textarea>
                            </div>

                        </div>

                        <div class="card-footer bg-light d-flex justify-content-between">
                            <a href="{{ route('files.index') }}" class="btn btn-secondary">ফিরে যান</a>
                            <button type="submit" class="btn btn-success font-weight-bold">
                                <i class="fas fa-save mr-1"></i> সংরক্ষণ করুন ও QR প্রিন্ট নিন
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection