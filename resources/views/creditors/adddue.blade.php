@extends('layouts.app')
@section('title') ড্যাশবোর্ড | বকেয়া যোগ করুন @endsection

@section('third_party_stylesheets')
	<style type="text/css">
		#img-upload{
		    width: 200px;
		    height: auto;
		}
	</style>
@endsection

@section('content')
	@section('page-header') বকেয়া যোগ করুন @endsection
    <div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="card card-success">
		          <div class="card-header">
		            <h3 class="card-title">বকেয়া যোগ করুন</h3>

		            <div class="card-tools">
		            	{{-- <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addExpenseModal">
		            		<i class="fas fa-hand-holding-usd"></i> Add
		            	</button> --}}
		            </div>
		          </div>
		          <!-- /.card-header -->
		          <div class="card-body">
	      	          <form method="post" action="{{ route('dashboard.creditorsdue.store') }}">
				          @csrf

				          <div class="input-group mb-3">
				          	<select name="creditor_id" class="form-control" required>
				          		<option selected="" disabled="" value="">পাওনাদার নির্ধারণ করুন</option>
				          		@foreach($creditors as $creditor)
				          			<option value="{{ $creditor->id }}">{{ $creditor->name }}</option>
				          		@endforeach
				          	</select>
				              <div class="input-group-append">
				                  <div class="input-group-text"><span class="fas fa-user"></span></div>
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

				          <div class="input-group mb-3">
				              <input type="number"
				                     name="amount"
				                     class="form-control"
				                     value="{{ old('amount') }}"
		                             min="1" 
				                     placeholder="বকেয়ার পরিমাণ লিখুন" required>
				              <div class="input-group-append">
				                  <div class="input-group-text"><span class="fas fa-money-check-alt"></span></div>
				              </div>
				          </div>

				          <button type="submit" class="btn btn-success">দাখিল করুন</button>
			          </form>
		          </div>
		          <!-- /.card-body -->
		        </div>
			</div>
		</div>
    </div>
@endsection

@section('third_party_scripts')
	
@endsection