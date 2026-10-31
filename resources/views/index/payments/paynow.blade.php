@extends('layouts.index')
@section('title') Pay Now | BJS & Bar Exam @endsection

@section('third_party_stylesheets')

@endsection

@section('content')
<section style="padding-top: 150px; padding-bottom: 50px; background-color: var(--light-3);">
      <div class="section-title-five">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="content">
                <h6>পেমেন্ট সম্পন্ন করুন</h6>
                <h2 class="fw-bold">৳ {{ bangla($amount) }}</h2>
                <p>
                  ট্রানজেকশন আইডিঃ {{ $trxid }}
                </p>
                <div style="border: 2px solid #ddd; padding: 0px; width: 100%; padding: 20px;" >
                    {{-- <img src="{{ asset('images/aamarpay.png') }}" class="img-responsive margin-two"> --}}
                    {!!
                    aamarpay_post_button([
                        'tran_id'   => $trxid,
                        'cus_name'  => $user->name,
                        'cus_email' => $user->mobile.'@bjsexam.com',
                        'cus_phone' => $user->mobile,

                        'success_url' => route('index.payment.success'),
                        'fail_url' => route('index.payment.failed'),
                        'cancel_url' => route('index.payment.cancel'),

                        'desc' => $packagedesc,
                        'opt_a' => $user->id,
                        'opt_b' => $amount,
                    ], $amount, '<i class="fa fa-money"></i> Pay Through AamarPay', 'btn primary-btn') !!}
                    <br/><br/>
                    <small>
                    	<a href="{{ route('index.terms-and-conditions') }}" target="_blank">Terms & Conditions</a>, <a href="{{ route('index.privacy-policy') }}" target="_blank">Privacy Policy</a> & <a href="{{ route('index.refund-policy') }}" target="_blank">Refund Policy</a>
                  	</small>
                </div>
              </div>
            </div>
          </div>
          <!-- row -->
        </div>
        <!-- container -->
      </div>
</section>
@endsection

@section('third_party_scripts')

@endsection