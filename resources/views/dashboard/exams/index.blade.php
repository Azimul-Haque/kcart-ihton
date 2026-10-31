@extends('layouts.app')
@section('title') ড্যাশবোর্ড | পরীক্ষাসমূহ @endsection

@section('third_party_stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/jquery-for-dp.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
@endsection

@section('content')
    @section('page-header') পরীক্ষাসমূহ @endsection
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">পরীক্ষাসমূহ</h3> <span style="margin-left: 5px;">(মোটঃ {{ $totalexams }} টি পরীক্ষা)</span>
          
                      <div class="card-tools">
                          <form class="form-inline form-group-lg" action="">
                            <div class="form-group">
                              <input type="search-param" class="form-control form-control-sm" placeholder="প্রশ্ন খুঁজুন" id="search-param" required>
                            </div>
                            <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                              <i class="fas fa-search"></i> খুঁজুন
                            </button>
                            {{-- <button type="button" class="btn btn-warning btn-sm"  data-toggle="modal" data-target="#addExcelExamModal" style="margin-left: 5px;">
                                <i class="fas fa-file-excel"></i> এক্সেল ফাইল আপলোড করুন
                            </button> --}}
                            <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addExamModal" style="margin-left: 5px;">
                              <i class="fas fa-plus-circle"></i> নতুন পরীক্ষা যোগ
                          </button>
                          </form>
                      </div>
                    </div>
                    <div class="card-body p-0">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th>পরীক্ষা</th>
                                  <th>যে সকল কোর্সে আছে</th>
                                  <th>সময়কাল</th>
                                  <th>প্রশ্ন ও প্রশ্নের মান</th>
                                  <th>শুরু-শেষ</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                          @foreach($exams as $exam)
                              <tr>
                                  <td>
                                    @php
                                        $currentDate = date('Y-m-d');  
                                        $startDate = date('Y-m-d', strtotime($exam->available_from));
                                        $endDate = date('Y-m-d', strtotime($exam->available_to));
                                        $isCurrent = false;
                                        if (($currentDate >= $startDate) && ($currentDate <= $endDate)) {   
                                            $isCurrent = true;
                                        }
                                    @endphp
                                    @if($isCurrent == true)
                                        <i class="fas fa-calendar-check" style="color: green;" rel="tooltip" title="চলতি"></i>
                                    @endif
                                    <a href="{{ route('dashboard.exams.add.question', $exam->id) }}" rel="tooltip" title="প্রশ্ন যোগ করুন">{{ $exam->name }}</a><br/>
                                    <span class="badge bg-success">{{ $exam->examcategory->name }}</span>
                                    <a href="{{ route('dashboard.exams.getmeritlist', $exam->id) }}" class="badge bg-primary" rel="tooltip" title="" data-original-title="মেধাতালিকা দেখুন">মেধাতালিকা <i class="fas fa-bolt"></i> {{ $exam->participation }}</a>
                                  </td>
                                  <td>
                                      @if($exam->courseexams->count() > 0)
                                          <div class="d-flex flex-wrap" style="gap: 5px;">
                                              @foreach($exam->courseexams as $index => $ce)
                                                  @if($ce->course != null)
                                                      <span class="badge badge-warning shadow-sm">
                                                          <i class="fas fa-book-open mr-1"></i>
                                                          {{ $ce->course->name ?? 'N/A' }}
                                                      </span>
                                                      {{-- যদি কমাও রাখতে চান তবে নিচের কোডটি ব্যবহার করতে পারেন --}}
                                                      {{-- @if(!$loop->last), @endif --}}
                                                  @endif
                                              @endforeach
                                          </div>
                                      @else
                                          <span class="text-muted small">কোনো কোর্সে যুক্ত নেই</span>
                                      @endif
                                  </td>
                                  <td><span class="fas fa-stopwatch"></span> {{ $exam->duration }} মিনিট</td>
                                  <td>
                                    <span class="badge bg-primary">{{ $exam->examquestions->count() }} টি প্রশ্ন</span><br/>
                                    মানঃ {{ $exam->qsweight }} (-{{ $exam->negativepercentage / 100 }} প্রতি ভুলের জন্য)
                                  </td>
                                  <td>{{ date('F d, Y', strtotime($exam->available_from)) }} থেকে {{ date('F d, Y', strtotime($exam->available_to)) }}</td>
                              
                                  <td>
                                      <a href="{{ route('dashboard.exams.add.question', $exam->id) }}" class="btn btn-warning btn-sm" rel="tooltip" title="প্রশ্ন যোগ/ হালনাগাদ করুন">
                                          <i class="fas fa-folder-plus"></i>
                                      </a>
                                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#copyExamModal{{ $exam->id }}" rel="tooltip" title="কপি তৈরি করুন">
                                          <i class="far fa-copy"></i>
                                      </button>

                                      <div class="modal fade" id="copyExamModal{{ $exam->id }}" tabindex="-1" role="dialog" aria-labelledby="copyExamModalLabel{{ $exam->id }}" aria-hidden="true" data-backdrop="static">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info">
                                              <h5 class="modal-title" id="copyExamModalLabel{{ $exam->id }}">পরীক্ষা কপি করুন</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <form method="post" action="{{ route('dashboard.exams.copy', $exam->id) }}">
                                              <div class="modal-body">
                                                  @csrf
                                                  <div class="input-group mb-3">
                                                    <input type="text" name="name" class="form-control" value="{{ $exam->name }} - Copy" placeholder="পরীক্ষার নাম" required>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text"><span class="far fa-clipboard"></span></div>
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

                                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editExamModal{{ $exam->id }}">
                                          <i class="far fa-edit"></i>
                                      </button>

                                      <div class="modal fade" id="editExamModal{{ $exam->id }}" tabindex="-1" role="dialog" aria-labelledby="editExamModalLabel{{ $exam->id }}" aria-hidden="true" data-backdrop="static">
                                          <div class="modal-dialog modal-lg" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header bg-success">
                                                <h5 class="modal-title" id="editExamModalLabel{{ $exam->id }}">পরীক্ষা হালনাগাদ</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>
                                              <form method="post" action="{{ route('dashboard.exams.update', $exam->id) }}" enctype="multipart/form-data">
                                                  <div class="modal-body text-left">
                                                      @csrf
                                                      <div class="row">
                                                          
                                                          <!-- পরীক্ষার ক্যাটাগরি -->
                                                          <div class="col-md-6 mb-3">
                                                              <label class="font-weight-bold text-dark small mb-1">পরীক্ষার ক্যাটাগরি</label>
                                                              <div class="input-group">
                                                                  <select name="examcategory_id" class="form-control" required>
                                                                      <option value="" disabled>পরীক্ষার ক্যাটাগরি নির্বাচন করুন</option>
                                                                      @foreach ($examcategories as $category)
                                                                          <option value="{{ $category->id }}" @if($exam->examcategory_id == $category->id) selected @endif>{{ $category->name }}</option>
                                                                      @endforeach
                                                                  </select>
                                                                  <div class="input-group-append">
                                                                      <div class="input-group-text"><span class="fas fa-bookmark"></span></div>
                                                                  </div>
                                                              </div>
                                                          </div>

                                                          <!-- পরীক্ষার নাম -->
                                                          <div class="col-md-6 mb-3">
                                                              <label class="font-weight-bold text-dark small mb-1">পরীক্ষার নাম</label>
                                                              <div class="input-group">
                                                                  <input type="text" name="name" class="form-control" value="{{ $exam->name }}" placeholder="পরীক্ষার নাম লিখুন" required>
                                                                  <div class="input-group-append">
                                                                      <div class="input-group-text"><span class="far fa-clipboard"></span></div>
                                                                  </div>
                                                              </div>
                                                          </div>

                                                          <!-- সময়কাল (মিনিটে) -->
                                                          <div class="col-md-6 mb-3">
                                                              <label class="font-weight-bold text-dark small mb-1">সময়কাল (মিনিটে)</label>
                                                              <div class="input-group">
                                                                  <input type="number" name="duration" value="{{ $exam->duration }}" min="1" class="form-control" placeholder="যেমন: ৬০" required>
                                                                  <div class="input-group-append">
                                                                      <div class="input-group-text"><span class="fas fa-stopwatch"></span></div>
                                                                  </div>
                                                              </div>
                                                          </div>

                                                          <!-- প্রতি প্রশ্নের মান -->
                                                          <div class="col-md-6 mb-3">
                                                              <label class="font-weight-bold text-dark small mb-1">প্রতি প্রশ্নের মান</label>
                                                              <div class="input-group">
                                                                  <input type="number" name="qsweight" value="{{ $exam->qsweight }}" min="1" class="form-control" placeholder="যেমন: ১" required>
                                                                  <div class="input-group-append">
                                                                      <div class="input-group-text"><span class="fas fa-sort-amount-up-alt"></span></div>
                                                                  </div>
                                                              </div>
                                                          </div>

                                                          <!-- নেগেটিভ মার্ক পারসেন্টেজ -->
                                                          <div class="col-md-6 mb-3">
                                                              <label class="font-weight-bold text-dark small mb-1">নেগেটিভ মার্কস (%)</label>
                                                              <div class="input-group">
                                                                  <input type="number" name="negativepercentage" value="{{ $exam->negativepercentage }}" min="0" max="50" class="form-control" placeholder="যেমন: ২৫" required>
                                                                  <div class="input-group-append">
                                                                      <div class="input-group-text"><span class="fas fa-percent"></span></div>
                                                                  </div>
                                                              </div>
                                                          </div>

                                                          <!-- কাটমার্কস -->
                                                          <div class="col-md-6 mb-3">
                                                              <label class="font-weight-bold text-dark small mb-1">কাটমার্কস</label>
                                                              <div class="input-group">
                                                                  <input type="text" name="cutmark" value="{{ $exam->cutmark }}" class="form-control" placeholder="যেমন: ৫০" required>
                                                                  <div class="input-group-append">
                                                                      <div class="input-group-text"><span class="fas fa-greater-than-equal"></span></div>
                                                                  </div>
                                                              </div>
                                                          </div>

                                                          <!-- চালু হবে (শুরুর তারিখ) -->
                                                          <div class="col-md-6 mb-3">
                                                              <label class="font-weight-bold text-dark small mb-1">চালু হবে (তারিখ)</label>
                                                              <div class="input-group">
                                                                  <input type="text" name="available_from" id="available_from{{ $exam->id }}" value="{{ date('F d, Y', strtotime($exam->available_from)) }}" class="form-control" autocomplete="off" placeholder="তারিখ নির্বাচন করুন" required>
                                                                  <div class="input-group-append">
                                                                      <div class="input-group-text"><span class="fas fa-calendar-check"></span></div>
                                                                  </div>
                                                              </div>
                                                          </div>

                                                          <!-- চালু থাকবে (শেষ তারিখ) -->
                                                          <div class="col-md-6 mb-3">
                                                              <label class="font-weight-bold text-dark small mb-1">চালু থাকবে (পর্যন্ত)</label>
                                                              <div class="input-group">
                                                                  <input type="text" name="available_to" id="available_to{{ $exam->id }}" value="{{ date('F d, Y', strtotime($exam->available_to)) }}" class="form-control" autocomplete="off" placeholder="তারিখ নির্বাচন করুন" required>
                                                                  <div class="input-group-append">
                                                                      <div class="input-group-text"><span class="fas fa-calendar-minus"></span></div>
                                                                  </div>
                                                              </div>
                                                          </div>

                                                          <!-- লেকচার PDF লিংক -->
                                                          <div class="col-md-6 mb-3">
                                                              <label class="font-weight-bold text-dark small mb-1">লেকচার PDF লিংক (ঐচ্ছিক)</label>
                                                              <div class="input-group">
                                                                  <input type="text" name="lecture_url" id="lecture_url{{ $exam->id }}" value="{{ $exam->lecture_url }}" class="form-control" placeholder="https://example.com/lecture.pdf">
                                                                  <div class="input-group-append">
                                                                      <div class="input-group-text"><span class="fas fa-file-pdf"></span></div>
                                                                  </div>
                                                              </div>
                                                          </div>

                                                          <!-- সিলেবাস -->
                                                          <div class="col-md-12 mb-3">
                                                              <label class="font-weight-bold text-dark small mb-1">সিলেবাস</label>
                                                              <div class="input-group">
                                                                  <textarea class="form-control" name="syllabus" style="height: 120px;" placeholder="সিলেবাসের বিস্তারিত লিখুন" required>{!! str_replace('<br />', "", $exam->syllabus) !!}</textarea>
                                                              </div>
                                                          </div>

                                                      </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                                      <button type="submit" class="btn btn-success">আপডেট করুন</button>
                                                  </div>
                                              </form>
                                          </div>
                                          </div>
                                      </div>
                                    <script>
                                        $("#available_from{{ $exam->id }}").datepicker({
                                          format: 'MM dd, yyyy',
                                          todayHighlight: true,
                                          autoclose: true,
                                          container:'#editExamModal{{ $exam->id }}',
                                        });
                                        $("#available_to{{ $exam->id }}").datepicker({
                                          format: 'MM dd, yyyy',
                                          todayHighlight: true,
                                          autoclose: true,
                                          container:'#editExamModal{{ $exam->id }}',
                                        });
                                    </script>
          
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteQuestionModal{{ $exam->id }}" disabled>
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                  </td>
                                  
                                  <div class="modal fade" id="deleteQuestionModal{{ $exam->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteQuestionModalLabel{{ $exam->id }}" aria-hidden="true" data-backdrop="static">
                                      <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                          <div class="modal-header bg-danger">
                                          <h5 class="modal-title" id="deleteQuestionModalLabel{{ $exam->id }}">পরীক্ষা ডিলেট</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                          </div>
                                          <div class="modal-body">
                                            আপনি কি নিশ্চিতভাবে এই পরীক্ষাটি ডিলেট করতে চান?<br/><br/>
                                            <center>
                                                <big><b>{{ $exam->name }}</b></big>
                                            </center>
                                          </div>
                                          <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                          <a href="{{ route('dashboard.exams.delete', $exam->id) }}" class="btn btn-danger">ডিলেট করুন</a>
                                          </div>
                                      </div>
                                      </div>
                                  </div>
                              </tr>
                          @endforeach
                          </tbody>
                      </table>
                    </div>
                    </div>
                  {{ $exams->links() }}
            </div>
            {{-- <div class="col-md-2">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">পরীক্ষার ক্যাটাগরি</h3>
          
                      <div class="card-tools">
                          <button type="button" class="btn btn-warning btn-sm"  data-toggle="modal" data-target="#addTopicModal">
                              <i class="fas fa-plus-circle"></i> নতুন ক্যাটাগরি
                          </button>
                      </div>
                    </div>
                    <div class="card-body p-0">
                      <table class="table">
                          <thead>
                              <tr>
                                  <th>Category</th>
                                  <th>Action</th>
                              </tr>
                          </thead>
                          <tbody>
                          @foreach($examcategories as $category)
                              <tr>
                                  <td>
                                      {{ $category->name }}
                                  </td>
                              
                                  <td align="right" width="40%">
                                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editCategoryModal{{ $category->id }}">
                                          <i class="far fa-edit"></i>
                                      </button>
                                      
                                      <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true" data-backdrop="static">
                                          <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                              <div class="modal-header bg-warning">
                                              <h5 class="modal-title" id="editCategoryModalLabel">ক্যাটাগরি হালনাগাদ</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                              </button>
                                              </div>
                                              <form method="post" action="{{ route('dashboard.exams.category.update', $category->id) }}">
                                                  <div class="modal-body">
                                                      @csrf
                                                      <div class="input-group mb-3">
                                                          <input type="text"
                                                                  name="name"
                                                                  class="form-control"
                                                                  value="{{ $category->name }}"
                                                                  placeholder="নাম" required>
                                                          <div class="input-group-append">
                                                              <div class="input-group-text"><span class="far fa-bookmark"></span></div>
                                                          </div>
                                                      </div>
                                                      <div class="input-group mb-3">
                                                          <input type="text"
                                                                 name="thumbnail"
                                                                 class="form-control"
                                                                 value="{{ $category->thumbnail }}"
                                                                 placeholder="থাম্বনেইল" required>
                                                          <div class="input-group-append">
                                                              <div class="input-group-text"><span class="far fa-image"></span></div>
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
          
                                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteCategoryModal{{ $category->id }}" disabled>
                                          <i class="far fa-trash-alt"></i>
                                      </button>
                                  </td>
                                  
                                  <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true" data-backdrop="static">
                                      <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                          <div class="modal-header bg-danger">
                                          <h5 class="modal-title" id="deleteCategoryModalLabel">ক্যাটাগরি ডিলেট</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                          </button>
                                          </div>
                                          <div class="modal-body">
                                          আপনি কি নিশ্চিতভাবে এই ক্যাটাগরিটি ডিলেট করতে চান?<br/>
                                          <center>
                                              <big><b>{{ $category->name }}</b></big><br/>
                                          </center>
                                          </div>
                                          <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                          <a href="{{ route('dashboard.exams.category.delete', $category->id) }}" class="btn btn-danger">ডিলেট করুন</a>
                                          </div>
                                      </div>
                                      </div>
                                  </div>
                              </tr>
                          @endforeach
                          </tbody>
                      </table>
                    </div>
                    </div>
            </div> --}}
        </div>
    </div>

    <div class="modal fade" id="addExamModal" tabindex="-1" role="dialog" aria-labelledby="addExamModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addExamModalLabel">নতুন পরীক্ষা যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.exams.store') }}" enctype="multipart/form-data">
              <div class="modal-body text-left">
                  @csrf
                  <div class="row">
                      
                      <!-- পরীক্ষার ক্যাটাগরি -->
                      <div class="col-md-6 mb-3">
                          <label class="font-weight-bold text-dark small mb-1">পরীক্ষার ক্যাটাগরি</label>
                          <div class="input-group">
                              <select name="examcategory_id" class="form-control" required>
                                  <option selected disabled value="">পরীক্ষার ক্যাটাগরি নির্বাচন করুন</option>
                                  @foreach ($examcategories as $category)
                                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                                  @endforeach
                              </select>
                              <div class="input-group-append">
                                  <div class="input-group-text"><span class="fas fa-bookmark"></span></div>
                              </div>
                          </div>
                      </div>

                      <!-- পরীক্ষার নাম -->
                      <div class="col-md-6 mb-3">
                          <label class="font-weight-bold text-dark small mb-1">পরীক্ষার নাম</label>
                          <div class="input-group">
                              <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="পরীক্ষার নাম লিখুন" required>
                              <div class="input-group-append">
                                  <div class="input-group-text"><span class="far fa-clipboard"></span></div>
                              </div>
                          </div>
                      </div>

                      <!-- সময়কাল (মিনিটে) -->
                      <div class="col-md-6 mb-3">
                          <label class="font-weight-bold text-dark small mb-1">সময়কাল (মিনিটে)</label>
                          <div class="input-group">
                              <input type="number" name="duration" value="{{ old('duration') }}" min="1" class="form-control" placeholder="যেমন: 60" required>
                              <div class="input-group-append">
                                  <div class="input-group-text"><span class="fas fa-stopwatch"></span></div>
                              </div>
                          </div>
                      </div>

                      <!-- প্রতি প্রশ্নের মান -->
                      <div class="col-md-6 mb-3">
                          <label class="font-weight-bold text-dark small mb-1">প্রতি প্রশ্নের মান</label>
                          <div class="input-group">
                              <input type="number" name="qsweight" value="1" min="1" class="form-control" placeholder="যেমন: 1" required>
                              <div class="input-group-append">
                                  <div class="input-group-text"><span class="fas fa-sort-amount-up-alt"></span></div>
                              </div>
                          </div>
                      </div>

                      <!-- নেগেটিভ মার্ক পারসেন্টেজ -->
                      <div class="col-md-6 mb-3">
                          <label class="font-weight-bold text-dark small mb-1">নেগেটিভ মার্কস (%)</label>
                          <div class="input-group">
                              <input type="number" name="negativepercentage" value="25" min="0" max="50" class="form-control" placeholder="যেমন: ২৫" required>
                              <div class="input-group-append">
                                  <div class="input-group-text"><span class="fas fa-percent"></span></div>
                              </div>
                          </div>
                      </div>

                      <!-- কাটমার্কস -->
                      <div class="col-md-6 mb-3">
                          <label class="font-weight-bold text-dark small mb-1">কাটমার্কস</label>
                          <div class="input-group">
                              <input type="text" name="cutmark" class="form-control" value="0" placeholder="যেমন: ৫০" required>
                              <div class="input-group-append">
                                  <div class="input-group-text"><span class="fas fa-greater-than-equal"></span></div>
                              </div>
                          </div>
                      </div>

                      <!-- চালু হবে (শুরুর তারিখ) -->
                      <div class="col-md-6 mb-3">
                          <label class="font-weight-bold text-dark small mb-1">চালু হবে (তারিখ)</label>
                          <div class="input-group">
                              <input type="text" name="available_from" id="available_from" value="{{ old('available_from') }}" class="form-control" autocomplete="off" placeholder="তারিখ নির্বাচন করুন" required>
                              <div class="input-group-append">
                                  <div class="input-group-text"><span class="fas fa-calendar-check"></span></div>
                              </div>
                          </div>
                      </div>

                      <!-- চালু থাকবে (শেষ তারিখ) -->
                      <div class="col-md-6 mb-3">
                          <label class="font-weight-bold text-dark small mb-1">চালু থাকবে (পর্যন্ত)</label>
                          <div class="input-group">
                              <input type="text" name="available_to" id="available_to" value="{{ date('F d, Y') }}" class="form-control" autocomplete="off" placeholder="তারিখ নির্বাচন করুন" required>
                              <div class="input-group-append">
                                  <div class="input-group-text"><span class="fas fa-calendar-minus"></span></div>
                              </div>
                          </div>
                      </div>

                      <!-- লেকচার PDF লিংক -->
                      <div class="col-md-6 mb-3">
                          <label class="font-weight-bold text-dark small mb-1">লেকচার PDF লিংক (ঐচ্ছিক)</label>
                          <div class="input-group">
                              <input type="text" name="lecture_url" id="lecture_url" value="{{ old('lecture_url') }}" class="form-control" placeholder="https://example.com/lecture.pdf">
                              <div class="input-group-append">
                                  <div class="input-group-text"><span class="fas fa-file-pdf"></span></div>
                              </div>
                          </div>
                      </div>

                      

                      <!-- সিলেবাস -->
                      <div class="col-md-12 mb-3">
                          <label class="font-weight-bold text-dark small mb-1">সিলেবাস</label>
                          <div class="input-group">
                              <textarea class="form-control" name="syllabus" style="height: 120px;" placeholder="সিলেবাসের বিস্তারিত লিখুন (যদি থাকে)" required></textarea>
                          </div>
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

    {{-- <div class="modal fade" id="addExcelExamModal" tabindex="-1" role="dialog" aria-labelledby="addExcelExamModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="addExcelExamModalLabel">Excel আপলোড</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.exams.excel.store') }}" enctype='multipart/form-data'>
              <div class="modal-body">
                    @csrf
                    <label for="file">Excel ফাইল</label>
                    <input type="file" id="file" name="file" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                <button type="submit" class="btn btn-warning">দাখিল করুন</button>
              </div>
          </form>
        </div>
      </div>
    </div> --}}

    <div class="modal fade" id="addTopicModal" tabindex="-1" role="dialog" aria-labelledby="addTopicModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-warning">
              <h5 class="modal-title" id="addTopicModalLabel">নতুন ক্যাটাগরি যোগ</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post" action="{{ route('dashboard.exams.category.store') }}">
                <div class="modal-body">
                      @csrf
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="name"
                                 class="form-control"
                                 value="{{ old('name') }}"
                                 placeholder="নাম" required>
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="far fa-bookmark"></span></div>
                          </div>
                      </div>
                      <div class="input-group mb-3">
                          <input type="text"
                                 name="thumbnail"
                                 class="form-control"
                                 value="{{ old('thumbnail') }}"
                                 placeholder="থাম্বনেইল" required>
                          <div class="input-group-append">
                              <div class="input-group-text"><span class="far fa-image"></span></div>
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

@endsection

@section('third_party_scripts')
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

{{-- <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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

    $(document).on('click', '#search-button', function() {
      if($('#search-param').val() != '') {
        var urltocall = '{{ route('dashboard.exams') }}' +  '/' + $('#search-param').val().replace(/\\|\//g, '%');
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
          var urltocall = '{{ route('dashboard.exams') }}' +  '/' + $('#search-param').val().replace(/\\|\//g, '%');
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