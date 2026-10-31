@extends('layouts.app')
@section('title') ড্যাশবোর্ড | কোর্স | {{ $course->name }} @endsection

@section('third_party_stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('js/jquery-for-dp.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
@endsection

@section('content')
    @section('page-header') {{ $course->name }} এর সিরিয়াল আপডেট @endsection
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">{{ $course->name }} এর সিরিয়াল আপডেট</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <table class="table table-striped">
                          <thead>
                              <tr>
                                  <th style="width: 10px">#</th>
                                  <th>Exam Name</th>
                                  <th>Created At</th>
                                  <th style="width: 150px">Serial (Order)</th>
                                  <th style="width: 100px">Action</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($courseexams as $courseexam)
                                  @php
                                      $exam = $courseexam->exam;
                                      // Calculate the current row number across all pages
                                      $rowNumber = ($courseexams->currentPage() - 1) * $courseexams->perPage() + $loop->iteration;
                                  @endphp
                                  <tr>
                                      <td>{{ $rowNumber }}</td>
                                      <td>{{ $exam->name ?? 'N/A' }}</td>
                                      <td>{{ $exam->created_at->format('Y-m-d') ?? 'N/A' }}</td>
                                      
                                      <form action="{{ route('dashboard.courses.exam.serial.edit', $exam->id) }}" method="POST">
                                          @csrf
                                          @method('POST')
                                          
                                          <td>
                                              <input 
                                                  type="number" 
                                                  name="serial" 
                                                  value="{{ $exam->serial ?? 0 }}" 
                                                  class="form-control form-control-sm"
                                                  placeholder="0" 
                                                  min="0"
                                                  style="max-width: 100px;"
                                              >
                                          </td>
                                          <td>
                                              <button type="submit" class="btn btn-sm btn-primary">হালনাগাদ</button>
                                          </td>
                                      </form>
                                  </tr>
                              @endforeach
                          </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>

                  {{ $courseexams->links() }}
            </div>
        </div>

    
@endsection

@section('third_party_scripts')

@endsection