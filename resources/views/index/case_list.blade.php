<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>বিবিধ মোকদ্দমার হালনাগাদ তথ্য - উপজেলা ভূমি অফিস, ভৈরব</title>
    
    <!-- Bootstrap 4 CSS & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body {
            font-family: 'SolaimanLipi', 'Nikosh', 'Kalpurush', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            padding: 15px;
        }
        .header-card {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            color: #ffffff;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .search-box {
            background: #ffffff;
            border-radius: 6px;
            padding: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
        .table-responsive {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
        }
        .table {
            margin-bottom: 0;
            font-size: 14px;
        }
        .table thead th {
            background-color: #065f46;
            color: #ffffff;
            border: none;
            font-weight: 600;
            white-space: nowrap;
            vertical-align: middle;
        }
        .table tbody td {
            vertical-align: middle;
            border-color: #f1f5f9;
        }
        .table-hover tbody tr:hover {
            background-color: #f0fdf4;
        }
        .badge-hearing {
            background-color: #fee2e2;
            color: #991b1b;
            font-size: 13px;
            padding: 5px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .badge-desk {
            background-color: #e0f2fe;
            color: #0369a1;
            font-size: 12px;
            padding: 4px 7px;
            border-radius: 4px;
        }
        /* এমবেড / আইফ্রেম অপটিমাইজেশন */
        @media print {
            .search-box, .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0">
        <!-- হেডার অংশ -->
        <div class="header-card text-center text-md-left d-md-flex align-items-center justify-content-between">
            <div>
                <h4 class="font-weight-bold m-0"><i class="fas fa-landmark mr-2"></i>উপজেলা ভূমি অফিস, ভৈরব, কিশোরগঞ্জ</h4>
                <p class="mb-0 mt-1" style="opacity: 0.9; font-size: 14px;">রুজুকৃত বিবিধ মোকদ্দমাসমূহের হালনাগাদ তথ্য ও শুনানির সময়সূচি</p>
            </div>
            <div class="mt-2 mt-md-0 text-md-right">
                <span class="badge badge-light px-3 py-2 text-dark font-weight-bold">
                    <i class="fas fa-list-ol mr-1 text-success"></i> মোট মামলা: <span id="total-count">{{ bangla($files->count()) }}</span> টি
                </span>
            </div>
        </div>

        <!-- লাইভ অনুসন্ধান বক্স (জাভাস্ক্রিপ্ট) -->
        <div class="search-box">
            <div class="row align-items-center">
                <div class="col-md-8 col-sm-12 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0 text-muted"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" id="caseSearchInput" class="form-control border-left-0 pl-0" placeholder="মোকদ্দমা নং, বাদি, বিবাদি, তারিখ বা গতিবিধি দিয়ে খুঁজুন..." onkeyup="searchCaseTable()">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 text-md-right text-muted small">
                    <i class="fas fa-info-circle mr-1 text-info"></i> যেকোনো তথ্য মুহূর্তেই ফিল্টার করতে টাইপ করুন
                </div>
            </div>
        </div>

        <!-- ডাটা টেবিল -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="caseDataTable">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">ক্রমিক</th>
                        <th width="15%">বিবিধ মোকদ্দমা নং</th>
                        <th width="18%">বাদি</th>
                        <th width="18%">বিবাদি</th>
                        <th width="11%" class="text-center">রুজুর তারিখ</th>
                        <th width="13%">নথির গতিবিধি</th>
                        <th width="12%" class="text-center">পরবর্তী শুনানি</th>
                        <th width="8%">মন্তব্য</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($files as $index => $file)
                        <tr>
                            <td class="text-center font-weight-bold text-muted">{{ bangla($index + 1) }}</td>
                            <td>
                                <strong class="text-primary">{{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}</strong>
                                <small class="d-block text-muted">ধরন: {{ $file->type }}</small>
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $file->badi_name ?? '-' }}</div>
                                @if($file->badi_mobile)
                                    <small class="text-muted"><i class="fas fa-phone-alt mr-1"></i>{{ bangla($file->badi_mobile) }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $file->bibadi_name ?? '-' }}</div>
                                @if($file->bibadi_mobile)
                                    <small class="text-muted"><i class="fas fa-phone-alt mr-1"></i>{{ bangla($file->bibadi_mobile) }}</small>
                                @endif
                            </td>
                            <td class="text-center small">
                                {{ bangla(date('d/m/Y', strtotime($file->created_at))) }}
                            </td>
                            <td>
                                <span class="badge-desk">
                                    <i class="fas fa-user-check mr-1"></i>{{ $file->currentDesk->designation ?? ($file->currentDesk->name ?? 'ডেস্ক নির্ধারিত নয়') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($file->next_hearing_date)
                                    <span class="badge-hearing d-inline-block mb-1">
                                        <i class="far fa-calendar-alt mr-1"></i>{{ bangla(date('d/m/Y', strtotime($file->next_hearing_date))) }}
                                    </span>
                                @else
                                    <span class="text-muted small">ধার্য হয়নি</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-dark">{{ $file->status ?? '-' }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr id="no-data-row">
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2 text-secondary d-block"></i>
                                বর্তমানে কোনো বিবিধ মোকদ্দমার তথ্য পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ক্লায়েন্ট সাইড জাভাস্ক্রিপ্ট সার্চ ফিল্টার (এমবেড ফ্রেন্ডলি) -->
    <script>
        function searchCaseTable() {
            var input = document.getElementById("caseSearchInput");
            var filter = input.value.toLowerCase().trim();
            var table = document.getElementById("caseDataTable");
            var tr = table.getElementsByTagName("tr");
            var visibleCount = 0;

            // হেডার বাদ দিয়ে ডাটা রো লুপ
            for (var i = 1; i < tr.length; i++) {
                // নো-ডাটা রো ইগনোর করবে
                if (tr[i].id === "no-data-row") continue;

                var tdText = tr[i].textContent || tr[i].innerText;
                
                if (tdText.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    visibleCount++;
                } else {
                    tr[i].style.display = "none";
                }
            }

            // দেখা যাওয়া রো-এর তথ্য বাংলায় কাউন্ট করবে
            document.getElementById("total-count").innerText = toBanglaNumber(visibleCount);
        }

        // ইংরেজি সংখ্যাকে বাংলায় রূপান্তর
        function toBanglaNumber(num) {
            var banglaDigits = {'0':'০', '1':'১', '2':'২', '3':'৩', '4':'৪', '5':'৫', '6':'৬', '7':'৭', '8':'৮', '9':'৯'};
            return num.toString().replace(/[0-9]/g, function(w) {
                return banglaDigits[w];
            });
        }
    </script>
</body>
</html>