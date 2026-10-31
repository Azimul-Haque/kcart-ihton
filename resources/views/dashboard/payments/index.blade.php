@extends('layouts.app')
@section('title') ড্যাশবোর্ড | পেমেন্ট @endsection

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
@endsection

@section('content')
	@section('page-header') পেমেন্ট @endsection
    <div class="container-fluid">
		<div class="card">
          <div class="card-header">
            <h3 class="card-title">পেমেন্ট তালিকা</h3>

            <div class="card-tools">
            	{{-- <button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addPackageModal" title="" rel="tooltip" data-original-title="পেমেন্ট যোগ করুন">
            		<i class="fas fa-clipboard-check"></i> নতুন পেমেন্ট
            	</button> --}}
              <div class="card-tools">
                <form class="form-inline form-group-lg" action="">
                  <div class="form-group">
                    <input type="search-param" class="form-control form-control-sm" placeholder="পেমেন্ট খুঁজুন" id="search-param" required>
                  </div>
                  <button type="button" id="search-button" class="btn btn-default btn-sm" style="margin-left: 5px;">
                    <i class="fas fa-search"></i> খুঁজুন
                  </button>
                </form>
                
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <table class="table">
              <thead>
                <thead>
                  <th>ব্যবহারকারী</th>
                  <th>প্যাকেজ</th>
                  <th>পেমেন্ট স্ট্যাটাস</th>
                  <th>কার্ডের ধরন</th>
                  <th>ট্রানজেকশন আইডি</th>
                  <th>পরিমাণ</th>
                  <th>সময়</th>
                </thead>
              </thead>
              <tbody>
                @foreach($payments as $payment)
                	<tr>
                    <td>
                      <a href="{{ route('dashboard.users.single', $payment->user->id) }}">{{ $payment->user->name }}</a>
                      <small>({{ $payment->user->payments->count() }} বার)</small><br/>
                      <small class="text-black-50">{{ $payment->user->mobile }}</small>
                    </td>
                    <td>{{ $payment->package->name }}</td>
                    <td>{{ $payment->payment_status == 1 ? 'Successfull' : 'Failed' }}</td>
                    <td>{{ $payment->card_type }}</td>
                    <td>{{ $payment->trx_id }}</td>
                    <td><b>৳ {{ $payment->store_amount }}</b> <small>(৳ {{ $payment->amount }})</small></td>
                		<td>{{ date('F d, Y h:i A', strtotime($payment->created_at)) }}</td>
                	</tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        {{ $payments->links() }}
    </div>
@endsection

@section('third_party_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="module">

      $(document).on('click', '#search-button', function() {
        if($('#search-param').val() != '') {
          var urltocall = '{{ route('dashboard.payments') }}' +  '/' + $('#search-param').val();
          location.href= urltocall;
        } else {
          $('#search-param').css({ "border": '#FF0000 2px solid'});
          Toast.fire({
              icon: 'warning',
              title: 'কিছু লিখে খুঁজুন!'
          })
        }
      });
      $("#search-param").keyup(function(e) {
        if(e.which == 13) {
          if($('#search-param').val() != '') {
            var urltocall = '{{ route('dashboard.payments') }}' +  '/' + $('#search-param').val();
            location.href= urltocall;
          } else {
            $('#search-param').css({ "border": '#FF0000 2px solid'});
            Toast.fire({
                icon: 'warning',
                title: 'কিছু লিখে খুঁজুন!'
            })
          }
        }
      });
      // import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.9.1/firebase-app.js';

      // // import { auth } from 'https://www.gstatic.com/firebasejs/9.9.1/firebase-auth.js';
      // import { doc, getFirestore, collection, getDocs, addDoc, setDoc, runTransaction } from 'https://www.gstatic.com/firebasejs/9.9.1/firebase-firestore.js';

      // const firebaseConfig = {
      //   apiKey: "AIzaSyDW9yf9W-6mYL35nPYW8rfL__-2vMIBsR8",
      //   authDomain: "bjs-exam.firebaseapp.com",
      //   projectId: "bjs-exam",
      //   storageBucket: "bjs-exam.appspot.com",
      //   messagingSenderId: "750423424153",
      //   appId: "1:750423424153:web:ab554cd595960865a30c08",
      //   measurementId: "G-EXSKW5L6GB"
      // };

      // const app = initializeApp(firebaseConfig);
      // const db = getFirestore(app);

      // // WRITE
      // try {
      //   const docRef = await setDoc(doc(db, "packages", "4"), {
      //     name: "বাৎসরিক",
      //     tagline: "১ বছরের জন্য অ্যাপের সব ফিচার অ্যাভেইলেবল থাকবে",
      //     duration: "১ বছর",
      //     price: "349",
      //     strike_price: "600",
      //     status: 1,
      //     suggested: 0
      //   });

      //   console.log("Document written with ID: ", docRef.id);
      // } catch (e) {
      //   console.error("Error adding document: ", e);
      // }

      // // READ
      // const querySnapshot = await getDocs(collection(db, "packages"));
      // var packages = [];
      // querySnapshot.forEach((doc) => {
      //   console.log(`${doc.id} => ${doc.data()}`);
      //   packages.push(doc.data());
      // });
      // console.log(packages);


      // // UPDATE
      // const sfDocRef = doc(db, "packages", "1");
      // try {
      //   await runTransaction(db, async (transaction) => {
      //     const sfDoc = await transaction.get(sfDocRef);
      //     if (!sfDoc.exists()) {
      //       throw "Document does not exist!";
      //     }
      //     transaction.update(sfDocRef, { name: 'মাসিক ২' });
      //   });
      //   console.log("Transaction successfully committed!");
      // } catch (e) {
      //   console.log("Transaction failed: ", e);
      // }

    </script>
@endsection