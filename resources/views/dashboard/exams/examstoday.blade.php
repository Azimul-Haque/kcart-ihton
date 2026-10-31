@extends('layouts.app')
@section('title') ড্যাশবোর্ড | আজ মোট পরীক্ষায় অংশগ্রহণ  @endsection

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
@endsection

@section('content')
	@section('page-header') আজ মোট পরীক্ষায় অংশগ্রহণ @endsection
    <div class="container-fluid">
		<div class="card">
          <div class="card-header">
            <h3 class="card-title">আজ মোট পরীক্ষায় অংশগ্রহণ তালিকা</h3>

            <div class="card-tools">
            	{{-- <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addPackageModal" title="" rel="tooltip" data-original-title="পেমেন্ট যোগ করুন">
            		<i class="fas fa-clipboard-check"></i> নতুন পেমেন্ট
            	</button> --}}
              <div class="card-tools">
                {{-- <form class="form-inline form-group-lg" action="">
                  <div class="form-group">
                    <input type="search-param" class="form-control form-control-sm" placeholder="পরীক্ষায় অংশগ্রহণ খুঁজুন" id="search-param" required>
                  </div>
                  <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                    <i class="fas fa-search"></i> খুঁজুন
                  </button>
                </form> --}}
                
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <table class="table">
              <thead>
                <thead>
                  <th>পরীক্ষার নাম</th>
                  <th>ব্যবহারকারী</th>
                  <th>প্রাপ্ত নম্বর</th>
                  <th>সময়</th>
                </thead>
              </thead>
              <tbody>
                @foreach($examstoday as $exam)
                	<tr>
                    {{-- {{ $exam }} --}}
                    <td>
                      <a href="{{ route('dashboard.exams.add.question', $exam->exam->id) }}">{{ $exam->exam->name }}</a><br/>
                      <small>{{ $exam->course->name }}</small>
                    </td>
                    <td><a href="{{ route('dashboard.users.single', $exam->user->id) }}">{{ $exam->user->name }}</a></td>
                    <td>{{ $exam->marks }}</td>
                		<td>{{ date('F d, Y h:i A', strtotime($exam->created_at)) }}</td>
                	</tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        {{ $examstoday->links() }}
    </div>
@endsection

@section('third_party_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="module">

      $(document).on('click', '#search-button', function() {
        if($('#search-param').val() != '') {
          var urltocall = '{{ route('dashboard.payments') }}' +  '/' + $('#search-param').val();
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
            var urltocall = '{{ route('dashboard.payments') }}' +  '/' + $('#search-param').val();
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