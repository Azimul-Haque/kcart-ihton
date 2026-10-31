@extends('layouts.app')
@section('title') ড্যাশবোর্ড | পাওনাদারের হিসাব @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
  @section('page-header') {{ $creditor->name }} @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">সর্বমোট বকেয়াঃ ৳ {{ $creditor->dues->sum('amount') }}</h3>

            <div class="card-tools">
              <a href="{{ route('dashboard.addduepage') }}" class="btn btn-success btn-sm">
                <i class="fas fa-money-check-alt"></i> বকেয়া যোগ
              </a>
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
                @foreach($dues as $due)
                  <tr>
                    <td>
                      <b>৳ {{ $due->amount }}</b><br/>
                      <small>
                          <span>{{ date('F d, Y h:i A', strtotime($due->created_at)) }}</span>, 
                          <span class="text-black-50">বিবরণঃ</span>  {{ $due->description }}
                      </small>   
                    </td>
                    <td align="right" width="40%">
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal{{ $due->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      {{-- Edit Modal Code --}}
                      {{-- Edit Modal Code --}}
                      <!-- Modal -->
                      <div class="modal fade" id="editModal{{ $due->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-primary">
                              <h5 class="modal-title" id="editModalLabel">বকেয়া সম্পাদনা করুন</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="post" action="{{ route('dashboard.creditorsdue.update', $due->id) }}">
                              <div class="modal-body">
                                
                                    @csrf

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="description"
                                               class="form-control"
                                               value="{{ $due->description }}"
                                               placeholder="বিবরণ (ঐচ্ছিক)">
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-file-alt"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="number"
                                               name="amount"
                                               class="form-control"
                                               value="{{ $due->amount }}"
                                                   min="1" 
                                               placeholder="বকেয়ার পরিমাণ লিখুন" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-money-check-alt"></span></div>
                                        </div>
                                    </div>
                                                                      
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <button type="submit" class="btn btn-primary">হালনাগাদ করুন</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      {{-- Edit Modal Code --}}
                      {{-- Edit Modal Code --}}

                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $due->id }}">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                    {{-- Delete Modal Code --}}
                    {{-- Delete Modal Code --}}
                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal{{ $due->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-danger">
                            <h5 class="modal-title" id="deleteModalLabel">বকেয়া ডিলেট</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            আপনি কি নিশ্চিতভাবে এই বকেয়াটি ডিলেট করতে চান?<br/>
                            <center>
                                <big><b>৳ {{ $due->amount }}</b></big><br/>
                                <small><i class="fas fa-calendar-week"></i> {{ date('F d, Y h:i A', strtotime($due->created_at)) }}</small>
                            </center>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                            <a href="{{ route('dashboard.creditorsdue.delete', $due->id) }}" class="btn btn-danger">ডিলেট করুন</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    {{-- Delete Modal Code --}}
                    {{-- Delete Modal Code --}}
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <small>{{ $dues->onEachSide(1)->links() }}</small>
    </div>
@endsection

@section('third_party_scripts')

@endsection