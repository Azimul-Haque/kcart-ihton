@extends('layouts.app')
@section('title') ড্যাশবোর্ড | পরীক্ষা | {{ $exam->name }}@endsection

@section('third_party_stylesheets')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>
<style type="text/css">
  .select2-selection__choice{
      background-color: rgba(0, 123, 255) !important;
  }
</style>
@endsection

@section('content')
    @section('page-header') {{ $exam->name }} @endsection
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">প্রশ্নসমূহ ({{ $examquestions->count() }} টি প্রশ্ন)</h3>
          
                      <div class="card-tools">
                        <button type="submit" class="btn btn-danger btn-sm"  data-toggle="modal" data-target="#clearQuestionsModal">
                            <i class="fas fa-trash-alt"></i> ক্লিয়ার করুন
                        </button>
                        <a href="{{ route('dashboard.exams.add.question.from.others', $exam->id) }}" class="btn btn-info btn-sm"  {{-- data-toggle="modal" data-target="#addExamQuestionModal" --}}>
                            <i class="fas fa-copy"></i> অন্য প্রশ্নপত্র থেকে
                        </a>
                        {{-- <a href="{{ route('dashboard.exams.add.question.all', $exam->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-tasks"></i> প্রশ্ন হালনাগাদ করুন
                        </a> --}}
                        <button type="button" class="btn btn-warning btn-sm"  data-toggle="modal" data-target="#addExcelQuesitonModal" style="margin-left: 5px;">
                            <i class="fas fa-file-excel"></i> এক্সেল ফাইল আপলোড করুন
                        </button>
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table class="table" id="datatable">
                          <thead>
                              <tr>
                                  <th>প্রশ্ন</th>
                                  <th width="35%">অপশনসমূহ</th>
                                  <th width="15%">Action</th>
                              </tr>
                          </thead>
                          <tbody>
                          @foreach($examquestions as $examquestion)
                              <tr>
                                  <td>
                                      {!! $examquestion->question->question !!}<br/>
                                      {{-- <span class="badge bg-success">{{ $examquestion->question->topic->name }}</span> --}}
                                      <span class="badge bg-success">{{ $examquestion->question->topic->full_path }}</span>
                                  </td>
                                  <td>
                                    <small>
                                        @if($examquestion->question->answer == 1)
                                            <big><b>{{ $examquestion->question->option1 }}</b></big>, 
                                        @else
                                            {{ $examquestion->question->option1 }}, 
                                        @endif
                                        @if($examquestion->question->answer == 2)
                                            <big><b>{{ $examquestion->question->option2 }}</b></big>, 
                                        @else
                                            {{ $examquestion->question->option2 }}, 
                                        @endif
                                        @if($examquestion->question->answer == 3)
                                            <big><b>{{ $examquestion->question->option3 }}</b></big>, 
                                        @else
                                            {{ $examquestion->question->option3 }}, 
                                        @endif
                                        @if($examquestion->question->answer == 4)
                                            <big><b>{{ $examquestion->question->option4 }}</b></big>
                                        @else
                                            {{ $examquestion->question->option4 }}
                                        @endif
                                    </small>
                                  </td>
                                  <td align="right">
                                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteCategoryModal{{ $examquestion->id }}">
                                          <i class="far fa-trash-alt"></i>
                                      </button>
                                  </td>
                                  
                                  {{-- Remove Exam Question Modal Code --}}
                                  {{-- Remove Exam Question Modal Code --}}
                                  <!-- Modal -->
                                  <div class="modal fade" id="deleteCategoryModal{{ $examquestion->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true" data-backdrop="static">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger">
                                                <h5 class="modal-title" id="deleteCategoryModalLabel">প্রশ্ন অপসারণ</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                আপনি কি নিশ্চিতভাবে এই প্রশ্নটি অপসারণ করতে চান?<br/>
                                                <center>
                                                    <big><b>{!! $examquestion->question->question !!}</b></big><br/>
                                                </center>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                                <a href="{{ route('dashboard.exams.question.remove', [$exam->id, $examquestion->question->id]) }}" class="btn btn-danger">ডিলেট করুন</a>
                                            </div>
                                        </div>
                                      </div>
                                  </div>
                                  {{-- Remove Exam Question Modal Code --}}
                                  {{-- Remove Exam Question Modal Code --}}
                              </tr>
                          @endforeach
                          </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
            </div>
            <div class="col-md-3">
                {{-- <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">স্বয়ংক্রিয় প্রশ্ন প্রণয়ন</h3>
          
                      <div class="card-tools">
                          <button type="button" class="btn btn-warning btn-sm"  data-toggle="modal" data-target="#automaticQuestionSetModal">
                              <i class="fas fa-plus-circle"></i> প্রশ্ন প্রণয়ন
                          </button>
                      </div>
                    </div>
                    <div class="card-body">
                      বিষয়ভিত্তিক মান বণ্টন
                      <table class="table">
                        <thead>
                            <tr>
                                <th>টপিক</th>
                                <th>মোট প্রশ্ন সংখ্যা</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                        </tbody>
                      </table>
                    </div>
                  </div> --}}   
            </div>
        </div>
    </div>

    {{-- Clear Questions Modal Code --}}
    {{-- Clear Questions Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="clearQuestionsModal" tabindex="-1" role="dialog" aria-labelledby="clearQuestionsModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="clearQuestionsModalLabel">
                        প্রশ্ন ক্লিয়ার করুন (N.B: প্রশ্ন রিসেট হবে!)
                        <span id="questionupdatingnumbertag"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('dashboard.exams.question.clear') }}">
                    <div class="modal-body">
                        @csrf
                        আপনি কি নিশ্চিতভাবে প্রশ্নপত্রটি ক্লিয়ার করতে চান?
                        <input type="hidden" name="exam_id" value="{{ $exam->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                        <button type="submit" class="btn btn-danger">দাখিল করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Clear Questions Modal Code --}}
    {{-- Clear Questions Modal Code --}}

    
    {{-- Auto Question Set Modal Code --}}
    {{-- Auto Question Set Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="automaticQuestionSetModal" tabindex="-1" role="dialog" aria-labelledby="automaticQuestionSetModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="automaticQuestionSetModalLabel">
                        স্বয়ংক্রিয় প্রশ্ন প্রণয়ন 
                        <small class="badge badge-dark ml-2" id="total_selected_count">০টি প্রশ্ন সিলেক্ট করা হয়েছে</small>
                        <div id="selection_preview_container" class="mb-3 p-2 border rounded bg-light d-none">
                            <label class="text-sm font-weight-bold text-primary mb-2">আপনার বাছাইকৃত সাবটপিকসমূহ:</label>
                            <div id="selection_preview" class="d-flex flex-wrap">
                                </div>
                        </div>
                        <div class="small mb-2">
                            সিলেবাস: {{ $exam->syllabus }}
                        </div>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="addautoquestionform" action="{{ route('dashboard.exams.question.auto.fromsubs') }}">
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        @csrf
                        <input type="hidden" name="exam_id" value="{{ $exam->id }}">

                        <div class="row align-items-end">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="main_topic">মূল টপিক সিলেক্ট করুন</label>
                                    <select class="form-control select2" id="main_topic_selector" style="width: 100%;">
                                        <option value="" selected disabled>-- টপিক সিলেক্ট করুন --</option>
                                        @foreach($mainTopics as $topic)
                                            <option value="{{ $topic->id }}" data-name="{{ $topic->name }}">
                                                {{ $topic->name }} (মোট প্রশ্ন: {{ $topic->total_questions_sum }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="button" id="add_topic_btn" class="btn btn-primary btn-block">
                                        <i class="fas fa-plus"></i> সাবটপিক লোড করুন
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div id="topics_wrapper">
                            <div class="text-center py-5 empty-msg">
                                <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                                <p class="text-muted">এখনো কোনো টপিক যোগ করা হয়নি। উপরের ড্রপডাউন থেকে টপিক সিলেক্ট করে "লোড করুন" বাটনে ক্লিক করুন।</p>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                        <button type="submit" class="btn btn-success shadow">
                            <i class="fas fa-check-circle"></i> প্রশ্ন সেট জেনারেট করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Auto Question Set Modal Code --}}
    {{-- Auto Question Set Modal Code --}}

    {{-- Upload Excel Modal Code --}}
    {{-- Upload Excel Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addExcelQuesitonModal" tabindex="-1" role="dialog" aria-labelledby="addExcelQuesitonModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="addExcelQuesitonModalLabel">Excel আপলোড (প্রশ্ন)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.exams.questions.excel.store', $exam->id) }}" enctype="multipart/form-data">
              @csrf
              <div class="modal-body">
                  <div class="form-group mb-3">
                      <label class="font-weight-bold text-dark small mb-1">Excel ফাইল নির্বাচন করুন (Max 10MB)</label>
                      <div class="input-group">
                          <div class="custom-file">
                              <input type="file" id="file" name="file" class="custom-file-input" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                              <label class="custom-file-label" for="file">ফাইল খুঁজুন...</label>
                          </div>
                      </div>
                      <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle"></i> শুধুমাত্র সঠিক ডেমো ফরম্যাটের .xls, .xlsx বা .csv ফাইল সাপোর্ট করবে। <a href="{{ asset('files/Question Upload Template.xlsx') }}" title="" download>ডেমো ডাউনলোড করতে ক্লিক করুন <i class="fas fa-download"></i></a></small>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                <button type="submit" class="btn btn-warning">আপলোড করুন</button>
              </div>
          </form>
        </div>
      </div>
    </div>
    {{-- Upload Excel Modal Code --}}
    {{-- Upload Excel Modal Code --}}
@endsection

@section('third_party_scripts')
<script>
    $('.multiple-select').select2({
      // theme: 'bootstrap4',
    });
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/underscore@1.13.4/underscore-umd-min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script>
  $(document).ready(function () {
    bsCustomFileInput.init();
  });
</script>
<script>
    $(document).ready(function() {
        let loadedTopics = []; 

        $('#add_topic_btn').on('click', function() {
            let selectedOption = $('#main_topic_selector').find(":selected");
            let topicId = selectedOption.val();
            let topicName = selectedOption.data('name');

            if (!topicId) {
                alert('অনুগ্রহ করে একটি টপিক সিলেক্ট করুন।');
                return;
            }

            if (loadedTopics.includes(topicId)) {
                alert('এই টপিকটি অলরেডি নিচে যোগ করা হয়েছে।');
                return;
            }

            let btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> লোড হচ্ছে...');
            $('.empty-msg').hide();

            // প্রাথমিক কল (লেভেল ২ এর জন্য)
            loadSubtopicData(topicId, topicName, true);
        });

        // ডাটা লোড করার কমন ফাংশন
        function loadSubtopicData(targetId, targetName, isMainCard = false) {
            $.ajax({
                url: "{{ route('dashboard.exams.subtopics') }}",
                type: "GET",
                data: { target_id: targetId },
                success: function(data) {
                    if ($.isEmptyObject(data)) {
                        alert('কোনো সাবটপিক পাওয়া যায়নি।');
                        return;
                    }

                    if (isMainCard) {
                        loadedTopics.push(targetId);
                        renderMainCard(targetId, targetName, data);
                    } else {
                        renderDrillDown(targetId, data);
                    }
                },
                complete: function() {
                    $('#add_topic_btn').prop('disabled', false).html('<i class="fas fa-plus"></i> সাবটপিক লোড করুন');
                }
            });
        }

        // মেইন কার্ড রেন্ডার (প্রাচীন যুগ, মধ্য যুগ ইত্যাদি)
        function renderMainCard(id, name, groups) {
            let html = `
                <div class="card card-warning card-outline mb-4 shadow" id="main_card_${id}">
                    <div class="card-header py-2">
                        <h3 class="card-title text-bold"><i class="fas fa-book-reader mr-2"></i> ${name}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool remove-main-topic" data-id="${id}"><i class="fas fa-times text-danger"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-2" id="wrapper_${id}">`;

            $.each(groups, function(subName, item) {
                html += generateRowHtml(item, subName);
            });

            html += `</div></div>`;
            $('#topics_wrapper').append(html);
        }

        // ড্রিল ডাউন রেন্ডার (চর্যাপদ এর ভেতর আরও গভীরে)
        function renderDrillDown(parentId, data) {
            let container = $(`#drill_container_${parentId}`);
            let html = '<div class="ml-4 border-left pl-3 mt-2 bg-light p-2 rounded">';
            $.each(data, function(subName, item) {
                html += generateRowHtml(item, subName);
            });
            html += '</div>';
            container.html(html);
        }

        // রো (Row) তৈরির কমন লজিক
        function generateRowHtml(item, name) {
            let drillBtn = item.has_children ? 
                `<button type="button" class="btn btn-xs btn-outline-primary ml-2 load-more-drill" data-id="${item.id}" data-name="${name}">
                    আরও সাবটপিক লোড করুন <i class="fas fa-arrow-down ml-1"></i>
                </button>` : '';

            let idsString = (item.all_ids && item.all_ids.length > 0) ? item.all_ids.join(',') : item.id;

            return `
                <div class="subtopic-row-wrapper mb-2" id="row_wrapper_${item.id}">
                    <div class="p-2 border rounded bg-white d-flex justify-content-between align-items-center shadow-sm">
                        <div style="max-width: 60%;">
                            <span class="d-block text-sm font-weight-bold text-dark">${name}</span>
                            <div class="mt-1">
                                <span class="badge badge-warning mr-1" style="font-size: 11px;">নিজস্ব প্রশ্ন: ${item.direct_q}</span>
                                <span class="badge badge-info" style="font-size: 11px;">মোট (সবসহ): ${item.total_q}</span>
                                ${drillBtn}
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <div class="text-center mr-2">
                                <input type="number" name="only_own[${item.id}]" 
                                       class="form-control form-control-sm q-count-input" 
                                       data-name="${name} (নিজস্ব)" 
                                       style="width: 55px; border-color: #28a745;" 
                                       min="0" max="${item.direct_q}" value="0">
                                <small class="text-success d-block" style="font-size: 11px;">নিজস্ব</small>
                            </div>

                            <div class="text-center">
                                <input type="number" name="topic_groups[${idsString}]" 
                                       class="form-control form-control-sm q-count-input" 
                                       data-name="${name} (সবসহ)" 
                                       style="width: 55px; border-color: #6c757d;" 
                                       min="0" max="${item.total_q}" value="0">
                                <small class="text-muted d-block" style="font-size: 11px;">সবসহ</small>
                            </div>
                        </div>
                    </div>
                    <div class="drill-target" id="drill_container_${item.id}"></div>
                </div>`;
        }

        // "আরও সাবটপিক" বাটনের ইভেন্ট
        $(document).on('click', '.load-more-drill', function() {
            let btn = $(this);
            let id = btn.data('id');
            let name = btn.data('name');
            btn.html('<i class="fas fa-spinner fa-spin"></i> লোড হচ্ছে...').prop('disabled', true);
            loadSubtopicData(id, name, false);
            btn.fadeOut(); // লোড হয়ে গেলে বাটন সরিয়ে ফেলবে
        });

        // মেইন টপিক রিমুভ
        $(document).on('click', '.remove-main-topic', function() {
            let id = $(this).data('id').toString();
            $(`#main_card_${id}`).fadeOut(300, function() {
                $(this).remove();
                loadedTopics = loadedTopics.filter(item => item !== id);
                if(loadedTopics.length === 0) $('.empty-msg').show();
                updateSelectionPreview();
            });
        });

        $(document).on('input', '.q-count-input', function() {
            updateSelectionPreview();
        });

        function updateSelectionPreview() {
            let total = 0;
            let previewHtml = '';
            let anySelected = false;

            $('.q-count-input').each(function() {
                let count = parseInt($(this).val()) || 0;
                total += count;
                if (count > 0) {
                    anySelected = true;
                    let name = $(this).data('name');
                    previewHtml += `<span class="badge badge-info m-1 px-2 py-1 shadow-sm border" style="font-size: 11px;">
                                    ${name} <span class="badge badge-light ml-1">${count}</span></span>`;
                }
            });

            $('#total_selected_count').text(total + ' টি প্রশ্ন সিলেক্ট করা হয়েছে');
            if (anySelected) {
                $('#selection_preview_container').removeClass('d-none');
                $('#selection_preview').html(previewHtml);
            } else {
                $('#selection_preview_container').addClass('d-none');
            }
        }
    });
</script>

<script>
    $("#datatable").DataTable({
        "responsive": true, "lengthChange": true, "autoWidth": false, info: false, "pageLength": 10,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });
    $("#datatablemodal").DataTable({
        "responsive": true, "lengthChange": true, "autoWidth": false, info: false, "pageLength": 10,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    {{-- function checkboxquestion(id) {
        if($('#check' + id)[0].checked){
            var hiddencheckarray = $('#hiddencheckarray').val();
            // console.log(hiddencheckarray);
            var updatedvalue = hiddencheckarray + (!hiddencheckarray ? '' : ',') + id;
            $('#hiddencheckarray').val(updatedvalue);
            console.log(updatedvalue);
            var array = updatedvalue.split(',');
            $('#questionupdatingnumber').text('প্রশ্ন সংখ্যাঃ ' + array.length.toString());
            $('#questionupdatingnumbertag').text('প্রশ্ন সংখ্যাঃ ' + array.length.toString());
            $('#questionupdatingnumberauto').text('প্রশ্ন সংখ্যাঃ ' + array.length.toString());
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
            $('#questionupdatingnumber').text('প্রশ্ন সংখ্যাঃ ' + updatedarray.length);
            $('#questionupdatingnumbertag').text('প্রশ্ন সংখ্যাঃ ' + updatedarray.length);
            $('#questionupdatingnumberauto').text('প্রশ্ন সংখ্যাঃ ' + updatedarray.length);
        }
    } --}}
    
    
  </script>

@endsection