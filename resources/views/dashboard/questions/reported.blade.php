@extends('layouts.app')
@section('title') ড্যাশবোর্ড | রিপোর্টেড প্রশ্নসমূহ @endsection

@section('third_party_stylesheets')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<style type="text/css">
  .select2-selection__choice{
      background-color: rgba(0, 123, 255) !important;
  }
</style>
@endsection

@section('content')
    @section('page-header') রিপোর্টেড প্রশ্নসমূহ @endsection
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">রিপোর্টেড প্রশ্নসমূহ (মোটঃ {{ $totalreportedquestions }})</h3>
          
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
                      <table class="table">
                          <thead>
                              <tr>
                                  <th width="30%">প্রশ্ন</th>
                                  <th>উত্তর</th>
                                  <th width="20%">অপশনসমূহ</th>
                                  <th>ব্যবহারকারী</th>
                                  <th>বার্তা</th>
                                  @if(Auth::user()->role == 'admin')
                                    <th width="15%">হালনাগাদ সময়</th>
                                  @endif
                                  <th width="10%">Action</th>
                              </tr>
                          </thead>
                          <tbody>
                          @foreach($reportedquestions as $reportedquestion)
                              <tr>
                                  <td>
                                      {!! $reportedquestion->question->question !!}<br/>
                                      <span class="badge bg-success">{{ $reportedquestion->question->topic->name }}</span>
                                      <span class="badge bg-info">{{ $reportedquestion->question->difficulty == 1 ? 'সহজ' : ($reportedquestion->question->difficulty == 2 ? 'মধ্যম' : 'কঠিন') }}</span>
                                      @foreach($reportedquestion->question->tags as $tag)
                                        <span class="badge bg-primary">{{ $tag->name }}</span>
                                      @endforeach
                                  </td>
                                  <td>{{ $reportedquestion->question->answer }}</td>
                                  <td>{{ $reportedquestion->question->option1 }}, {{ $reportedquestion->question->option2 }}, {{ $reportedquestion->question->option3 }}, {{ $reportedquestion->question->option4 }}</td>
                                  <td>
                                    <a href="{{ route('dashboard.users.single', $reportedquestion->user->id) }}">{{ $reportedquestion->user->name }}</a>
                                  </td>
                                  <td>{{ $reportedquestion->message }}</td>
                                  @if(Auth::user()->role == 'admin')
                                    <td>
                                      <small>
                                        @if($reportedquestion->question->updated_at > $reportedquestion->created_at)
                                          <b>{{ date('d M, Y h:i a', strtotime($reportedquestion->question->updated_at)) }}
                                        @endif
                                      </small><br/>
                                      <small>{{ date('d M, Y h:i a', strtotime($reportedquestion->created_at)) }}</small>
                                    </td>
                                  @endif                   
                                  <td>
                                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editQuestionModal{{ $reportedquestion->question->id }}">
                                          <i class="far fa-edit"></i>
                                      </button>
                                      {{-- Edit Question Modal Code --}}
                                      {{-- Edit Question Modal Code --}}
                                      <!-- Modal -->
                                      <div class="modal fade" id="editQuestionModal{{ $reportedquestion->question->id }}" tabindex="-1" role="dialog" aria-labelledby="editQuestionModalLabel" aria-hidden="true" data-backdrop="static">
                                          <div class="modal-dialog modal-lg" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header bg-success">
                                                <h5 class="modal-title" id="editQuestionModalLabel">প্রশ্ন হালনাগাদ</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>
                                              <form method="post" action="{{ route('dashboard.questions.update', $reportedquestion->question->id) }}" enctype='multipart/form-data'>
                                                <div class="modal-body">
                                                      @csrf
                                                      <textarea id="question{{ $reportedquestion->question->id }}" name="question" required>{!! $reportedquestion->question->question !!}</textarea><br/>
                                                      {{-- <div class="input-group mb-3">
                                                          <input type="text" name="question" class="form-control" value="{{ $reportedquestion->question->question }}" placeholder="প্রশ্ন" required>
                                                          <div class="input-group-append">
                                                              <div class="input-group-text"><span class="far fa-question-circle"></span></div>
                                                          </div>
                                                      </div> --}}
                                                      <div class="row">
                                                          <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="option1" value="{{ $reportedquestion->question->option1 }}" class="form-control" placeholder="অপশন ১" required>
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><small>অপশন ১</small></div>
                                                                </div>
                                                            </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="option2" value="{{ $reportedquestion->question->option2 }}" class="form-control" placeholder="অপশন ২" required>
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><small>অপশন ২</small></div>
                                                                </div>
                                                            </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="option3" value="{{ $reportedquestion->question->option3 }}" class="form-control" placeholder="অপশন ৩" required>
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><small>অপশন ৩</small></div>
                                                                </div>
                                                            </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                            <div class="input-group mb-3">
                                                                <input type="text" name="option4" value="{{ $reportedquestion->question->option4 }}" class="form-control" placeholder="অপশন ৪" required>
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><small>অপশন ৪</small></div>
                                                                </div>
                                                            </div>
                                                          </div>
                                                      </div>
                                                      <div class="row">
                                                        <div class="col-md-6">
                                                          <div class="input-group mb-3">
                                                              <select name="answer" class="form-control" required>
                                                                  <option selected="" disabled="" value="">সঠিক উত্তর</option>
                                                                  <option value="1" @if($reportedquestion->question->answer == 1) selected @endif>অপশন ১</option>
                                                                  <option value="2" @if($reportedquestion->question->answer == 2) selected @endif>অপশন ২</option>
                                                                  <option value="3" @if($reportedquestion->question->answer == 3) selected @endif>অপশন ৩</option>
                                                                  <option value="4" @if($reportedquestion->question->answer == 4) selected @endif>অপশন ৪</option>
                                                              </select>
                                                              <div class="input-group-append">
                                                                  <div class="input-group-text"><span class="far fa-check-circle"></span></div>
                                                              </div>
                                                          </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                          <select name="tags_ids[]" class="form-control multiple-select" multiple="multiple" data-placeholder="ট্যাগ">
                                                            @php
                                                              $tag_array = [];
                                                              foreach($reportedquestion->question->tags as $tag) {
                                                                $tag_array[] = $tag->id;
                                                              } 
                                                            @endphp
                                                            @foreach ($tags as $tag)
                                                                <option value="{{ $tag->id }}" @if(in_array($tag->id, $tag_array)) selected @endif>{{ $tag->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        </div>
                                                      </div>
                                                      <div class="row">
                                                          <div class="col-md-6">
                                                              <div class="input-group mb-3">
                                                                  <select name="difficulty" class="form-control" required>
                                                                      <option selected="" disabled="" value="">ডিফিকাল্টি লেভেল</option>
                                                                      <option value="1" @if($reportedquestion->question->difficulty == 1) selected @endif>সহজ</option>
                                                                      <option value="2" @if($reportedquestion->question->difficulty == 2) selected @endif>মধ্যম</option>
                                                                      <option value="3" @if($reportedquestion->question->difficulty == 3) selected @endif>কঠিন</option>
                                                                  </select>
                                                                  <div class="input-group-append">
                                                                      <div class="input-group-text"><span class="fas fa-star-half-alt"></span></div>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                              <div class="input-group mb-3">
                                                                  <select name="topic_id" class="form-control" required>
                                                                      <option selected="" disabled="" value="">টপিক (বিষয়)</option>
                                                                      @foreach ($topics as $topic)
                                                                          <option value="{{ $topic->id }}" @if($reportedquestion->question->topic_id == $topic->id) selected @endif>{{ $topic->name }}</option>
                                                                      @endforeach
                                                                  </select>
                                                                  <div class="input-group-append">
                                                                      <div class="input-group-text"><span class="fas fa-bookmark"></span></div>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <div class="col-md-6">
                                                              <div class="form-group ">
                                                                  <label for="image">ছবি (প্রয়োজনে)</label>
                                                                  <input type="file" id="image{{ $reportedquestion->question->id }}" name="image" accept="image/*">
                                                              </div>
                                                              <center>
                                                                  <?php
                                                                    if($reportedquestion->question->questionimage) {
                                                                        $currentimage = asset('images/questions/' . $reportedquestion->question->questionimage->image);
                                                                    } else {
                                                                        $currentimage = asset('images/placeholder.png');
                                                                    }
                                                                  ?>
                                                                  <img src="{{ $currentimage }}" id='img-upload{{ $reportedquestion->question->id }}' style="width: 250px; height: auto;" class="img-responsive" />
                                                              </center>
                                                          </div>
                                                          <div class="col-md-6">
                                                              <label for="explanation">ব্যাখ্যা (প্রয়োজনে)</label><br/>
                                                              <textarea class="form-control summernote" name="explanation" id="explanation" placeholder="ব্যাখ্যা" style="width: 100%; height: 220px;">{{ $reportedquestion->question->questionexplanation ? $reportedquestion->question->questionexplanation->explanation : '' }}</textarea>
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

                                      <script>
                                          $('#question{{ $reportedquestion->question->id }}').summernote({
                                            // callbacks: {
                                            //   onChange: function(contents, $editable) {
                                            //     $("textarea#content").html(contents);
                                            //   }
                                            // },
                                            dialogsInBody: true,
                                            placeholder: 'কন্টেন্ট লিখুন...',
                                            tabsize: 3,
                                            height: 150,
                                            toolbar: [
                                              ['style', ['style']],
                                              ['font', ['bold', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript']],
                                              ['color', ['color']],
                                              ['para', ['ul', 'ol', 'paragraph']],
                                              ['table', ['table']],
                                              ['insert', ['link', 'picture', 'video']],
                                              ['view', ['fullscreen', 'codeview', 'help']]
                                            ]
                                          });
                                      </script>
<script type="text/javascript">
    $(document).ready( function() {
      $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
      });

      $('.btn-file :file').on('fileselect', function(event, label) {
          var input = $(this).parents('.input-group').find(':text'),
              log = label;
          if( input.length ) {
              input.val(log);
          } else {
              if( log ) alert(log);
          }
      });
      function readURL(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              reader.onload = function (e) {
                  $('#img-upload{{ $reportedquestion->question->id }}').attr('src', e.target.result);
              }
              reader.readAsDataURL(input.files[0]);
          }
      }
      $("#image{{ $reportedquestion->question->id }}").change(function(){
          readURL(this);
          var filesize = parseInt((this.files[0].size)/1024);
          if(filesize > 10000) {
            $("#image{{ $reportedquestion->question->id }}").val('');
            // toastr.warning('File size is: '+filesize+' Kb. try uploading less than 300Kb', 'WARNING').css('width', '400px;');
            Toast.fire({
                icon: 'warning',
                title: 'File size is: '+filesize+' Kb. try uploading less than 300Kb'
            })
            setTimeout(function() {
            $("#img-upload{{ $reportedquestion->question->id }}").attr('src', '{{ asset('images/placeholder.png') }}');
            }, 1000);
          }
      });

    });
</script>
                                      {{-- Edit Question Modal Code --}}
                                      {{-- Edit Question Modal Code --}}
                                      
                                      <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#deleteReport{{ $reportedquestion->id }}">
                                        <i class="fas fa-clipboard-check"></i>
                                      </button>
                                          {{-- Delete Modal Code --}}
                                          {{-- Delete Modal Code --}}
                                          <!-- Modal -->
                                          <div class="modal fade" id="deleteReport{{ $reportedquestion->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteReportLabel" aria-hidden="true" data-backdrop="static">
                                            <div class="modal-dialog" role="document">
                                              <div class="modal-content">
                                                <div class="modal-header bg-warning">
                                                  <h5 class="modal-title" id="deleteReportLabel">সমাধান করা হয়েছে?</h5>
                                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                  </button>
                                                </div>
                                                <div class="modal-body">
                                                  আপনি কি নিশ্চিতভাবে এই রিপোর্টটি সমাধান করতে চান?<br/><br/>
                                                  <b>{!! $reportedquestion->question->question !!}</b><br/>
                                                  
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                                  <form method="GET" action="{{ route('dashboard.questions.reported.delete', $reportedquestion->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning">দাখিল করুন</button>
                                                  </form>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          {{-- Delete Modal Code --}}
                                          {{-- Delete Modal Code --}}
                                  </td>
                              </tr>
                          @endforeach
                          </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  {{ $reportedquestions->links() }}
            </div>
        </div>
@endsection

@section('third_party_scripts')
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
          // var tempsearch = $('#search-param').val().replace(/\\|\//g, '%');
          // console.log(tempsearch);
          var urltocall = '{{ route('dashboard.questions.reported') }}' +  '/' + $('#search-param').val().replace(/\\|\//g, '%');
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
            var urltocall = '{{ route('dashboard.questions.reported') }}' +  '/' + $('#search-param').replace(/\\|\//g, '%');
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

      $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
      });

      $('.btn-file :file').on('fileselect', function(event, label) {
          var input = $(this).parents('.input-group').find(':text'),
              log = label;
          if( input.length ) {
              input.val(log);
          } else {
              if( log ) alert(log);
          }
      });
      function readURL(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              reader.onload = function (e) {
                  $('#img-upload').attr('src', e.target.result);
              }
              reader.readAsDataURL(input.files[0]);
          }
      }
      $("#image").change(function(){
          readURL(this);
          var filesize = parseInt((this.files[0].size)/1024);
          if(filesize > 10000) {
            $("#image").val('');
            // toastr.warning('File size is: '+filesize+' Kb. try uploading less than 300Kb', 'WARNING').css('width', '400px;');
            Toast.fire({
                icon: 'warning',
                title: 'File size is: '+filesize+' Kb. try uploading less than 300Kb'
            })
            setTimeout(function() {
            $("#img-upload").attr('src', '{{ asset('images/placeholder.png') }}');
            }, 1000);
          }
      });
    });
</script>
@endsection