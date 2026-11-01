@extends('layouts.app')

@section('title', 'ড্যাশবোর্ড | শুনানির কার্যতালিকা')

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
</style>
@endsection

@section('content')
    @section('page-header') শুনানির কার্যতালিকা (Cause List) @endsection

    <div class="container-fluid">

        <div class="card shadow-sm mb-3">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('hearings.today') }}" class="row g-2 align-items-center">
                    <div class="col-md-4 col-sm-6 mb-2">
                        <label class="small text-muted mb-1">তারিখ নির্বাচন করুন:</label>
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ $selectedDate }}">
                    </div>

                    <div class="col-md-5 col-sm-6 mb-2">
                        <label class="small text-muted mb-1">মামলা নং, বাদি বা বিবাদি খুঁজুন:</label>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="খুঁজুন..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3 col-sm-12 mb-2 pt-4 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill mr-1">
                            <i class="fas fa-search mr-1"></i> তালিকা দেখুন
                        </button>
                        <a href="{{ route('hearings.today') }}" class="btn btn-outline-secondary btn-sm" title="আজকের তারিখ">
                            আজকে
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- মূল টেবিল -->
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h3 class="card-title text-dark font-weight-bold mb-0">
                    <i class="fas fa-gavel text-success mr-1"></i> 
                    ধার্য তারিখ: {{ bangla(date('d F, Y', strtotime($selectedDate))) }} (মোট {{ bangla($files->total()) }} টি)
                </h3>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ক্র.নং</th>
                                <th>মামলা / নথি নম্বর</th>
                                <th>ধরন</th>
                                <th>বাদি ও বিবাদি</th>
                                <th>বর্তমান অবস্থান</th>
                                <th>বর্তমান অবস্থা</th>
                                <th class="text-right">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($files as $key => $file)
                                <tr>
                                    <td>{{ bangla($files->firstItem() + $key) }}</td>

                                    <td>
                                        <strong class="text-primary font-weight-bold">
                                            <a href="{{ route('files.show', $file->id) }}" title="শুনানি গ্রহন ও আদেশ লিখুন">
                                                {{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}
                                            </a>
                                        </strong>
                                        <div class="small text-muted">আইডি: <code>{{ substr($file->uuid, 0, 8) }}</code></div>
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
                                        <span class="badge badge-secondary px-2 py-1">
                                            {{ $file->currentDesk->designation ?? $file->currentDesk->name }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-info px-2 py-1">
                                            {{ $file->status }}
                                        </span>
                                    </td>

                                    <td class="text-right">
                                        <div class="btn-group">
                                            <!-- বিস্তারিত ও আদেশ প্রদান -->
                                            <a href="{{ route('files.show', $file->id) }}" class="btn btn-success btn-sm" title="শুনানি গ্রহন ও বিবরণ দেখুন">
                                                <i class="fas fa-gavel mr-1"></i> শুনানি নিন
                                            </a>

                                            <!-- 58mm স্টিকার প্রিন্ট -->
                                            <a href="{{ route('files.receipt', $file->id) }}" target="_blank" class="btn btn-outline-info btn-sm" title="58mm স্লিপ প্রিন্ট">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-calendar-check text-success fa-2x mb-2 d-block"></i>
                                        এই তারিখে কোনো শুনানির বাধ্যবাধকতা নেই।
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