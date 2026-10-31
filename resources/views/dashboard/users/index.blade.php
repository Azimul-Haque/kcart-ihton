@extends('layouts.app')
@section('title') ড্যাশবোর্ড | ব্যবহারকারীগণ @endsection

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
    @section('page-header') ব্যবহারকারীগণ (মোট {{ bangla($userscount) }} জন) @endsection
    <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">ব্যবহারকারীগণ</h3>
            <div class="card-tools">
              <form class="form-inline form-group-lg" action="">
                <div class="form-group">
                  <input type="search-param" class="form-control form-control-sm" placeholder="ব্যবহারকারী খুঁজুন" id="search-param" required>
                </div>
                <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                  <i class="fas fa-search"></i> খুঁজুন
                </button>
                <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addUserModal" style="margin-left: 5px;">
                  <i class="fas fa-user-plus"></i> নতুন
                </button>
              </form>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle shadow-sm">
                    <style>
                        .table thead th { 
                            background-color: #f8f9fa; 
                            text-transform: uppercase; 
                            font-size: 11px; 
                            letter-spacing: 0.5px; 
                            color: #777; 
                            border-bottom: 2px solid #eee;
                        }
                        .ref-code-badge {
                            background: #fff;
                            border: 1px dashed #007bff;
                            color: #007bff;
                            padding: 3px 10px;
                            border-radius: 4px;
                            font-family: 'Courier New', Courier, monospace;
                            font-weight: bold;
                            font-size: 13px;
                            display: inline-block;
                        }
                        .user-name-link { font-weight: 600; color: #333; transition: 0.3s; }
                        .user-name-link:hover { color: #007bff; text-decoration: none; }
                        .stat-pill { font-size: 12px; background: #f1f3f9; padding: 2px 8px; border-radius: 12px; color: #555; display: block; margin-bottom: 2px; }
                        .date-info { font-size: 11px; line-height: 1.2; color: #888; }
                    </style>
                    <thead>
                        <tr>
                            <th>ব্যবহারকারী</th>
                            <th>যোগাযোগ</th>
                            <th>পরিসংখ্যান</th>
                            <th>রোল</th>
                            <th>মেয়াদ</th>
                            <th class="text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <a href="{{ route('dashboard.users.single', $user->id) }}" class="user-name-link">
                                        {{ $user->name }}
                                    </a>
                                    {{-- Approval Status Icon --}}
                                    @if($user->is_approved)
                                        <i class="fas fa-check-circle text-success ml-1" title="অনুমোদিত"></i>
                                    @else
                                        <i class="fas fa-exclamation-circle text-danger ml-1" title="অনুমোদন অপেক্ষমান"></i>
                                    @endif

                                    <div class="date-info">যোগদান: {{ date('d M, Y', strtotime($user->created_at)) }}</div>
                                </td>

                                <td>
                                    <span class="text-dark small"><i class="fas fa-phone-alt mr-1 text-muted"></i>{{ $user->mobile }}</span>
                                </td>

                                <td>
                                    <span class="stat-pill"><i class="fas fa-file-signature mr-1 text-warning"></i> পরীক্ষা: {{ bangla($user->meritlists->count()) }}</span>
                                </td>

                                <td>
                                    <span class="badge @if($user->role == 'admin') badge-success @else badge-info @endif badge-pill px-3">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="font-weight-bold text-danger small">
                                        {{ date('d M, Y', strtotime($user->package_expiry_date)) }}
                                    </div>
                                </td>

                                <td class="text-right">
                                    <div class="btn-group">
                                        {{-- Approve / Unapprove Toggle Button --}}
                                        <form action="{{ route('dashboard.users.approve', $user->id) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @if($user->is_approved)
                                                <button type="submit" class="btn btn-outline-warning btn-sm" title="অনুমোদন বাতিল করুন" rel="tooltip" title="" data-original-title="অনুমোদন বাতিল করুন">
                                                    <i class="fas fa-user-times"></i>
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-outline-success btn-sm" title="অনুমোদন দিন" rel="tooltip" title="" data-original-title="অনুমোদন দিন">
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            @endif
                                        </form>
                                        
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $user->id }}" title="এডিট করুন" rel="tooltip" title="" data-original-title="এডিট করুন">
                                            <i class="fas fa-user-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $user->id }}" title="ডিলেট করুন" rel="tooltip" title="" data-original-title="ডিলেট করুন">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    </div>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade text-left" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title">ব্যবহারকারী আপডেট</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <form method="post" action="{{ route('dashboard.users.update', $user->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>নাম</label>
                                                                <input type="text" name="name" class="form-control mb-2" value="{{ $user->name }}" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>মোবাইল</label>
                                                                <input type="text" name="mobile" class="form-control mb-2" value="{{ $user->mobile }}" required>
                                                            </div>
                                                        </div>
                                                        <label>রোল</label>
                                                        <select name="role" class="form-control mb-2">
                                                            <option value="admin" @if($user->role == 'admin') selected @endif>এডমিন</option>
                                                            <option value="manager" @if($user->role == 'manager') selected @endif>ম্যানেজার</option>
                                                            <option value="user" @if($user->role == 'user') selected @endif>ব্যবহারকারী/ পরীক্ষার্থী</option>
                                                        </select>
                                                        
                                                        <label>স্ট্যাটাস</label>
                                                        <select name="is_approved" class="form-control mb-2">
                                                            <option value="1" @if($user->is_approved == 1) selected @endif>অনুমোদিত</option>
                                                            <option value="0" @if($user->is_approved == 0) selected @endif>অপেক্ষমান</option>
                                                        </select>

                                                        <label>প্যাকেজের মেয়াদ</label>
                                                        <input type="text" name="packageexpirydate" id="packageexpirydate{{ $user->id }}" class="form-control mb-2" value="{{ date('F d, Y', strtotime($user->package_expiry_date)) }}" required>
                                                        <label>পাসওয়ার্ড (পরিবর্তন করতে চাইলে)</label>
                                                        <input type="password" name="password" class="form-control" placeholder="নতুন পাসওয়ার্ড">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                                        <button type="submit" class="btn btn-primary">দাখিল করুন</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Delete Modal --}}
                                    <div class="modal fade text-left" id="deleteUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">ব্যবহারকারী ডিলেট</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <p>আপনি কি নিশ্চিতভাবে এই ব্যবহারকারীকে ডিলেট করতে চান?</p>
                                                    <h4><b>{{ $user->name }}</b></h4>
                                                    <small>{{ $user->mobile }}</small>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                                    <a href="{{ route('dashboard.users.delete', $user->id) }}" class="btn btn-danger">ডিলেট করুন</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            
                            <script type="text/javascript" src="{{ asset('js/jquery-for-dp.min.js') }}"></script>
                            <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
                            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
                            <script>
                                $("#packageexpirydate{{ $user->id }}").datepicker({
                                    format: 'MM dd, yyyy',
                                    todayHighlight: true,
                                    autoclose: true,
                                });
                            </script>
                        @endforeach
                    </tbody>
                </table>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        {{ $users->links() }}
    </div>

    {{-- Add User Modal Code --}}
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addUserModalLabel">নতুন ব্যবহারকারী যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.users.store') }}">
          <div class="modal-body">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="নাম" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="mobile" value="{{ old('mobile') }}" autocomplete="off" class="form-control" placeholder="মোবাইল নম্বর (১১ ডিজিট)" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-phone"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <select name="role" id="adduserrole" class="form-control" required>
                        <option selected="" disabled="" value="">ধরন</option>
                        <option value="admin">এডমিন</option>
                        <option value="manager">ম্যানেজার</option>
                        <option value="user" selected>ব্যবহারকারী/ পরীক্ষার্থী</option>
                    </select>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-user-secret"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" autocomplete="off" placeholder="পাসওয়ার্ড" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
            <button type="submit" class="btn btn-success">দাখিল করুন</button>
          </div>
          </form>
        </div>
      </div>
    </div>
@endsection

@section('third_party_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).on('click', '#search-button', function() {
          if($('#search-param').val() != '') {
            var urltocall = '{{ route('dashboard.users') }}' +  '/' + $('#search-param').val();
            location.href= urltocall;
          } else {
            $('#search-param').css({ "border": '#FF0000 2px solid'});
            Toast.fire({
                icon: 'warning',
                title: 'কিছু লিখে খুঁজুন!'
            })
          }
        });
        $("#search-param").keyup(function(e) {
          if(e.which == 13) {
            if($('#search-param').val() != '') {
              var urltocall = '{{ route('dashboard.users') }}' +  '/' + $('#search-param').val();
              location.href= urltocall;
            } else {
              $('#search-param').css({ "border": '#FF0000 2px solid'});
              Toast.fire({
                  icon: 'warning',
                  title: 'কিছু লিখে খুঁজুন!'
              })
            }
          }
        });
    </script>
@endsection