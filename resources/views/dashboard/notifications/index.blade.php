@extends('layouts.app')
@section('title') ড্যাশবোর্ড | নোটিফিকেশন @endsection

@section('third_party_stylesheets')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
@endsection

@section('content')
	@section('page-header') নোটিফিকেশন @endsection
    <div class="container-fluid">
  		<div class="row">
        <div class="col-md-9">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">নোটিফিকেশন তালিকা</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#newNotifModal" title="" rel="tooltip" data-original-title="নতুন নোটিফিকেশন">
                      <i class="fas fa-bell"></i> নতুন নোটিফিকেশন
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>ধরন</th>
                        <th>নোটিফিকেশন</th>
                        <th>সময়</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($notifications as $notification)
                        <tr>
                          <td>
                            @if($notification->type == 'premium')
                              <i class="far fa-gem"></i> প্রিমিয়াম
                            @elseif($notification->type == 'free')
                              <i class="fas fa-receipt"></i> ফ্রি
                            @else
                              <i class="fas fa-users"></i> সবাই
                            @endif
                          </td>
                          <td>{{ $notification->headings }}<br/>{{ $notification->message }}</td>
                          <td>{{ date('F d, Y h:m A', strtotime($notification->created_at)) }}</td>
                          
                          <td align="right">
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#copyModal{{ $notification->id }}" rel="tooltip" title="পুনরায় ব্যবহার করুন"><i class="fas fa-copy"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $notification->id }}">
                              <i class="fas fa-trash-alt"></i>
                            </button>
                          </td>
                              {{-- Duplicate Modal Code --}}
                              {{-- Duplicate Modal Code --}}
                              <!-- Modal -->
                              <div class="modal fade" id="copyModal{{ $notification->id }}" tabindex="-1" role="dialog" aria-labelledby="copyModalLabel" aria-hidden="true" data-backdrop="static">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                      <h5 class="modal-title" id="copyModalLabel">নোটিফিকেশন কপি</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <form method="post" action="{{ route('dashboard.notifications.sendagain') }}">
                                      @csrf
                                      <input type="hidden" name="type" value="{{ $notification->type }}">
                                      <input type="hidden" name="headings" value="{{ $notification->headings }}">
                                      <input type="hidden" name="message" value="{{ $notification->message }}">
                                      <div class="modal-body">
                                        আপনি কি নিশ্চিতভাবে এই নোটিফিকেশনটি আবার পাঠাতে চান?<br/><br/>
                                        <center>
                                          @if($notification->type == 'premium')
                                            <i class="far fa-gem"></i> প্রিমিয়াম
                                          @elseif($notification->type == 'free')
                                            <i class="fas fa-receipt"></i> ফ্রি
                                          @else
                                            <i class="fas fa-users"></i> সবাই
                                          @endif
                                        </center>
                                        <div style="margin: 30px; padding: 10px; border: 1px solid #007BFF; border-radius: 10px;">
                                          <small style="color: #7A7B7D;"><span class="fas fa-bell"></span> BCS Exam</small><br/>
                                          <b><span>{{ $notification->headings }}</span></b><br/>
                                          <span>{{ $notification->message }}</span>
                                        </div>
                                        
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                        <button type="submit" class="btn btn-warning">আবার পাঠান</button>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                              {{-- Duplicate Modal Code --}}
                              {{-- Duplicate Modal Code --}}
                              {{-- Delete Modal Code --}}
                              {{-- Delete Modal Code --}}
                              <!-- Modal -->
                              <div class="modal fade" id="deleteModal{{ $notification->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true" data-backdrop="static">
                                <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                      <h5 class="modal-title" id="deleteModalLabel">নোটিফিকেশন ডিলেট</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      আপনি কি নিশ্চিতভাবে এই নোটিফিকেশনটি ডিলেট করতে চান?<br/><br/>
                                      <div style="margin: 30px; padding: 10px; border: 1px solid #007BFF; border-radius: 10px;">
                                        <small style="color: #7A7B7D;"><span class="fas fa-bell"></span> BJS & Bar Exam</small><br/>
                                        <b><span>{{ $notification->headings }}</span></b><br/>
                                        <span>{{ $notification->message }}</span>
                                      </div>
                                      
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                      <a href="{{ route('dashboard.notifications.delete', $notification->id) }}" class="btn btn-danger">ডিলেট করুন</a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              {{-- Delete Modal Code --}}
                              {{-- Delete Modal Code --}}
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
        </div>  
        <div class="col-md-3"></div>  
      </div>
    {{ $notifications->links() }}<br/><br/>
    </div>

    {{-- New Notif Modal Code --}}
    {{-- New Notif Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="newNotifModal" tabindex="-1" role="dialog" aria-labelledby="newNotifModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-success">
              <h5 class="modal-title" id="newNotifModalLabel">নতুন ক্যাটাগরি যোগ</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post" action="{{ route('dashboard.notifications.send') }}">
                <div class="modal-body">
                      @csrf
                      <div class="input-group mb-3">
                        <select name="type" class="form-control" required>
                          <option disabled="" value="" selected>ধরন নির্ধারণ করুন</option>
                          <option value="premium">প্রিমিয়াম ব্যবহারকারী</option>
                          <option value="free">ফ্রি ব্যবহারকারী</option>
                          <option value="all">সব ব্যবহারকারী</option>
                          <option value="update">সব ব্যবহারকারী (UPDATE)</option>
                        </select>
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-wrench"></span></div>
                          </div>
                      </div>
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="headings"
                                 id="headings" 
                                 class="form-control"
                                 placeholder="হেডিংস" required>
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-file-alt"></span></div>
                          </div>
                      </div>
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="message"
                                 id="message" 
                                 class="form-control"
                                 placeholder="মেসেজ" required>
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="fas fa-spa"></span></div>
                          </div>
                      </div>
                      <div style="margin: 20px; padding: 5px; border: 1px solid #007BFF; border-radius: 10px;" id="notifdemo">
                        <small style="color: #7A7B7D;"><span class="fas fa-bell"></span> BJS & Bar Exam</small><br/>
                        <b><span id="headingstext"></span></b><br/>
                        <span id="messagetext"></span>
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
      {{-- New Notif Modal Code --}}
      {{-- New Notif Modal Code --}}
@endsection

@section('third_party_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $('#notifdemo').hide();
        $('#headings').keyup(function () {
          $('#notifdemo').show();
          $('#headingstext').text(this.value);
        });
        $('#message').keyup(function () {
          $('#notifdemo').show();
          $('#messagetext').text(this.value);
        });
    </script>
@endsection