@extends('layouts.app')
@section('title') ড্যাশবোর্ড | কাস্টমার সাপোর্ট মেসেজসমূহ @endsection

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <style>
        .msg-card { border-radius: 10px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .table-v-align td, .table-v-align th { vertical-align: middle !important; }
        .badge-package { background-color: #e8f5e9; color: #2e7d32; font-weight: 600; padding: 6px 10px; border-radius: 6px; }
        .action-btn-group .btn { border-radius: 6px; margin: 0 2px; }
        .user-avatar { width: 38px; height: 38px; background: #e3f2fd; color: #1565c0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
    </style>
@endsection

@section('content')
    @section('page-header') মেসেজ ও কাস্টমার সাপোর্ট @endsection
    <div class="container-fluid">
        
        <!-- Top Stats Row -->
        <div class="row mb-3">
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box shadow-sm rounded-lg">
                    <span class="info-box-icon bg-info rounded-circle"><i class="fas fa-comments"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted">মোট মেসেজ</span>
                        <span class="info-box-number h5 mb-0">{{ bangla($totalMessages) }} টি</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box shadow-sm rounded-lg">
                    <span class="info-box-icon bg-warning rounded-circle"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted">অপেক্ষমাণ (Pending)</span>
                        <span class="info-box-number h5 mb-0 text-warning">{{ bangla($pendingMessages) }} টি</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="info-box shadow-sm rounded-lg">
                    <span class="info-box-icon bg-success rounded-circle"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-muted">সমাধান হয়েছে (Resolved)</span>
                        <span class="info-box-number h5 mb-0 text-success">{{ bangla($resolvedMessages) }} টি</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card msg-card">
            <div class="card-header bg-white py-3 border-0">
                <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-inbox text-primary mr-2"></i> সাম্প্রতিক ইনকোয়ারি তালিকা</h3>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-v-align mb-0">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th>ইউজার প্রোফাইল</th>
                                {{-- <th>ক্রয় বিবরণী</th> --}}
                                <th style="width: 35%;">মেসেজ কন্টেন্ট</th>
                                <th>সময়</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-right">কুইক অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $message)
                                <tr class="{{ $message->status == 0 ? 'table-warning-light' : '' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar mr-2">
                                                {{ mb_substr($message->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">{{ $message->user->name ?? 'N/A' }}</div>
                                                <small class="text-muted">📞 {{ $message->user->mobile ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    {{-- <td>
                                        <span class="badge-package badge-sm">
                                            <i class="fas fa-shopping-bag mr-1"></i> {{ $message->user->payments->count() ? bangla($message->user->payments->count()) : 0 }} বার প্যাকেজ কিনেছেন
                                        </span>
                                    </td> --}}
                                    <td>
                                        <div class="p-2 bg-light rounded text-dark" style="font-size: 14px; max-height: 80px; overflow-y: auto;">
                                            {{ $message->message }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="far fa-clock mr-1"></i> {{ date('d M, Y', strtotime($message->created_at)) }}<br>
                                            <b>{{ date('h:i A', strtotime($message->created_at)) }}</b>
                                        </small>
                                    </td>
                                    <td>
                                        @if($message->status == 1)
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Solved</span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1"><i class="fas fa-hourglass-half mr-1"></i> Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-right action-btn-group">
                                        <!-- 1. INSTANT CALL BUTTON -->
                                        @if(isset($message->user->mobile))
                                            <a href="tel:{{ $message->user->mobile }}" class="btn btn-outline-success btn-sm" title="সরাসরি কল করুন">
                                                <i class="fas fa-phone-alt"></i>
                                            </a>
                                        @endif

                                        <!-- 2. INSTANT SMS BUTTON -->
                                        {{-- <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#smsModal{{ $message->id }}" title="SMS পাঠান">
                                            <i class="fas fa-paper-plane"></i>
                                        </button> --}}

                                        <!-- 3. MARK RESOLVED BUTTON -->
                                        @if($message->status == 0)
                                            <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#updateModal{{ $message->id }}" title="সমাধান হিসেবে চিহ্নিত করুন">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif

                                        <!-- 4. DELETE BUTTON -->
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $message->id }}" title="মুছে ফেলুন">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- ==================== INSTANT SMS MODAL ==================== -->
                                {{-- <div class="modal fade" id="smsModal{{ $message->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title font-weight-bold"><i class="fas fa-sms mr-2"></i> ইনস্ট্যান্ট SMS পাঠান</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <form method="POST" action="{{ route('dashboard.users.singlesms', $message->user->id) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="text-muted mb-1">প্রাপক:</label>
                                                        <div class="font-weight-bold">{{ $message->user->name }} ({{ $message->user->mobile }})</div>
                                                    </div>
                                                    
                                                    <!-- Quick Templates -->
                                                    <div class="form-group">
                                                        <label class="text-muted mb-1">কুইক টেমপ্লেট:</label><br>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary mr-1 mb-1" onclick="setTemplate('{{ $message->id }}', 'ধন্যবাদ, আপনার সমস্যার সমাধান করা হয়েছে। অ্যাপটি পুনরায় চেক করুন।\n- BCS Exam Aid')">সমাধান টেমপ্লেট</button>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary mr-1 mb-1" onclick="setTemplate('{{ $message->id }}', 'আপনার দেওয়া তথ্যে সমস্যা রয়েছে, দয়া করে অ্যাপ থেকে সঠিক মোবাইল নম্বর দিন।\n- BCS Exam Aid')">তথ্য সংশোধন</button>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="font-weight-bold">মেসেজ লিখুন:</label>
                                                        <textarea name="message" id="smsText{{ $message->id }}" class="form-control" rows="4" placeholder="বাংলা বা ইংরেজিতে মেসেজ লিখুন..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                                                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-1"></i> SMS পাঠান</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> --}}

                                <!-- ==================== UPDATE MODAL ==================== -->
                                <div class="modal fade" id="updateModal{{ $message->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title font-weight-bold"><i class="fas fa-clipboard-check mr-2"></i> সমাধান নিশ্চিতকরণ</h5>
                                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                আপনি কি এই মেসেজটিকে <b>"সমাধান হয়েছে"</b> হিসেবে মার্ক করতে চান?
                                                <div class="p-3 bg-light rounded mt-2">
                                                    <strong>{{ $message->user->name }}:</strong><br>
                                                    <span class="text-muted">{{ $message->message }}</span>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                                <form method="POST" action="{{ route('dashboard.messages.update', $message->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning font-weight-bold">হ্যাঁ, মার্ক করুন</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ==================== DELETE MODAL ==================== -->
                                <div class="modal fade" id="deleteModal{{ $message->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title font-weight-bold"><i class="fas fa-trash-alt mr-2"></i> ডিলিট নিশ্চিতকরণ</h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                আপনি কি নিশ্চিতভাবে এই সাপোর্ট মেসেজটি ডিলিট করতে চান?
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                                                <a href="{{ route('dashboard.messages.delete', $message->id) }}" class="btn btn-danger">ডিলিট করুন</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 text-light"></i><br>
                                        কোনো মেসেজ পাওয়া যায়নি!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('third_party_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        // Quick SMS Template Auto-Fill Logic
        function setTemplate(msgId, text) {
            $('#smsText' + msgId).val(text);
        }
    </script>
@endsection