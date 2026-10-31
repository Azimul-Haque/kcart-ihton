<title>bKash Production Test</title>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<br/><br/>
<div class="d-flex justify-content-center align-items-center">
    
    <div class="card p-3">
        
        <div class="d-flex justify-content-between align-items-center ">
            <div class="mt-2">
                <h4 class="text-uppercase">টেস্ট প্রোডাক্ট ১</h4>
                <div class="mt-5">
                    <h5 class="text-uppercase mb-0">বিবরণ ১</h5>
                    <h1 class="main-heading mt-0">বিবরণ ১</h1>
                    <div class="d-flex flex-row user-ratings">
                        <div class="ratings">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        </div>
                        <h6 class="text-muted ml-1">4/5</h6>
                    </div>
                </div>
            </div>
            <div class="image">
                <img src="https://i.imgur.com/MGorDUi.png" width="130">
            </div>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
            <big>৳ ১.০০</big>
            <div class="colors">
                
            </div>
            
        </div>
        
        {{-- <a href="{{ route('bkash-prod-test-payment', '1.00') }}" class="btn btn-danger" id="bKash_button" style="background: url({{ asset('images/bkash_payment_logo.png') }}); background-size: 100%; background-size: 250px auto; background-repeat: no-repeat;">
        </a> --}}
        <form method="post" action="{{ route('bkash-prod-test-payment') }}">
            @csrf
            <input type="hidden" name="amount" value="1.00">
            <button class="btn btn-danger" id="bKash_button" style="background:  #FFFFFF;">
                <img style="width: 210px; heigh: auto;" src="{{ asset('images/bkash_payment_logo.png') }}">
            </button>
        </form>
    </div>

    <div style="width:20px"></div>

    <div class="card p-3">
    
        <div class="d-flex justify-content-between align-items-center ">
            <div class="mt-2">
                <h4 class="text-uppercase">টেস্ট প্রোডাক্ট ২</h4>
                <div class="mt-5">
                    <h5 class="text-uppercase mb-0">বিবরণ ২</h5>
                    <h1 class="main-heading mt-0">বিবরণ ২</h1>
                    <div class="d-flex flex-row user-ratings">
                        <div class="ratings">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <h6 class="text-muted ml-1">4/5</h6>
                    </div>
                </div>
            </div>
            <div class="image">
                <img src="https://i.imgur.com/MGorDUi.png" width="130">
            </div>
        </div>
    
        <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
            <big>৳ ১.০০</big>
            <div class="colors">
    
            </div>
    
        </div>
    
        {{-- <a href="{{ route('bkash-prod-test-payment', '1.00') }}" class="btn btn-danger" id="bKash_button" style="background: url({{ asset('images/bkash_payment_logo.png') }}); background-size: 100%; background-size: 250px auto; background-repeat: no-repeat;">
        </a> --}}
        <form method="post" action="{{ route('bkash-prod-test-payment') }}">
            @csrf
            <input type="hidden" name="amount" value="1.00">
            <button class="btn btn-danger" id="bKash_button" style="background:  #FFFFFF;">
                <img style="width: 210px; heigh: auto;" src="{{ asset('images/bkash_payment_logo.png') }}">
            </button>
        </form>
    </div>
    <div style="width:20px"></div>

    <div class="card p-3">
    
        <div class="d-flex justify-content-between align-items-center ">
            <div class="mt-2">
                <h4 class="text-uppercase">টেস্ট প্রোডাক্ট ৩</h4>
                <div class="mt-5">
                    <h5 class="text-uppercase mb-0">বিবরণ ৩</h5>
                    <h1 class="main-heading mt-0">বিবরণ ৩</h1>
                    <div class="d-flex flex-row user-ratings">
                        <div class="ratings">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <h6 class="text-muted ml-1">4/5</h6>
                    </div>
                </div>
            </div>
            <div class="image">
                <img src="https://i.imgur.com/MGorDUi.png" width="130">
            </div>
        </div>
    
        <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
            <big>৳ ৫.০০</big>
            <div class="colors">
    
            </div>
    
        </div>
        
        <form method="post" action="{{ route('bkash-prod-test-payment') }}">
            @csrf
            <input type="hidden" name="amount" value="200.00">
            <button class="btn btn-danger" id="bKash_button" style="background:  #FFFFFF;">
                <img style="width: 210px; heigh: auto;" src="{{ asset('images/bkash_payment_logo.png') }}">
            </button>
        </form>
    </div>
</div>

<style type="text/css">
        body{
            background-color:#dce3f0;
        }

        .height{
            
            height:100vh;
        }

        .card{
            
            width:300px;
            height:320px;
        }

        .image{
            position:absolute;
            right:12px;
            top:10px;
        }

        .main-heading{
            
            font-size:40px;
            color:red !important;
        }

        .ratings i{
            
            color:orange;
            
        }

        .user-ratings h6{
            margin-top:2px;
        }

        .colors{
            display:flex;
            margin-top:2px;
        }

        .colors span{
            width:15px;
            height:15px;
            border-radius:50%;
            cursor:pointer;
            display:flex;
            margin-right:6px;
        }

        .colors span:nth-child(1) {
            
            background-color:red;
            
        }

        .colors span:nth-child(2) {
            
            background-color:blue;
            
        }

        .colors span:nth-child(3) {
            
            background-color:yellow;
            
        }

        .colors span:nth-child(4) {
            
            background-color:purple;
            
        }

        .btn-danger{
            
            height:48px;
            font-size:18px;
        }
</style>