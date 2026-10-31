@extends('layouts.app')
@section('title', 'ড্যাশবোর্ড | অফিস স্টাফগণ')

@section('third_party_stylesheets')
    <!-- Custom CSS for Table -->
    <style>
        .table thead th { 
            background-color: #f8f9fa; 
            text-transform: uppercase; 
            font-size: 11px; 
            letter-spacing: 0.5px; 
            color: #777; 
            border-bottom: 2px solid #eee;
        }
        .user-name-link { font-weight: 600; color: #333; transition: 0.3s; }
        .user-name-link:hover { color: #007bff; text-decoration: none; }
        .stat-pill { font-size: 12px; background: #f1f3f9; padding: 3px 10px; border-radius: 12px; color: #555; display: inline-block; margin-bottom: 2px; }
        .date-info { font-size: 11px; line-height: 1.2; color: #888; }
    </style>
@endsection

@section('content')
    @section('page-header') অফিস স্টাফ ও ব্যবহারকারী (মোট {{ bangla($userscount ?? 0) }} জন) @endsection
    
    <div class="container-fluid">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <h3 class="card-title mt-1"><i class="fas fa-users text-primary"></i> স্টাফ তালিকা</h3>
            <div class="card-tools">
              <form class="form-inline" action="{{ route('dashboard.users') ?? '#' }}" method="GET">
                <div class="input-group input-group-sm">
                  <input type="text" name="search" class="form-control" placeholder="নাম বা মোবাইল নম্বর খুঁজুন..." value="{{ request('search') }}">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i> খুঁজুন
                    </button>
                  </div>
                </div>
                <button type="button" class="btn btn-success btn-sm ml-2" data-toggle="modal" data-target="#addUserModal">
                  <i class="fas fa-user-plus"></i> নতুন স্টাফ
                </button>
              </form>
            </div>
          </div>
          
          <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>কর্মকর্তা/স্টাফের নাম</th>
                            <th>মোবাইল নম্বর</th>
                            <th>পদবী ও ডেস্ক</th>
                            <th>রোল</th>
                            <th>কাজের পরিসংখ্যান</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <span class="user-name-link">{{ $user->name }}</span>
                                    <div class="date-info mt-1">যোগদান: {{ date('d M, Y', strtotime($user->created_at)) }}</div>
                                </td>

                                <td>
                                    <span class="text-dark small font-weight-bold">
                                        <i class="fas fa-phone-alt mr-1 text-muted"></i> {{ bangla($user->mobile) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-secondary badge-pill px-3 py-1">
                                        {{ $user->designation ?? 'অনির্ধারিত' }}
                                    </span>
                                </td>

                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge badge-success badge-pill px-3"><i class="fas fa-user-shield"></i> এডমিন</span>
                                    @else
                                        <span class="badge badge-info badge-pill px-3"><i class="fas fa-user"></i> স্টাফ</span>
                                    @endif
                                </td>

                                <td>
                                    <!-- Pending files on this user's desk -->
                                    <span class="stat-pill text-warning border border-warning" title="উনার ডেস্কে আটকে থাকা ফাইল">
                                        <i class="fas fa-inbox"></i> পেন্ডিং ফাইল: {{ bangla($user->filesOnDesk->count() ?? 0) }}
                                    </span>
                                </td>

                                <td class="text-right">
                                    <div class="btn-group">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $user->id }}" title="এডিট করুন">
                                            <i class="fas fa-user-edit"></i>
                                        </button>
                                        <!-- Delete Button -->
                                        @if(Auth::id() != $user->id)
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $user->id }}" title="ডিলেট করুন">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        @endif
                                    </div>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade text-left" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">স্টাফ তথ্য আপডেট</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <form method="post" action="{{ route('dashboard.users.update', $user->id) ?? '#' }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-2">
                                                                <label>নাম</label>
                                                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>মোবাইল</label>
                                                                <input type="text" name="mobile" class="form-control" value="{{ $user->mobile }}" required>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6 mb-2">
                                                                <label>পদবী</label>
                                                                <select name="designation" class="form-control" required>
                                                                    @php $designations = ['সহকারী কমিশনার (ভূমি)', 'কানুনগো', 'সার্ভেয়ার', 'নাজির', 'পেশকার', 'অফিস সহকারী', 'জারিকারক', 'অফিস সহায়ক']; @endphp
                                                                    @foreach($designations as $desig)
                                                                        <option value="{{ $desig }}" @if($user->designation == $desig) selected @endif>{{ $desig }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 mb-2">
                                                                <label>সিস্টেম রোল</label>
                                                                <select name="role" class="form-control" required>
                                                                    <option value="admin" @if($user->role == 'admin') selected @endif>এডমিন</option>
                                                                    <option value="staff" @if($user->role == 'staff') selected @endif>স্টাফ</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group mt-2">
                                                            <label>নতুন পাসওয়ার্ড <small class="text-danger">(পরিবর্তন না করতে চাইলে ফাঁকা রাখুন)</small></label>
                                                            <input type="password" name="password" class="form-control" placeholder="নতুন পাসওয়ার্ড (ঐচ্ছিক)">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                                                        <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Delete Modal --}}
                                    <div class="modal fade text-left" id="deleteUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">স্টাফ ডিলেট</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body text-center mt-3">
                                                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                                    <p>আপনি কি নিশ্চিতভাবে এই স্টাফকে সিস্টেম থেকে মুছে ফেলতে চান?</p>
                                                    <h4><b>{{ $user->name }}</b></h4>
                                                    <small class="badge badge-secondary">{{ $user->designation }}</small>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                                    <form action="{{ route('dashboard.users.delete', $user->id) ?? '#' }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">হ্যাঁ, ডিলেট করুন</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle mr-1"></i> কোনো ব্যবহারকারী বা স্টাফ পাওয়া যায়নি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
          </div>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Add User Modal --}}
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title"><i class="fas fa-user-plus mr-1"></i> নতুন স্টাফ যোগ করুন</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.users.store') ?? '#' }}">
            @csrf
            <div class="modal-body">
                
                <div class="form-group">
                    <label>স্টাফের নাম</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="যেমন: মো: আব্দুর রহমান" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>মোবাইল নম্বর (লগইন আইডি)</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                        <input type="text" name="mobile" value="{{ old('mobile') }}" autocomplete="off" class="form-control" placeholder="যেমন: 01700000000" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>পদবী (Designation)</label>
                        <select name="designation" class="form-control" required>
                            <option value="" selected disabled>চিহ্নিত করুন...</option>
                            <option value="সহকারী কমিশনার (ভূমি)">সহকারী কমিশনার (ভূমি)</option>
                            <option value="কানুনগো">কানুনগো</option>
                            <option value="সার্ভেয়ার">সার্ভেয়ার</option>
                            <option value="নাজির">নাজির</option>
                            <option value="পেশকার">পেশকার</option>
                            <option value="অফিস সহকারী">অফিস সহকারী</option>
                            <option value="জারিকারক">জারিকারক</option>
                            <option value="অফিস সহায়ক">অফিস সহায়ক</option>
                            <option value="ইভূসক (পৌর)">ইভূসক (পৌর)</option>
                            <option value="ইভূসক (শিমুলকান্দি)">ইভূসক (শিমুলকান্দি)</option>
                            <option value="ইভূসক (সাদেকপুর)">ইভূসক (সাদেকপুর)</option>
                            <option value="ইভূসক (আগানগর)">ইভূসক (আগানগর)</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>সিস্টেম রোল</label>
                        <select name="role" class="form-control" required>
                            <option value="admin">এডমিন (AC Land)</option>
                            <option value="staff" selected>সাধারণ স্টাফ</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>অ্যাপের পাসওয়ার্ড</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                        <input type="password" name="password" class="form-control" autocomplete="off" placeholder="গোপন পাসওয়ার্ড দিন" required>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                <button type="submit" class="btn btn-success">দাখিল করুন</button>
            </div>
          </form>
        </div>
      </div>
    </div>
@endsection