@extends('layouts.app')
@section('title') ড্যাশবোর্ড | {{ $exam->name }} @endsection

@section('third_party_stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/jquery-for-dp.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
@endsection

@section('content')
    @section('page-header') {{ $exam->name }} @endsection
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">মেধাতালিকা</h3>
          
                      <div class="card-tools">
                          
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th>নাম</th>
                                  <th>প্রাপ্ত নম্বর</th>
                                  <th>র‍্যাংক</th>
                                  <th>কোর্স</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                          @foreach($exam->meritlists->sortByDesc('marks') as $meritlist)
                              <tr>
                                  <td>
                                    <a href="{{ route('dashboard.users.single', $meritlist->user->id) }}">{{ $meritlist->user->name }}</a><br/>
                                    <small class="text-black-50">{{ $meritlist->user->mobile }}</small> 
                                  </td>
                                  <td>{{ $meritlist->marks }}</td>
                                  <td>{{ $meritlist->rank }}</td>
                                  <td><small>{{ $meritlist->course->name }}</small></td>
                                  <td></td>
                              </tr>
                          @endforeach
                          </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
            </div>
        </div>



@endsection

@section('third_party_scripts')
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

{{-- <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $("#available_from").datepicker({
      format: 'MM dd, yyyy',
      todayHighlight: true,
      autoclose: true,
      container:'#addExamModal',
    });
    $("#available_to").datepicker({
      format: 'MM dd, yyyy',
      todayHighlight: true,
      autoclose: true,
      container:'#addExamModal',
    });
</script> --}}
@endsection