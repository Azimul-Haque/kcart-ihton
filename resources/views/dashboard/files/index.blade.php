@extends('layouts.app')

@section('title', 'ড্যাশবোর্ড | নথি তালিকা')

@section('third_party_stylesheets')
<style>
    .table thead th {
        background-color: #f8f9fa;
        font-size: 11px;
        letter-spacing: 0.5px;
        color: #555;
        text-transform: uppercase;
        border-bottom: 2px solid #dee2e6;
    }
    .aging-badge { font-size: 0.78rem; font-weight: 600; padding: 4px 8px; border-radius: 12px; }
    .badge-status { font-size: 0.8rem; padding: 5px 10px; }
    
    /* ১৪+ দিনের ক্রুশিয়াল ওয়ার্নিং অ্যানিমেশন ও স্টাইল */
    .aging-critical {
        background-color: #dc3545 !important;
        color: #ffffff !important;
        font-weight: 700;
        box-shadow: 0 0 6px rgba(220, 53, 69, 0.6);
        animation: pulse-danger 2s infinite;
    }
    @keyframes pulse-danger {
        0% { transform: scale(1); }
        50% { transform: scale(1.03); }
        100% { transform: scale(1); }
    }
</style>
@endsection

@section('content')
    @section('page-header') 
        @if(isset($isArchived))
            আর্কাইভকৃত / নিষ্পত্তিকৃত নথি তালিকা
        @elseif(isset($isMyDesk))
            আমার ডেস্কে থাকা নথিসমূহ
        @elseif(isset($isTransit))
            ট্রানজিটে থাকা নথি (রিসিভ অপেক্ষমান)
        @else
            সকল চলমান নথি ও মোকদ্দমা তালিকা
        @endif
    @endsection

    <div class="container-fluid">
        <!-- ফিল্টার এবং সার্চ বার -->
        <div class="card shadow-sm mb-3">
            <div class="card-body p-3">
                <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-center">
                    <div class="col-md-3 col-sm-6 mb-2">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="মামলা নং, বাদি, বিবাদি বা মোবাইল..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2 col-sm-6 mb-2">
                        <select name="type" class="form-control form-control-sm">
                            <option value="">সকল টাইপ</option>
                            <option value="বিবিধ মোকদ্দমা" {{ request('type') == 'বিবিধ মোকদ্দমা' ? 'selected' : '' }}>বিবিধ মোকদ্দমা</option>
                            <option value="দেওয়ানী মোকদ্দমা" {{ request('type') == 'দেওয়ানী মোকদ্দমা' ? 'selected' : '' }}>দেওয়ানী মোকদ্দমা</option>
                            <option value="১৪৪/১৪৫ মামলা" {{ request('type') == '১৪৪/১৪৫ মামলা' ? 'selected' : '' }}>১৪৪/১৪৫ মামলা</option>
                            <option value="CR Case" {{ request('type') == 'CR Case' ? 'selected' : '' }}>CR Case</option>
                            <option value="SF (স্টেটমেন্ট অফ ফ্যাক্টস)" {{ request('type') == 'SF (স্টেটমেন্ট অফ ফ্যাক্টস)' ? 'selected' : '' }}>SF Case</option>
                            <option value="অভিযোগ / আবেদন" {{ request('type') == 'অভিযোগ / আবেদন' ? 'selected' : '' }}>অভিযোগ / আবেদন</option>
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-6 mb-2">
                        <select name="status" class="form-control form-control-sm">
                            <option value="">সকল স্ট্যাটাস</option>
                            <option value="overdue_14" {{ request('status') == 'overdue_14' ? 'selected' : '' }} class="text-danger font-weight-bold">⚠️ ১৪+ দিন ঝুলন্ত নথি</option>
                            <option value="রুজু হয়েছে" {{ request('status') == 'রুজু হয়েছে' ? 'selected' : '' }}>রুজু হয়েছে</option>
                            <option value="তদন্তাধীন" {{ request('status') == 'তদন্তাধীন' ? 'selected' : '' }}>তদন্তাধীন</option>
                            <option value="শুনানির জন্য" {{ request('status') == 'শুনানির জন্য' ? 'selected' : '' }}>শুনানির জন্য</option>
                            <option value="আদেশের জন্য" {{ request('status') == 'আদেশের জন্য' ? 'selected' : '' }}>আদেশের জন্য</option>
                            <option value="চূড়ান্ত শুনানি" {{ request('status') == 'চূড়ান্ত শুনানি' ? 'selected' : '' }}>চূড়ান্ত শুনানি</option>
                            <option value="নিষ্পত্তি" {{ request('status') == 'নিষ্পত্তি' ? 'selected' : '' }}>নিষ্পত্তি</option>
                        </select>
                    </div>

                    @if(!isset($isMyDesk))
                    <div class="col-md-3 col-sm-6 mb-2">
                        <select name="desk_id" class="form-control form-control-sm">
                            <option value="">সকল ডেস্ক / কর্মকর্তা</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('desk_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->designation ?? $user->name }} ({{ $user->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-2 col-sm-12 mb-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill mr-1">
                            <i class="fas fa-filter"></i> ফিল্টার
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm" title="ফিল্টার মুছুন">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- মূল টেবিল কার্ড -->
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 class="card-title text-dark font-weight-bold mb-0">
                    <i class="fas fa-list text-primary mr-1"></i> 
                    @if(isset($isArchived))
                        নিষ্পত্তিকৃত নথির রেকর্ড ({{ bangla($files->total()) }} টি)
                    @else
                        নথির তালিকা ({{ bangla($files->total()) }} টি)
                    @endif
                </h3>
                <div class="d-flex align-items-center">
                    @if(!isset($isArchived))
                    <a href="{{ route('files.archived') }}" class="btn btn-outline-dark btn-sm mr-2">
                        <i class="fas fa-archive"></i> আর্কাইভ / নিষ্পত্তিকৃত তালিকা
                    </a>
                    @else
                    <a href="{{ route('files.index') }}" class="btn btn-outline-primary btn-sm mr-2">
                        <i class="fas fa-list"></i> চলমান সকল নথি
                    </a>
                    @endif

                    @if(Auth::user()->role == 'admin' || Auth::user()->designation == 'পেশকার')
                    <a href="{{ route('files.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus-circle"></i> নতুন নথি এন্ট্রি
                    </a>
                    @endif
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>মামলা / নথি নম্বর</th>
                                <th>ধরন</th>
                                <th>বাদি ও বিবাদি</th>
                                <th>বর্তমান ডেস্ক / অবস্থান</th>
                                <th>অবস্থা (Status)</th>
                                <th>আটকে থাকার সময়</th>
                                <th class="text-right">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($files as $file)
                                @php
                                    $lastMovement = $file->movements->first();
                                    $receivedAt = $lastMovement ? ($lastMovement->received_at ?? $lastMovement->created_at) : $file->created_at;
                                    $daysAtDesk = \Carbon\Carbon::parse($receivedAt)->diffInDays(now());
                                    
                                    if ($file->status == 'নিষ্পত্তি') {
                                        $agingClass = 'bg-secondary text-white';
                                    } elseif ($daysAtDesk >= 14) {
                                        $agingClass = 'aging-critical';
                                    } elseif ($daysAtDesk > 7) {
                                        $agingClass = 'bg-danger text-white';
                                    } elseif ($daysAtDesk > 3) {
                                        $agingClass = 'bg-warning text-dark';
                                    } else {
                                        $agingClass = 'bg-success text-white';
                                    }
                                @endphp
                                <tr class="{{ ($daysAtDesk >= 14 && $file->status != 'নিষ্পত্তি') ? 'table-danger' : '' }}">
                                    <td>
                                        <strong class="text-primary font-weight-bold">
                                            <a href="{{ route('files.show', $file->id) }}" title="নথি বিবরণ ও শুনানি ইতিহাস">
                                                {{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}
                                            </a>
                                        </strong>
                                        <div class="small text-muted">
                                            আইডি: <code>{{ substr($file->uuid, 0, 8) }}</code>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge badge-light border">{{ $file->type }}</span>
                                    </td>

                                    <td>
                                        <div class="small">
                                            <strong>বাদি:</strong> {{ $file->badi_name ?? '-' }} 
                                            @if($file->badi_mobile) <small class="text-muted">({{ bangla($file->badi_mobile) }})</small> @endif <br>
                                            <strong>বিবাদি:</strong> {{ $file->bibadi_name ?? '-' }}
                                            @if($file->bibadi_mobile) <small class="text-muted">({{ bangla($file->bibadi_mobile) }})</small> @endif
                                        </div>
                                    </td>

                                    <td>
                                        <span class="font-weight-bold text-dark d-block">
                                            {{ $file->currentDesk->designation ?? 'অনির্ধারিত' }}
                                        </span>
                                        <small class="text-muted">{{ $file->currentDesk->name ?? '' }}</small>
                                        
                                        @if($lastMovement && !$lastMovement->is_received && $file->status != 'নিষ্পত্তি')
                                            <div class="badge badge-warning d-block mt-1">রিসিভ অপেক্ষমান</div>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge {{ $file->status == 'নিষ্পত্তি' ? 'badge-dark' : 'badge-info' }} badge-status">
                                            {{ $file->status }}
                                        </span>
                                        @if($file->next_hearing_date && $file->status != 'নিষ্পত্তি')
                                            <div class="small text-danger mt-1">
                                                <i class="far fa-calendar-alt"></i> {{ bangla(date('d/m/Y', strtotime($file->next_hearing_date))) }}
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge {{ $agingClass }} aging-badge" title="ডেস্কে আসার পর থেকে সময়">
                                            @if($file->status == 'নিষ্পত্তি')
                                                <i class="fas fa-check-circle"></i> আর্কাইভকৃত
                                            @elseif($daysAtDesk >= 14)
                                                <i class="fas fa-exclamation-triangle"></i> {{ bangla($daysAtDesk) }} দিন (ঝুলন্ত)
                                            @else
                                                <i class="far fa-clock"></i> {{ bangla($daysAtDesk) }} দিন ধরে
                                            @endif
                                        </span>
                                    </td>

                                    <td class="text-right">
                                        <div class="btn-group">
                                            <!-- শুনানি বাটন -->
                                            

                                            @if($file->current_desk_id == Auth::id() || Auth::user()->role == 'admin')
                                            <a href="{{ route('files.show', $file->id) }}" class="btn btn-success btn-sm" title="শুনানি গ্রহন ও আদেশ লিখুন">
                                                <i class="fas fa-gavel"></i> শুনানি
                                            </a>
                                            @endif

                                            <!-- নথির তথ্য এডিট বাটন -->
                                            <a href="{{ route('files.edit', $file->id) }}" class="btn btn-outline-primary btn-sm" title="তথ্য সংশোধন করুন">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- QR Print -->
                                            <a href="{{ route('files.receipt', $file->id) }}" target="_blank" class="btn btn-outline-info btn-sm" title="58mm QR স্টিকার প্রিন্ট">
                                                <i class="fas fa-qrcode"></i>
                                            </a>

                                            <!-- Hearing Date Print -->
                                            <a href="{{ route('files.print_slip', $file->id) }}" target="_blank" class="btn btn-outline-success btn-sm" title="58mm QR স্টিকার প্রিন্ট">
                                                <i class="far fa-sticky-note"></i>
                                            </a>

                                            <!-- ফাইল ট্রান্সফার বাটন -->
                                            @if($file->status != 'নিষ্পত্তি' && ($file->current_desk_id == Auth::id() || Auth::user()->role == 'admin'))
                                            <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#transferModal{{ $file->id }}" title="অন্য ডেস্কে পাঠান">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                            @endif

                                            <!-- ফাইল চূড়ান্ত নিষ্পত্তি বাটন -->
                                            @if($file->status != 'নিষ্পত্তি' && ($file->current_desk_id == Auth::id() || Auth::user()->role == 'admin'))
                                            <button type="button" class="btn btn-dark btn-sm" data-toggle="modal" data-target="#disposeModal{{ $file->id }}" title="নথি চূড়ান্ত নিষ্পত্তি করুন">
                                                <i class="fas fa-check-double"></i> নিষ্পত্তি
                                            </button>
                                            @endif
                                        </div>

                                        <!-- ১. ফাইল ট্রান্সফার মডাল -->
                                        <div class="modal fade text-left" id="transferModal{{ $file->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-dark">
                                                        <h5 class="modal-title"><i class="fas fa-paper-plane mr-1"></i> ফাইল স্থানান্তর করুন</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <form action="{{ route('files.transfer', $file->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>মামলা নং:</label>
                                                                <input type="text" class="form-control" value="{{ $file->case_number ?? 'রুজু হয়নি' }} ({{ $file->type }})" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>প্রাপক কর্মকর্তা/ডেস্ক চিহ্নিত করুন:</label>
                                                                <select name="to_user_id" class="form-control" required>
                                                                    <option value="" disabled selected>বাছাই করুন...</option>
                                                                    @foreach($users as $u)
                                                                        @if($u->id != $file->current_desk_id)
                                                                            <option value="{{ $u->id }}">{{ $u->designation ?? $u->name }} ({{ $u->name }})</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>নোট / নির্দেশনা (ঐচ্ছিক):</label>
                                                                <textarea name="remarks" class="form-control" rows="2" placeholder="যেমন: তদন্ত প্রতিবেদন দিন..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                                                            <button type="submit" class="btn btn-warning font-weight-bold">পাঠিয়ে দিন</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ২. ফাইল চূড়ান্ত নিষ্পত্তি ও আর্কাইভ মডাল -->
                                        <div class="modal fade text-left" id="disposeModal{{ $file->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark text-white">
                                                        <h5 class="modal-title"><i class="fas fa-check-circle text-success mr-1"></i> নথি চূড়ান্ত নিষ্পত্তি নিশ্চিতকরণ</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <form action="{{ route('files.dispose', $file->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="alert alert-warning py-2 mb-3" style="font-size: 13px;">
                                                                <i class="fas fa-exclamation-circle mr-1"></i> নথি নিষ্পত্তি করা হলে তা সাধারণ তালিকা ও আমার ডেস্ক থেকে সরাতে আর্কাইভে সংরক্ষিত হবে।
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">মামলা/নথি নং:</label>
                                                                <input type="text" class="form-control form-control-sm" value="{{ $file->case_number ?? 'রুজু হয়নি' }} ({{ $file->type }})" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">চূড়ান্ত আদেশ স্বাক্ষরিত হয়েছে কি না? <span class="text-danger">*</span></label>
                                                                <select name="order_signed" class="form-control form-control-sm" required>
                                                                    <option value="yes" selected>হ্যাঁ, চূড়ান্ত আদেশ প্রদেয় ও অনুস্বাক্ষরিত</option>
                                                                    <option value="no">না, আদেশের খসড়া বাকি</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="font-weight-bold">নিষ্পত্তির সংক্ষিপ্ত বিবরণ / আদেশ নোট (ঐচ্ছিক):</label>
                                                                <textarea name="disposal_note" class="form-control form-control-sm" rows="3" placeholder="যেমন: আদেশে নামজারি মঞ্জুর করা হলো / মামলাটি ডিসমিস করা হলো..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">বাতিল</button>
                                                            <button type="submit" class="btn btn-success btn-sm font-weight-bold">
                                                                <i class="fas fa-archive mr-1"></i> চূড়ান্ত নিষ্পত্তি করুন
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                        কোনো নথি বা মোকদ্দমা পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($files->hasPages())
            <div class="card-footer bg-white d-flex justify-content-center">
                {{ $files->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection