@extends('layouts.app')
@section('title') ড্যাশবোর্ড | ব্যালেন্স @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
	@section('page-header') বর্তমান ব্যালেন্স ৳ {{ $totalbalance - $totalexpense }} @endsection
    <div class="container-fluid">
		<div class="card">
          <div class="card-header">
            <h3 class="card-title">ব্যালেন্স</h3>

            <div class="card-tools">
            	<button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addBalanceModal">
            		<i class="fas fa-coins"></i> অর্থ যোগ
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
                {{-- <tr>
                  <td>1.</td>
                  <td>Update software</td>
                  <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                    </div>
                  </td>
                  <td><span class="badge bg-danger">55%</span></td>
                </tr> --}}
                @foreach($balances as $balance)
                	<tr>
                    <td style="line-height: 1;">
                      <span class="badge bg-success"><big>জমাঃ ৳ {{ $balance->amount }}</big></span><br/>
                      <small>
                          <span class="text-black-50">প্রদান করেছেনঃ </span> {{ $balance->user->name }},
                          <span class="text-black-50">গ্রহণ করেছেনঃ </span> {{ $balance->receiver ? $balance->receiver->name : ''}}<br/>
                          <span class="text-black-50">মাধ্যমঃ </span> {{ $balance->medium ? $balance->medium : ''}}, <span class="text-black-50">বিবরণঃ </span> {{ $balance->description ? $balance->description : ''}}<br/>
                          <small>{{ date('F d, Y h:i A', strtotime($balance->created_at)) }}</small>
                      </small> 
                    </td>
                		<td align="right" width="40%">
                			@if(Auth::user()->role == 'admin')
                          <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteBalanceModal{{ $balance->id }}">
                              <i class="fas fa-trash-alt"></i>
                          </button>
                      @endif
                		</td>
                        {{-- Delete Balance Modal Code --}}
                        {{-- Delete Balance Modal Code --}}
                        <!-- Modal -->
                        <div class="modal fade" id="deleteBalanceModal{{ $balance->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteBalanceModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deleteBalanceModalLabel">অর্থ ডিলেট</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                আপনি কি নিশ্চিতভাবে এই অর্থ ডিলেট করতে চান?<br/>
                                <center><big><b>৳ {{ $balance->amount }}</b></big><br/>
                                    <small><i class="fas fa-user"></i> {{ $balance->user->name }}, <i class="fas fa-calendar-alt"></i> {{ date('F d, Y', strtotime($balance->created_at)) }}</small>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <a href="{{ route('dashboard.balance.delete', $balance->id) }}" class="btn btn-danger">ডিলেট করুন</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        {{-- Delete Balance Modal Code --}}
                        {{-- Delete Balance Modal Code --}}
                	</tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <small>{{ $balances->onEachSide(1)->links() }}</small>
    </div>

    {{-- Add Balance Modal Code --}}
    {{-- Add Balance Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addBalanceModal" tabindex="-1" role="dialog" aria-labelledby="addBalanceModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addBalanceModalLabel">অর্থ যোগ করুন</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.balance.store') }}">
	          <div class="modal-body">
	                @csrf

	                <div class="input-group mb-3">
                      <input type="number"
                             name="amount"
                             class="form-control"
                             value="{{ old('amount') }}"
                             placeholder="পরিমাণ লিখুন" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-money-check-alt"></span></div>
                      </div>
                  </div>

                  <div class="input-group mb-3">
                    <select name="receiver_id" class="form-control" required>
                      <option disabled="" selected="">ব্যক্তি নির্ধারণ করুন</option>
                      @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                      @endforeach
                    </select>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-user"></span></div>
                      </div>
                  </div>

                  <div class="input-group mb-3">
                      <input type="text"
                             name="medium"
                             class="form-control"
                             value="{{ old('medium') }}"
                             placeholder="মাধ্যম (ঐচ্ছিক)">
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-list-ul"></span></div>
                      </div>
                  </div>

                  <div class="input-group mb-3">
                      <input type="text"
                             name="description"
                             class="form-control"
                             value="{{ old('description') }}"
                             placeholder="বিবরণ (ঐচ্ছিক)">
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-file-alt"></span></div>
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
    {{-- Add Balance Modal Code --}}
    {{-- Add Balance Modal Code --}}
@endsection

@section('third_party_scripts')

@endsection