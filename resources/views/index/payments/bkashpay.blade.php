<title>bKash Payment Gateway</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script id="myScript" src="https://scripts.pay.bka.sh/versions/1.2.0-beta/checkout/bKash-checkout.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">


<style>
    .swal-container, .swal2-container {
        z-index: 2000;
    }
    .overlay{
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(224,34,95,0.3) url("/images/Spinner-1s-200px.gif") center no-repeat;
    }
    body{
        text-align: center;
    }
    /* Turn off scrollbar when body element has the loading class */
    body.loading{
        overflow: hidden;   
    }
    /* Make spinner image visible when body element has the loading class */
    body.loading .overlay{
        display: block;
    }
</style>

<div class="overlay"></div>
<div class="height d-flex justify-content-center align-items-center">
    
    <div class="card p-3">
        <center><big>পেমেন্ট ৳ {{ bangla($amount) }}</big></center>
        <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
        </div>
        
        <button class="btn btn-danger" id="bKash_button" onclick="BkashPayment()" style="background: url({{ asset('images/bkash_payment_logo.png') }}); background-size: 100%; background-size: 250px auto; background-repeat: no-repeat;">
        </button>
    </div>
</div>


@include('index.payments.bkashscript')

<style type="text/css">
        body{
            background-color:#dce3f0;
        }

        .height{
            
            height:100vh;
        }

        .card{
            
            width:300px;
            height:150px;
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