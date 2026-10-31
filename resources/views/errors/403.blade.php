@extends('layouts.app')
@section('title') 403 Forbidden @endsection

@section('content')
    <div class="container-fluid">
        @section('page-header') 403 Forbidden @endsection
		<div class="error-page">
			<h2 class="headline text-warning"> 403</h2>
			<div class="error-content">
				<h3><i class="fas fa-exclamation-triangle text-warning"></i> You are NOT ALLOWED!</h3>

				<p>
					The page you are requesting is not allowed for you.
					Instead, you may <a href="{{ route('dashboard.index') }}">return to dashboard</a> or try using the search form.
				</p>
			</div>
		</div>
    </div>
@endsection
