@extends('layouts.app')
@section('title') ড্যাশবোর্ড | পরীক্ষা | {{ $exam->name }} @endsection

@section('third_party_stylesheets')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>
<style type="text/css">
  .select2-selection__choice{
      background-color: rgba(0, 123, 255) !important;
  }
</style>
@endsection

@section('content')
    @section('page-header') <b><a href="{{ route('dashboard.exams.add.question', $exam->id) }}">{{ $exam->name }} <small>({{ $examquestions->count() }} টি প্রশ্ন)</small></a></b> / প্রশ্নব্যাংক থেকে বাছাই করুন @endsection
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title"><a href="{{ route('dashboard.exams.add.question.all', $exam->id) }}">প্রশ্নব্যাংক (মোটঃ {{ $totalquestions }})</a></h3>
                      <div class="card-tools">
                          <form class="form-inline form-group-lg" action="">
                            <div class="form-group">
                              <input type="search-param" class="form-control form-control-sm" placeholder="প্রশ্ন খুঁজুন" id="search-param" required>
                            </div>
                            <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                              <i class="fas fa-search"></i> খুঁজুন
                            </button>
                          </form>
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <form method="post" id="addquestionform" action="{{ route('dashboard.exams.question.store') }}">
                        <button type="submit" class="btn btn-primary btn-sm" style="float: right; margin: 10px 10px 10px 0px;">
                          <i class="fas fa-folder-plus"></i> সংরক্ষণ করুন
                        </button>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th width="40%">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @csrf
                                @php
                                    $examquestionidarray = [];
                                    foreach ($examquestions as $examquestion) {
                                        $examquestionidarray[] = $examquestion->question_id;
                                    }
                                    $questionchecktext = implode(",", $examquestionidarray);
                                    $currentcheck = []
                                @endphp
                                <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                                <input type="hidden" id="hiddencheckarray" name="hiddencheckarray" value="">
                                {{-- <input type="hidden" id="hiddencheckarray" name="hiddencheckarray" value="{{ $questionchecktext }}"> --}}
                                @foreach($questions as $question)
                                  <tr>
                                    <td>
                                        <div class="icheck-primary icheck-inline" style="float: left;">
                                            <input type="checkbox" onchange="checkboxquestion({{ $question->id }})" id="check{{ $question->id }}" name="questioncheck[]" value="{{ $question->id }}" 
                                            @if(in_array($question->id, $examquestionidarray)) checked="" @endif
                                            />
                                            <label for="check{{ $question->id }}"> </label>
                                        </div>
                                    </td>
                                    <td>
                                        {!! $question->question !!}<br/>
                                        <span class="badge bg-success">{{ $question->topic->name }}</span>
                                        <span class="badge bg-info">{{ $question->difficulty == 1 ? 'সহজ' : ($question->difficulty == 2 ? 'মধ্যম' : 'কঠিন') }}</span>
                                        
                                    </td>
                                    <td>
                                      @if($question->answer == 1)
                                          <big><b>{{ $question->option1 }}</b></big>, 
                                      @else
                                          {{ $question->option1 }}, 
                                      @endif
                                      @if($question->answer == 2)
                                          <big><b>{{ $question->option2 }}</b></big>, 
                                      @else
                                          {{ $question->option2 }}, 
                                      @endif
                                      @if($question->answer == 3)
                                          <big><b>{{ $question->option3 }}</b></big>, 
                                      @else
                                          {{ $question->option3 }}, 
                                      @endif
                                      @if($question->answer == 4)
                                          <big><b>{{ $question->option4 }}</b></big>
                                      @else
                                          {{ $question->option4 }}
                                      @endif
                                    </td>
                                  </tr>
                                  @php
                                    if(in_array($question->id, $examquestionidarray)) {
                                      $currentcheck[] = $question->id;
                                    }
                                  @endphp
                                @endforeach
                                @php
                                    $currentchecktext = implode(",", $currentcheck);
                                @endphp
                                <input type="hidden" id="currentchecktext" name="currentchecktext" value="{{ $currentchecktext }}">
                            </tbody>
                        </table>
                      </form>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  {{ $questions->links() }}
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">টপিকসমূহ</h3>
          
                      <div class="card-tools">
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th>Topic</th>
                              </tr>
                          </thead>
                          <tbody>
                          @foreach($topics as $topic)
                              <tr>
                                  <td>
                                    <a href="{{ route('dashboard.exams.add.question.topic', [$topic->id, $exam->id]) }}">
                                      {{ $topic->name }} <small>({{ $topic->questions->count() }} টি প্রশ্ন)</small>
                                      <span class="badge bg-primary"><i class="fas fa-bolt"></i> {{ $topic->participation }}</span>
                                    </a>
                                  </td>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/underscore@1.13.4/underscore-umd-min.js"></script>
<script>
    $('.multiple-select').select2({
      // theme: 'bootstrap4',
    });
    // ClassicEditor
    //     .create( document.querySelector( '.summernote' ) )
    //     .then( editor => {
    //             console.log( editor );
    //     } )
    //     .catch( error => {
    //             console.error( error );
    //     } );
</script>
<script type="text/javascript">
    $(document).ready( function() {
      $(document).on('click', '#search-button', function() {
        if($('#search-param').val() != '') {
          var urltocall = '{{ route('dashboard.index') }}' +  '/exams/add/question/bank/search/{{ $exam->id }}/' + $('#search-param').val();
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
            var urltocall = '{{ route('dashboard.index') }}' +  '/exams/add/question/bank/search/{{ $exam->id }}/' + $('#search-param').val();
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
    });

    $('#hiddencheckarray').val('{{ $currentchecktext }}');
    console.log($('#hiddencheckarray').val());

    function checkboxquestion(id) {
        if($('#check' + id)[0].checked){
            var hiddencheckarray = $('#hiddencheckarray').val();
            // console.log(hiddencheckarray);
            var updatedvalue = hiddencheckarray + (!hiddencheckarray ? '' : ',') + id;
            $('#hiddencheckarray').val(updatedvalue);
            console.log(updatedvalue);
            var array = updatedvalue.split(',');
            // $('#questionupdatingnumber').text('প্রশ্ন সংখ্যাঃ ' + array.length.toString());
            // $('#questionupdatingnumbertag').text('প্রশ্ন সংখ্যাঃ ' + array.length.toString());
            // $('#questionupdatingnumberauto').text('প্রশ্ন সংখ্যাঃ ' + array.length.toString());
            Toast.fire({
                icon: 'warning',
                title: 'প্রশ্ন সংখ্যাঃ ' + array.length.toString()
            })
            // console.log(array.length);
        } else {
            var hiddencheckarray = $('#hiddencheckarray').val();
            var uncheckedarray = hiddencheckarray.split(',');
            var updatedarray = _.without(uncheckedarray, id.toString());
            // console.log(updatedarray);
            var newupdatedvalue = '';
            for(var i=0; i<updatedarray.length; i++) {
                newupdatedvalue = newupdatedvalue + (!newupdatedvalue ? '' : ',') + updatedarray[i];
            };
            $('#hiddencheckarray').val(newupdatedvalue);
            console.log(newupdatedvalue);
            Toast.fire({
                icon: 'warning',
                title: 'প্রশ্ন সংখ্যাঃ ' + updatedarray.length.toString()
            })
            // $('#questionupdatingnumber').text('প্রশ্ন সংখ্যাঃ ' + updatedarray.length);
            // $('#questionupdatingnumbertag').text('প্রশ্ন সংখ্যাঃ ' + updatedarray.length);
            // $('#questionupdatingnumberauto').text('প্রশ্ন সংখ্যাঃ ' + updatedarray.length);
        }
    }
</script>
@endsection