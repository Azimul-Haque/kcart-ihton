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

  /* সিলেক্টবক্সের হাইট অটো করা যাতে দুই লাইন দেখা যায় */
  .select2-container--default .select2-selection--single {
      height: auto !important;
      padding: 6px !important;
  }

  /* ড্রপডাউনের প্রতিটি আইটেমের মাঝে হালকা গ্যাপ */
  .select2-results__option {
      border-bottom: 1px solid #f9f9f9;
  }
</style>
@endsection

@section('content')
    @section('page-header') <b><a href="{{ route('dashboard.exams.add.question', $exam->id) }}">{{ $exam->name }} <small>({{ $examquestions->count() }} টি প্রশ্ন)</small></a></b> / প্রশ্নপত্র থেকে বাছাই করুন
    @endsection
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">প্রশ্নপত্র থেকে যোগ করুন</h3>
                      <div class="card-tools">
                          
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <select class="form-control select2-custom" id="questionsetselect" data-placeholder="পরীক্ষার নাম">
                        <option value="" selected disabled>পরীক্ষার নাম</option>
                        {{-- @foreach ($exams as $foreachexam)
                            <option value="{{ $foreachexam->name }},{{ $foreachexam->id }}">{{ $foreachexam->name }}
                              <span style="font-size: 10px!; color: #bbb;">({{ $foreachexam->examcategory->name }})
                                @if($foreachexam->courseexams->count() > 0)
                                    <div>
                                        @foreach($foreachexam->courseexams as $index => $ce)
                                            @if($ce->course != null)
                                                <span>{{ $ce->course->name ?? 'N/A' }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                              </span>
                            </option>
                        @endforeach --}}
                        @foreach ($exams as $foreachexam)
                            @php
                                // কোর্সের নামগুলো কমা দিয়ে যুক্ত করা হচ্ছে
                                $courses = $foreachexam->courseexams
                                    ->map(function($ce) { return $ce->course->name ?? 'N/A'; })
                                    ->implode(', ');
                                    
                                // তারিখ ফরম্যাট করা (যেমন: ১২ জুন, ২০২৪)
                                $date = $foreachexam->available_from ? \Carbon\Carbon::parse($foreachexam->available_from)->format('d M, Y') : 'N/A';
                            @endphp

                            <option value="{{ $foreachexam->name }},{{ $foreachexam->id }}" 
                                    data-category="{{ $foreachexam->examcategory->name ?? 'N/A' }}"
                                    data-courses="{{ $courses }}"
                                    data-date="{{ $date }}">
                                {{ $foreachexam->name }}
                            </option>
                        @endforeach
                      </select><br/>
                      <form method="post" action="{{ route('dashboard.exams.question.from.others.store', $exam->id) }}">
                        @csrf
                        <table id="selectedquestionlist"></table><br/>
                        <input type="hidden" id="otherexamids" name="otherexamids">
                        <button type="submit" class="btn btn-success" style="float: right;">দাখিল করুন</button>
                      </form>
                    </div>
                    <!-- /.card-body -->
                  </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">সিলেবাস</h3>
          
                      <div class="card-tools">

                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body ">
                      {{ $exam->syllabus }}
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
    var otherexamids = [];
    $('.select2').select2({
      // theme: 'bootstrap4',
    });
    $('.select2-custom').select2({
        templateResult: formatExamWithDetails,
        templateSelection: formatExamWithDetails,
        escapeMarkup: function(m) { return m; }
    });

    function formatExamWithDetails(state) {
        if (!state.id) return state.text;

        // ডাটা অ্যাট্রিবিউট থেকে তথ্য সংগ্রহ
        var category = $(state.element).data('category');
        var courses = $(state.element).data('courses');
        var date = $(state.element).data('date');

        // মডার্ন মাল্টি-লাইন লেআউট
        return $(
            '<div style="display: flex; flex-direction: column; padding: 4px 0;">' +
                // মেইন পরীক্ষার নাম (বড়)
                '<span style="font-weight: bold; font-size: 14px; color: #333;">' + state.text + '</span>' +
                
                // সাব-টেক্সট (ছোট, গ্রে এবং নিচে)
                '<div style="font-size: 11px; color: #888; margin-top: 2px; line-height: 1.4;">' +
                    '<span><i class="fa fa-folder-open"></i> ' + category + '</span>' +
                    (courses ? ' • <span><i class="fa fa-book"></i> ' + courses + '</span>' : '') +
                    ' • <span><i class="fa fa-calendar"></i> ' + date + '</span>' +
                '</div>' +
            '</div>'
        );
    }
    $('#questionsetselect').change(function() {
      const selectarray = $(this).val().split(',');
      // console.log(selectarray[0]);
      // console.log(selectarray[1]);
      var appendhtml = '<tr id="tablerow'+selectarray[1]+'">';
      appendhtml += '<td width="70%">' + selectarray[0] + '</td>';
      appendhtml += '<td width="20%"><input type="number" min="1" class="form-control" name="questionamount'+selectarray[1]+'" placeholder="প্রশ্নের সংখ্যা" required></td>';
      appendhtml += '<td align="right"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow('+selectarray[1]+')"><i class="far fa-trash-alt"></i></button</td>';
      appendhtml += '</tr>';
      $('#selectedquestionlist').append(appendhtml);
      otherexamids.push(selectarray[1]);
      $('#otherexamids').val(otherexamids);
    });
    function removeRow(rowid) {
      // console.log(rowid);
      $('#tablerow' + rowid).remove();
      var y = $('#otherexamids').val().split(",").map(Number);
      var removeItem = rowid;

      otherexamids = jQuery.grep(y, function(value) {
        return value != removeItem;
      });
      $('#otherexamids').val(otherexamids);
    }
</script>
@endsection