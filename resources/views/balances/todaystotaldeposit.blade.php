@extends('layouts.app')
@section('title') ড্যাশবোর্ড | দৈনিক জমা @endsection

@section('third_party_stylesheets')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
  <style type="text/css">
    @media print
    {    
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
  </style>
@endsection

@section('content')
  @section('page-header') দৈনিক জমা 
    <span style="font-size: 13px;">
      @if(!empty($transactiondate)) {{ bangla(date('F d, Y', strtotime($transactiondate))) }} @else {{ bangla(date('F d, Y')) }} @endif
    </span> 
  @endsection
    <div class="container-fluid">
      <div class="form-row">
        <div class="form-group col-xs-6">
          <input class="form-control" type="text" name="transactiondate" id="transactiondate" @if(!empty($transactiondate)) value="{{ date('F d, Y', strtotime($transactiondate)) }}" @else value="{{ date('F d, Y') }}" @endif placeholder="Select Date" readonly="">
        </div>
        <div class="form-group col-xs-6">
          <select class="form-control" name="selecteduser" id="selecteduser">
            <option value="All" @if($selecteduser == 'All') selected="" @endif>সকল ব্যবহারকারী</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" @if($selecteduser == $user->id) selected="" @endif>{{ $user->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group col-xs-4">
          <button id="loadTodaysDeposits" class="btn btn-success"><i class="fa fa fa-search"></i> দেখুন</button>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">দৈনিক মোট জমাঃ <b>৳ {{ $todaystotaldeposit ? $todaystotaldeposit->totalamount : 0 }}</b></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm no-print" id="printThisPage" title="প্রিন্ট করুন">
              <i class="fas fa-print"></i>
            </button>
            {{-- <ul class="pagination pagination-sm float-right">
              <li class="page-item"><a class="page-link" href="#">«</a></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">»</a></li>
            </ul> --}}
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <table class="table">
            <tbody>
             {{--  <tr>
                <th>ব্যয় করেছেন</th>
                <th>সাইট</th>
                <th>খাত</th>
                <th>পরিমাণ</th>
                <th>ব্য্যের পরিমাণ</th>
                <th>ব্য্যের পরিমাণ</th>
                
              </tr> --}}
              @foreach($deposits as $deposit)
                <tr>
                  <td style="line-height: 1;">
                    <span class="badge bg-success"><big>জমাঃ ৳ {{ $deposit->amount }}</big></span><br/>
                    <small>
                        <span class="text-black-80">প্রদান করেছেনঃ </span> {{ $deposit->user ? $deposit->user->name : '' }},
                        <span class="text-black-80">গ্রহণ করেছেনঃ </span> {{ $deposit->receiver ? $deposit->receiver->name : ''}}<br/>
                        <span class="text-black-80">মাধ্যমঃ </span> {{ $deposit->medium ? $deposit->medium : ''}}, <span class="text-black-80">বিবরণঃ </span> {{ $deposit->description ? $deposit->description : ''}}<br/>
                        <small>{{ date('F d, Y h:i A', strtotime($deposit->created_at)) }}</small>
                    </small> 
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      {{-- {{ $deposit->links() }} --}}
    </div>

    
@endsection

@section('third_party_scripts')
  <script type="text/javascript" src="{{ asset('js/jquery-for-dp.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script>
    $("#transactiondate").datepicker({
      format: 'MM dd, yyyy',
      todayHighlight: true,
      autoclose: true,
    });

    $('#loadTodaysDeposits').click(function() {
      var transactiondate = $('#transactiondate').val();

      // toastr.warning('Select Date!', 'WARNING').css('width', '400px');
      
      selecteduser = $('#selecteduser').val();
      console.log(selecteduser);
      if(isEmptyOrSpaces(transactiondate)) {
        Toast.fire({
          icon: 'warning',
          title: 'Select Date!'
        })
      } else {
        window.location.href = '/dashboard/deposit/'+ moment(transactiondate).format('YYYY-MM-DD') + '/' + selecteduser;
      }
    });

    // on enter search
    function isEmptyOrSpaces(str){
        return str === null || str.match(/^ *$/) !== null;
    }

    // print
    $('#printThisPage').click(function(){
      $('.bg-success').removeClass('bg-success');
      window.print();
    });
  </script>
@endsection