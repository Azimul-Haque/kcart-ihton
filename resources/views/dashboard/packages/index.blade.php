@extends('layouts.app')
@section('title') ড্যাশবোর্ড | প্যাকেজ @endsection

@section('third_party_stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
@endsection

@section('content')
	@section('page-header') প্যাকেজ @endsection
    <div class="container-fluid">
		<div class="card">
          <div class="card-header">
            <h3 class="card-title">প্যাকেজ তালিকা</h3>

            <div class="card-tools">
            	<button type="button" class="btn btn-success btn-sm"  data-toggle="modal" data-target="#addPackageModal" title="" rel="tooltip" data-original-title="প্যাকেজ যোগ করুন">
            		<i class="fas fa-clipboard-check"></i> নতুন প্যাকেজ
            	</button>
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
                @foreach($packages as $package)
                	<tr>
                		<td>
                			{{ $package->name }}
                      <span class="badge bg-info"><strike>৳ {{ $package->strike_price }}</strike></span>
                      <span class="badge bg-success">৳ {{ $package->price }}</span>
                      @if($package->status == 1)
                        <span class="badge bg-primary">Active</span>
                      @endif
                      @if($package->suggested == 1)
                        <span class="badge bg-warning"><i class="fas fa-bolt"></i></span>
                      @endif
                			<br/>
                			<small class="text-black-50">{{ $package->tagline }}</small> 
                		</td>
                		<td align="right" width="40%">
                			<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editPackageModal{{ $package->id }}">
                				<i class="fas fa-edit"></i>
                			</button>
            			    {{-- Edit Package Modal Code --}}
            			    {{-- Edit Package Modal Code --}}
            			    <!-- Modal -->
            			    <div class="modal fade" id="editPackageModal{{ $package->id }}" tabindex="-1" role="dialog" aria-labelledby="editPackageModalLabel" aria-hidden="true" data-backdrop="static">
            			      <div class="modal-dialog" role="document">
            			        <div class="modal-content">
            			          <div class="modal-header bg-primary">
            			            <h5 class="modal-title" id="editPackageModalLabel">প্যাকেজ তথ্য হালনাগাদ</h5>
            			            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            			              <span aria-hidden="true">&times;</span>
            			            </button>
            			          </div>
            			          <form method="post" action="{{ route('dashboard.packages.update', $package->id) }}">
				                      <div class="modal-body">
                                
                                    @csrf

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               value="{{ $package->name }}"
                                               placeholder="প্যাকেজের নাম" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-ticket-alt"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="tagline"
                                               class="form-control"
                                               value="{{ $package->tagline }}"
                                               placeholder="ট্যাগ লাইন" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-quote-left"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="duration"
                                               class="form-control"
                                               value="{{ $package->duration }}"
                                               placeholder="মেয়াদ" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-calendar-check"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="number"
                                               name="numeric_duration"
                                               class="form-control"
                                               value="{{ $package->numeric_duration }}"
                                               placeholder="নম্বরে মেয়াদ" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-calendar-check"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="number"
                                               name="price"
                                               value="{{ $package->price }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="মূল্য" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-dollar-sign"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="number"
                                               name="strike_price"
                                               value="{{ $package->strike_price }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="মুদ্রিত মূল্য" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-strikethrough"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                      <select name="status" class="form-control" required>
                                        <option selected="" disabled="" value="">স্ট্যাটাস</option>
                                        <option value="1" @if($package->status == 1) selected @endif>Active</option>
                                        <option value="0" @if($package->status == 0) selected @endif>In-active</option>
                                      </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-toggle-on"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                      <select name="suggested" class="form-control" required>
                                        <option selected="" disabled="" value="">ফিচারড</option>
                                        <option value="1" @if($package->suggested == 1) selected @endif>Yes</option>
                                        <option value="0" @if($package->suggested == 0) selected @endif>No</option>
                                      </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-bolt"></span></div>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="text"
                                               name="image"
                                               value="{{ $package->image }}"
                                               autocomplete="off"
                                               class="form-control"
                                               placeholder="ছবির নাম" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text"><span class="fas fa-image"></span></div>
                                        </div>
                                    </div>
                              </div>
            				          <div class="modal-footer">
            				            <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
            				            <button type="submit" class="btn btn-primary">দাখিল করুন</button>
            				          </div>
            			          </form>
            			        </div>
            			      </div>
            			    </div>
            			    {{-- Edit Package Modal Code --}}
            			    {{-- Edit Package Modal Code --}}

                			<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletePackageModal{{ $package->id }}">
                				<i class="fas fa-trash-alt"></i>
                			</button>
                		</td>
                        {{-- Delete Package Modal Code --}}
                        {{-- Delete Package Modal Code --}}
                        <!-- Modal -->
                        <div class="modal fade" id="deletePackageModal{{ $package->id }}" tabindex="-1" role="dialog" aria-labelledby="deletePackageModalLabel" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-danger">
                                <h5 class="modal-title" id="deletePackageModalLabel">প্যাকেজ ডিলেট</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                আপনি কি নিশ্চিতভাবে এই প্যাকেজটি ডিলেট করতে চান?<br/>
                                <center>
                                    <big><b>{{ $package->name }}</b></big><br/>
                                    <span>৳ {{ $package->price }}</span>
                                </center>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ফিরে যান</button>
                                <a href="{{ route('dashboard.users.delete', $package->id) }}" class="btn btn-danger">ডিলেট করুন</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        {{-- Delete Package Modal Code --}}
                        {{-- Delete Package Modal Code --}}
                	</tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
    </div>

    {{-- Add Package Modal Code --}}
    {{-- Add Package Modal Code --}}
    <!-- Modal -->
    <div class="modal fade" id="addPackageModal" tabindex="-1" role="dialog" aria-labelledby="addPackageModalLabel" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h5 class="modal-title" id="addPackageModalLabel">নতুন প্যাকেজ যোগ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="{{ route('dashboard.packages.store') }}">
	          <div class="modal-body">
	            
	                @csrf

	                <div class="input-group mb-3">
	                    <input type="text"
	                           name="name"
	                           class="form-control"
	                           value="{{ old('name') }}"
	                           placeholder="প্যাকেজের নাম" required>
	                    <div class="input-group-append">
	                        <div class="input-group-text"><span class="fas fa-ticket-alt"></span></div>
	                    </div>
	                </div>

                  <div class="input-group mb-3">
                      <input type="text"
                             name="tagline"
                             class="form-control"
                             value="{{ old('tagline') }}"
                             placeholder="ট্যাগ লাইন" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-quote-left"></span></div>
                      </div>
                  </div>

                  <div class="input-group mb-3">
                      <input type="text"
                             name="duration"
                             class="form-control"
                             value="{{ old('duration') }}"
                             placeholder="মেয়াদ" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-calendar-check"></span></div>
                      </div>
                  </div>

                  <div class="input-group mb-3">
                      <input type="number"
                             name="numeric_duration"
                             class="form-control"
                             value="{{ old('numeric_duration') }}"
                             placeholder="নম্বরে মেয়াদ" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-calendar-check"></span></div>
                      </div>
                  </div>

	                <div class="input-group mb-3">
	                    <input type="number"
	                           name="price"
	                           value="{{ old('price') }}"
	                           autocomplete="off"
	                           class="form-control"
	                           placeholder="মূল্য" required>
	                    <div class="input-group-append">
	                        <div class="input-group-text"><span class="fas fa-dollar-sign"></span></div>
	                    </div>
	                </div>

                  <div class="input-group mb-3">
                      <input type="number"
                             name="strike_price"
                             value="{{ old('strike_price') }}"
                             autocomplete="off"
                             class="form-control"
                             placeholder="মুদ্রিত মূল্য" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-strikethrough"></span></div>
                      </div>
                  </div>

	                <div class="input-group mb-3">
	                	<select name="status" class="form-control" required>
	                		<option selected="" disabled="" value="">স্ট্যাটাস</option>
	                		<option value="1" selected>Active</option>
							        <option value="0">In-active</option>
	                	</select>
	                    <div class="input-group-append">
	                        <div class="input-group-text"><span class="fas fa-toggle-on"></span></div>
	                    </div>
	                </div>

                  <div class="input-group mb-3">
                    <select name="suggested" class="form-control" required>
                      <option selected="" disabled="" value="">ফিচারড</option>
                      <option value="1">Yes</option>
                      <option value="0" selected>No</option>
                    </select>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-bolt"></span></div>
                      </div>
                  </div>

                  <div class="input-group mb-3">
                      <input type="text"
                             name="image"
                             value="{{ old('image') }}"
                             autocomplete="off"
                             class="form-control"
                             placeholder="ছবির নাম" required>
                      <div class="input-group-append">
                          <div class="input-group-text"><span class="fas fa-image"></span></div>
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
    {{-- Add Package Modal Code --}}
    {{-- Add Package Modal Code --}}
@endsection

@section('third_party_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        // $('#adduserrole').change(function () {
        //     if($('#adduserrole').val() == 'accountant') {
        //         $('#ifaccountant').hide();
        //     } else {
        //         $('#ifaccountant').show();
        //     }
        // });
    </script>

    <script type="module">
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