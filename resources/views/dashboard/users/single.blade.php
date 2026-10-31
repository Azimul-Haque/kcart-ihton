@extends('layouts.app')

@section('title', 'ড্যাশবোর্ড | ' . $user->name)

@section('third_party_stylesheets')
<style>
    .profile-card { background: #fff; border-top: 4px solid #007bff; }
    .duration-badge { font-size: 0.85rem; font-weight: 600; padding: 5px 10px; border-radius: 20px; }
    .table thead th { background-color: #f8f9fa; font-size: 12px; color: #555; }
</style>
@endsection

@section('content')
    @section('page-header') স্টাফ প্রোফাইল ও ডেস্কে থাকা নথি @endsection

    <div class="container-fluid">
        <div class="row">
            
            <!-- Left Column: User Summary Card -->
            <div class="col-md-4">
                <div class="card profile-card shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="fa-stack fa-2x">
                                <i class="fas fa-circle fa-stack-2x text-primary"></i>
                                <i class="fas fa-user fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                        <h4 class="font-weight-bold mb-1">{{ $user->name }}</h4>
                        <p class="badge badge-primary px-3 py-1 font-weight-normal mb-2">{{ $user->designation ?? 'পদবী নির্ধারিত নয়' }}</p>
                        
                        <div class="text-muted small mb-3">
                            <i class="fas fa-phone-alt mr-1"></i> {{ bangla($user->mobile) }} <br>
                            {{-- <i class="fas fa-shield-alt mr-1"></i> রোল: {{ ucfirst($user->role) }} <br> --}}
                            <i class="fas fa-calendar-alt mr-1"></i> সিস্টেমে যোগদান: {{ date('d M, Y', strtotime($user->created_at)) }}
                        </div>

                        <a href="{{ route('dashboard.users') }}" class="btn btn-outline-secondary btn-sm btn-block">
                            <i class="fas fa-arrow-left"></i> স্টাফ তালিকায় ফিরে যান
                        </a>
                    </div>
                </div>

                <!-- Stats Summary Boxes -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title m-0 text-dark" style="font-size: 1rem;"><i class="fas fa-chart-pie mr-1"></i> কাজের সংক্ষিপ্ত বিবরণ</h5>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <span class="text-muted"><i class="fas fa-inbox text-warning mr-1"></i> ডেস্কে পেন্ডিং ফাইল:</span>
                            <span class="font-weight-bold badge badge-warning px-2 py-1">{{ bangla($filesOnDesk->count()) }} টি</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <span class="text-muted"><i class="fas fa-exclamation-triangle text-danger mr-1"></i> ৭ দিনের বেশি আটকে আছে:</span>
                            <span class="font-weight-bold badge badge-danger px-2 py-1">{{ bangla($overdueCount) }} টি</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted"><i class="fas fa-check-circle text-success mr-1"></i> মোট প্রসেসকৃত ফাইল:</span>
                            <span class="font-weight-bold badge badge-success px-2 py-1">{{ bangla($totalProcessed) }} টি</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Files Currently on Desk Table -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-primary font-weight-bold mb-0">
                            <i class="fas fa-folder-open mr-1"></i> বর্তমানে ডেস্কে থাকা নথিসমূহ ({{ bangla($filesOnDesk->count()) }})
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>নথি / মামলা নং</th>
                                        <th>ধরন</th>
                                        <th>বাদি ও বিবাদি</th>
                                        <th>ডেস্কে আসার তারিখ</th>
                                        <th>আটকে থাকার সময়</th>
                                        <th class="text-right">একশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($filesOnDesk as $file)
                                        @php
                                            $lastMovement = $file->movements->first();
                                            $receivedAt = $lastMovement ? ($lastMovement->received_at ?? $lastMovement->created_at) : $file->created_at;
                                            $daysAtDesk = \Carbon\Carbon::parse($receivedAt)->diffInDays(now());
                                            
                                            // Color coding logic based on duration
                                            if ($daysAtDesk <= 3) {
                                                $badgeClass = 'badge-success';
                                                $statusText = '১-৩ দিন (স্বাভাবিক)';
                                            } elseif ($daysAtDesk <= 7) {
                                                $badgeClass = 'badge-warning';
                                                $statusText = '৪-৭ দিন (সতর্কতা)';
                                            } else {
                                                $badgeClass = 'badge-danger';
                                                $statusText = '৭+ দিন (বিলম্বে আটক)';
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong class="text-dark">{{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}</strong>
                                                <div class="small text-muted">আইডি: {{ substr($file->uuid, 0, 8) }}...</div>
                                            </td>

                                            <td>
                                                <span class="badge badge-light border">{{ $file->type }}</span>
                                            </td>

                                            <td>
                                                <div class="small">
                                                    <strong>বাদি:</strong> {{ $file->badi_name ?? 'N/A' }} <br>
                                                    <strong>বিবাদি:</strong> {{ $file->bibadi_name ?? 'N/A' }}
                                                </div>
                                            </td>

                                            <td>
                                                <span class="small text-dark">
                                                    {{ date('d M, Y', strtotime($receivedAt)) }} <br>
                                                    <small class="text-muted">{{ date('h:i A', strtotime($receivedAt)) }}</small>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge {{ $badgeClass }} duration-badge" title="{{ $statusText }}">
                                                    <i class="far fa-clock mr-1"></i> {{ bangla($daysAtDesk) }} দিন ধরে
                                                </span>
                                            </td>

                                            <td class="text-right">
                                                <a href="{{ route('files.show', $file->id) }}" class="btn btn-outline-primary btn-sm" title="নথির বিস্তারিত ও টাইমলাইন দেখুন">
                                                    <i class="fas fa-eye"></i> বিস্তারিত
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                                                বর্তমানে এই কর্মকর্তার ডেস্কে কোনো ফাইল পেন্ডিং নেই।
                                            </td>
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