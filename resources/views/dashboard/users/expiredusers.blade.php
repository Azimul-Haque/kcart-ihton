@extends('layouts.app')
@section('title') ড্যাশবোর্ড | মেয়াদোত্তীর্ণ ব্যবহারকারীগণ @endsection

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
	@section('page-header') মেয়াদোত্তীর্ণ ব্যবহারকারীগণ (মোট {{ bangla($userscount) }} জন) @endsection
    <div class="container-fluid">
		<div class="card">
          <div class="card-header">
            <h3 class="card-title">মেয়াদোত্তীর্ণ ব্যবহারকারীগণ</h3>
            <small><a href="{{ route('dashboard.userssort')  }}" style="margin-left: 5px;">সর্বোচ্চ পরীক্ষার্থী</a></small>
            <small><a href="{{ route('dashboard.expiredusers')  }}" style="margin-left: 5px;">মেয়াদোত্তীর্ণ পরীক্ষার্থী</a></small>

            <div class="card-tools">
              <form class="form-inline form-group-lg" action="">
                <div class="form-group">
                  <input type="search-param" class="form-control form-control-sm" placeholder="ব্যবহারকারী খুঁজুন" id="search-param" required>
                </div>
                <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                  <i class="fas fa-search"></i> খুঁজুন
                </button>
                <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#sendMessage" style="margin-left: 5px;">
                  <i class="fas fa-envelope"></i> বার্তা পাঠান
                </button>
              </form>
            	
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <table class="table">
              <tbody>
                {{-- <tr>
                  <td>1.</td>
                  <td>Update software</td>
                  <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-danger">55%</span></td>
                </tr> --}}
                @foreach($users as $user)
                	<tr>
                		<td>
                			<a href="{{ route('dashboard.users.single', $user->id) }}">{{ $user->name }}</a>
                      <small><b>প্যাকেজ: ({{ bangla($user->payments->count()) }} বার)</b>, <b>পরীক্ষা: {{ bangla($user->meritlists->count()) }} টি</b></small>
                			<br/>
                            {{-- {{ $user->balances2 }} --}}
                			<small class="text-black-50">{{ $user->mobile }}</small> 
                			<span class="badge @if($user->role == 'admin') bg-success @else bg-info @endif">{{ ucfirst($user->role) }}</span>,
                      <small><span>যোগদান: {{ date('d F, Y h:i A', strtotime($user->created_at)) }}</span></small>,
                      <small><span>প্যাকেজ: <b>{{ date('d F, Y', strtotime($user->package_expiry_date)) }}</b></span></small>
                		</td>
                		<td align="right" width="40%">
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#smsModal{{ $user->id }}">
                        <i class="fas fa-envelope"></i>
                      </button>
                      {{-- SMS Modal Code --}}
                      {{-- SMS Modal Code --}}
                      <!-- Modal -->
                      <div class="modal fade" id="smsModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="smsModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-info">
                              <h5 class="modal-title" id="smsModalLabel">এসএমএস পাঠান</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="post" action="{{ route('dashboard.users.singlesms', $user->id) }}">
                              <div class="modal-body">
                                    @csrf
                                    <textarea class="form-control" placeholder="মেসেজ লিখুন" name="message" style="min-height: 150px; resize: none;" required></textarea>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <button type="submit" class="btn btn-info">মেসেজ পাঠান</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      {{-- SMS Modal Code --}}
                      {{-- SMS Modal Code --}}
                      <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#notifModal{{ $user->id }}">
                        <i class="fas fa-bell"></i>
                      </button>
                      {{-- Notif Modal Code --}}
                      {{-- Notif Modal Code --}}
                      <!-- Modal -->
                      <div class="modal fade" id="notifModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="notifModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-warning">
                              <h5 class="modal-title" id="notifModalLabel">নোটিফিকেশন পাঠান</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="post" action="{{ route('dashboard.users.singlenotification', $user->id) }}">
                              <div class="modal-body">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="headings"
                                               class="form-control"
                                               placeholder="হেডিংস" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-file-alt"></span></div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="message"
                                               class="form-control"
                                               placeholder="মেসেজ" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-spa"></span></div>
                                        </div>
                                    </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <button type="submit" class="btn btn-warning">দাখিল করুন</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      {{-- Notif Modal Code --}}
                      {{-- Notif Modal Code --}}
                			<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal{{ $user->id }}">
                				<i class="fas fa-user-edit"></i>
                			</button>
            			    {{-- Edit User Modal Code --}}
            			    {{-- Edit User Modal Code --}}
            			    <!-- Modal -->
            			    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true" data-backdrop="static">
            			      <div class="modal-dialog" role="document">
            			        <div class="modal-content">
            			          <div class="modal-header bg-primary">
            			            <h5 class="modal-title" id="editUserModalLabel">ব্যবহারকারী তথ্য হালনাগাদ</h5>
            			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            			              <span aria-hidden="true">&times;</span>
            			            </button>
            			          </div>
            			          <form method="post" action="{{ route('dashboard.users.update', $user->id) }}">
            				          <div class="modal-body">
            				            
            				                @csrf

            				                <div class="input-group mb-3">
            				                    <input type="text"
            				                           name="name"
            				                           class="form-control"
            				                           value="{{ $user->name }}"
            				                           placeholder="নাম" required>
            				                    <div class="input-group-append">
            				                        <div class="input-group-text"><span class="fas fa-user"></span></div>
            				                    </div>
            				                </div>

            				                <div class="input-group mb-3">
            				                    <input type="text"
            				                           name="mobile"
            				                           value="{{ $user->mobile }}"
            				                           autocomplete="off"
            				                           class="form-control"
            				                           placeholder="মোবাইল নম্বর (১১ ডিজিট)" required>
            				                    <div class="input-group-append">
            				                        <div class="input-group-text"><span class="fas fa-phone"></span></div>
            				                    </div>
            				                </div>

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="uid"
                                               value="{{ $user->uid }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="Firebase UID">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-server"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="onesignal_id"
                                               value="{{ $user->onesignal_id }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="Onesignal Player ID">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-bell"></span></div>
                                        </div>
                                    </div>

            				                <div class="input-group mb-3">
            				                	<select name="role" class="form-control" required>
            				                		<option disabled="" value="">ধরন নির্ধারণ করুন</option>
            				                		<option value="admin" @if($user->role == 'admin') selected="" @endif>এডমিন</option>
              													<option value="manager" @if($user->role == 'manager') selected="" @endif>ম্যানেজার</option>
                                        <option value="volunteer" @if($user->role == 'volunteer') selected="" @endif>ভলান্টিয়ার</option>
              													<option value="user" @if($user->role == 'user') selected="" @endif>ব্যবহারকারী</option>
              													{{-- <option value="accountant" @if($user->role == 'accountant') selected="" @endif>একাউন্টেন্ট</option> --}}
            				                	</select>
            				                    <div class="input-group-append">
            				                        <div class="input-group-text"><span class="fas fa-user-secret"></span></div>
            				                    </div>
            				                </div>

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="packageexpirydate"
                                               id="packageexpirydate{{ $user->id }}" 
                                               value="{{ date('F d, Y', strtotime($user->package_expiry_date)) }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="প্যাকেজের মেয়াদ বৃদ্ধি" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-calendar-check"></span></div>
                                        </div>
                                    </div>

            				                <div class="input-group mb-3">
            				                    <input type="password"
            				                           name="password"
            				                           class="form-control"
            				                           autocomplete="new-password"
            				                           placeholder="পাসওয়ার্ড (ঐচ্ছিক)">
            				                    <div class="input-group-append">
            				                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
            				                    </div>
            				                </div>
            				            
            				          </div>
            				          <div class="modal-footer">
            				            <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
            				            <button type="submit" class="btn btn-primary">দাখিল করুন</button>
            				          </div>
            			          </form>
            			        </div>
            			      </div>
            			    </div>
            			    {{-- Edit User Modal Code --}}
            			    {{-- Edit User Modal Code --}}

                			<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteUserModal{{ $user->id }}">
                				<i class="fas fa-user-minus"></i>
                			</button>
                		</td>
                        {{-- Delete User Modal Code --}}
                        {{-- Delete User Modal Code --}}
                        <!-- Modal -->
                        <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deleteUserModalLabel">ব্যবহারকারী ডিলেট</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                আপনি কি নিশ্চিতভাবে এই ব্যবহারকারীকে ডিলেট করতে চান?<br/>
                                <center>
                                    <big><b>{{ $user->name }}</b></big><br/>
                                    <small><i class="fas fa-phone"></i> {{ $user->mobile }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <a href="{{ route('dashboard.users.delete', $user->id) }}" class="btn btn-danger">ডিলেট করুন</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        {{-- Delete User Modal Code --}}
                        {{-- Delete User Modal Code --}}
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
          <!-- /.card-body -->
        </div>
        {{ $users->links() }}
    </div>

    {{-- Send SMS Modal Code --}}
    {{-- Send SMS Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="sendMessage" tabindex="-1" role="dialog" aria-labelledby="sendMessageLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="sendMessageLabel">বার্তা পাঠান</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.users.expired.sms') }}">
	          <div class="modal-body">
	              মোট SMS গ্রহণকারী: {{ $userscount }} জন<br/><br/>
	                @csrf
	                <div class="input-group mb-3">
	                	<textarea class="form-control" name="sms" style="min-height: 100px;" placeholder="বার্তা লিখুন" required></textarea>
	                </div>
                  @php
                    $random1 = rand(1,10);
                    $random2 = rand(1,20);

                    $randtotal = $random1 + $random2;
                  @endphp
                  <div class="input-group mb-3">
                    <input type="hidden" name="randtotalhidden" value="{{ $randtotal }}">
                      <input type="number"
                             name="randtotalvisible"
                             class="form-control"
                             placeholder="{{ $random1 . ' + ' . $random2 . ' = ?' }}" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-fingerprint"></span></div>
                      </div>
                  </div>
	          </div>
	          <div class="modal-footer">
	            <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
	            <button type="submit" class="btn btn-success">পাঠিয়ে দিন!</button>
	          </div>
          </form>
        </div>
      </div>
    </div>
    {{-- Send SMS Modal Code --}}
    {{-- Send SMS Modal Code --}}
@endsection

@section('third_party_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $('#adduserrole').change(function () {
            if($('#adduserrole').val() == 'accountant') {
                $('#ifaccountant').hide();
            } else {
                $('#ifaccountant').show();
            }
        });


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