@extends('layouts.app')
@section('title') ড্যাশবোর্ড | পাওনাদারের হিসাব @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
  @section('page-header') পাওনাদারের হিসাব @endsection
  @section('page-header-right')
    <a href="{{ route('dashboard.addduepage') }}" class="btn btn-success btn-sm">
      <i class="fas fa-money-check-alt"></i> বকেয়া যোগ
    </a>
  @endsection
    <div class="container-fluid">
    <div class="card">
          <div class="card-header">
            <h3 class="card-title">পাওনাদারের হিসাব</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addCreditModal">
                <i class="fas fa-user-plus"></i> পাওনাদার যোগ
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
                @foreach($creditors as $creditor)
                  <tr>
                    <td>
                        <a href="{{ route('dashboard.creditors.single', $creditor->id) }}">{{ $creditor->name }}</a><br/>
                        সর্বমোট বকেয়াঃ 
                        <span class="badge bg-warning">
                          ৳ {{ $creditor->dues->sum('amount') }}
                        </span>
                    </td>
                    <td align="right" width="40%">
                      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editCreditorModal{{ $creditor->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                      {{-- Edit Modal Code --}}
                      {{-- Edit Modal Code --}}
                      <!-- Modal -->
                      <div class="modal fade" id="editCreditorModal{{ $creditor->id }}" tabindex="-1" role="dialog" aria-labelledby="editCreditorModalLabel" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header bg-primary">
                              <h5 class="modal-title" id="editCreditorModalLabel">পাওনাদার সম্পাদনা করুন</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="post" action="{{ route('dashboard.creditors.update', $creditor->id) }}">
                              <div class="modal-body">
                                
                                    @csrf

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               value="{{ $creditor->name }}"
                                               placeholder="পাওনাদারের নাম" srequired>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-tag"></span></div>
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
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <small>{{ $creditors->onEachSide(1)->links() }}</small>
    </div>


    {{-- Add Modal Code --}}
    {{-- Add Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addCreditModal" tabindex="-1" role="dialog" aria-labelledby="addCreditModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addCreditModalLabel">নতুন পাওনাদার যোগ করুন</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.creditors.store') }}">
            <div class="modal-body">
              
                  @csrf

                  <div class="input-group mb-3">
                      <input type="text"
                             name="name"
                             class="form-control"
                             value="{{ old('name') }}"
                             placeholder="পাওনাদারের নাম" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-tag"></span></div>
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
    {{-- Add Modal Code --}}
    {{-- Add Modal Code --}}
@endsection

@section('third_party_scripts')

@endsection