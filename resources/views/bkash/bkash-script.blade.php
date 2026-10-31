<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    // $("#bKash_button").trigger("click");
    function BkashPayment() {
        showLoading();
        // get token
        $.ajax({
            url: "{{ route('bkash-get-token') }}",
            type: 'POST',
            contentType: 'application/json',
            success: function (data) {
                $('pay-with-bkash-button').trigger('click');
                if (data.hasOwnProperty('msg')) {
                    showErrorMessage(data) // unknown error
                }
            },
            error: function (err) {
                hideLoading();
                showErrorMessage(err);
            }
        });
    }
    let paymentID = '';
    bKash.init({
            paymentMode: 'checkout',
            paymentRequest: {},
            createRequest: function (request) {
                setTimeout(function () {
                    createPayment(request);
                }, 2000)
            },
            executeRequestOnAuthorization: function (request) {
                $.ajax({
                    url: '{{ route('bkash-execute-payment') }}',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        "paymentID": paymentID
                    }),
                    success: function (data) {
                        if (data) {
                            if (data.paymentID != null) {
                                {{-- window.location.replace('{{ route('bkash-success-page') }}'); --}}
                                BkashSuccess(data);
                            } else {
                                showErrorMessage(data);
                                bKash.execute().onError();
                            }
                        } else {
                            $.get('{{ route('bkash-query-payment') }}', {
                                payment_info: {
                                    payment_id: paymentID
                                }
                            }, function (data) {
                                if (data.transactionStatus === 'Completed') {
                                    BkashSuccess(data);
                                } else {
                                    createPayment(request);
                                }
                            });
                        }
                    },
                    error: function (err) {
                        bKash.execute().onError();
                    }
                });
            },
            onClose: function () {
                window.location.replace('{{ route('bkash-cancel-page') }}');
                // for error handle after close bKash Popup
            }
        });
        function createPayment(request) {
            // Amount already checked and verified by the controller
            // because of createRequest function finds amount from this request
            {{--request['amount'] = "{{ Session::get('bkash')['invoice_amount'] }}";--}} 
            request['amount'] = {{ $amount ? $amount : 0 }}; // max two decimal points allowed
            $.ajax({
                url: '{{ route('bkash-create-payment') }}',
                data: JSON.stringify(request),
                type: 'POST',
                contentType: 'application/json',
                success: function (data) {
                    
                    if (data && data.paymentID != null) {
                        paymentID = data.paymentID;
                        bKash.create().onSuccess(data);
                        hideLoading();
                    } else {
                        bKash.create().onError();
                        hideLoading();
                    }
                },
                error: function (err) {
                    hideLoading();
                    showErrorMessage(err.responseJSON);
                    bKash.create().onError();
                }
            });
        }
        {{-- function BkashSuccess(data) {
            $.post('{{ route('bkash-success') }}', {
                payment_info: data,
                mobile: '{{ $mobile }}',
                package_id: '{{ $packageid }}'
            }, function (res) {
                alert(JSON.stringify(res));
                // location.reload();
                if(res.status == true) {
                    window.location.replace('{{ route('bkash-success-page') }}');
                } else {
                    window.location.replace('{{ route('bkash-failed-page') }}');
                }
                
            });
        } --}}

        function BkashSuccess(data) {
            // পেমেন্ট হওয়ার পর ইউজার যাতে বারবার ক্লিক না করে বা পেজ না ছাড়ে সেজন্য একটি লোডার দেখানো ভালো
            {{-- console.log("Payment Info Received:", data); --}}

            $.post('{{ route('bkash-success') }}', {
                _token: '{{ csrf_token() }}', // লারাভেল সিকিউরিটির জন্য সিএসআরএফ টোকেন
                payment_info: data,
                mobile: '{{ $mobile }}',
                package_id: '{{ $packageid }}',
                referral_code: '{{ $referralcode ?? '' }}' // যদি প্রোমো কোড থাকে তবে পাঠাবে
            })
            .done(function (res) {
                // লজিক্যাল সাকসেস বা ফেইল (লারাভেল থেকে স্ট্যাটাস ট্রু/ফলস আসলে)
                {{-- console.log("Server Response:", res); --}}
                
                if(res.status == true) {
                    // পেমেন্ট এবং ডাটাবেস আপডেট সফল
                    window.location.replace('{{ route('bkash-success-page') }}');
                } else {
                    // লারাভেল থেকে লজিক্যাল এরর আসলে (যেমন: ইউজার পাওয়া যায়নি)
                    {{-- alert("ত্রুটি: " + (res.message || "পেমেন্ট প্রসেস করা সম্ভব হয়নি।")); --}}
                    window.location.replace('{{ route('bkash-failed-page') }}');
                }
            })
            .fail(function (xhr, status, error) {
                // সার্ভার যদি ৫00 এরর দেয় বা নেটওয়ার্ক চলে যায়
                {{-- console.error("Server Crash Error:", xhr.responseText); --}}
                
                let errorMsg = "সার্ভারে সমস্যা হয়েছে। দয়া করে আপনার ট্রানজেকশন আইডিটি সংরক্ষণ করুন।";
                try {
                    let jsonErr = JSON.parse(xhr.responseText);
                    if(jsonErr.message) errorMsg = jsonErr.message;
                } catch(e) {}

                {{-- alert("সিস্টেম এরর: " + errorMsg); --}}
                window.location.replace('{{ route('bkash-failed-page') }}');
            });
        }

        function showErrorMessage(response) {
            let message = 'Unknown Error';
            if (response.hasOwnProperty('errorMessage')) {
                let errorCode = parseInt(response.errorCode);
                let bkashErrorCode = [2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014,
                    2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030,
                    2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040, 2041, 2042, 2043, 2044, 2045, 2046,
                    2047, 2048, 2049, 2050, 2051, 2052, 2053, 2054, 2055, 2056, 2057, 2058, 2059, 2060, 2061, 2062,
                    2063, 2064, 2065, 2066, 2067, 2068, 2069, 503,
                ];
                if (bkashErrorCode.includes(errorCode)) {
                    message = response.errorMessage
                }
            }
            Swal.fire("Payment Failed!", message, "error");
        }
        function showLoading() {
            $("body").addClass("loading");
        }
        function hideLoading() {
            $("body").removeClass("loading"); 
            $("#changeTextonLoad").text("Wait! Do NOT close the page!"); 
        }
</script>