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
            padding: 10px;
        }
        .header-card {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            color: #ffffff;
            border-radius: 6px;
            padding: 10px 15px;
            margin-bottom: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }
        .search-box {
            background: #ffffff;
            border-radius: 6px;
            padding: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 12px;
        }
        .table-responsive {
            background: #ffffff;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }
        /* আল্ট্রা-কম্প্যাক্ট টেবিল স্টাইল */
        .table-compact {
            margin-bottom: 0;
            font-size: 13px;
        }
        .table-compact thead th {
            background-color: #065f46;
            color: #ffffff;
            border: none;
            font-weight: 600;
            padding: 6px 8px;
            white-space: nowrap;
            vertical-align: middle;
        }
        .table-compact tbody td {
            vertical-align: middle;
            border-color: #f1f5f9;
            padding: 5px 8px;
            line-height: 1.3;
        }
        .table-hover tbody tr:hover {
            background-color: #f0fdf4;
        }
        .badge-hearing {
            background-color: #fee2e2;
            color: #991b1b;
            font-size: 11.5px;
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: bold;
            white-space: nowrap;
        }
        .badge-desk {
            background-color: #e0f2fe;
            color: #0369a1;
            font-size: 11.5px;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
        }
        @media (max-width: 576px) {
            body { padding: 5px; }
            .header-card { padding: 8px 12px; }
            .header-card h5 { font-size: 16px; }
            .table-compact { font-size: 12px; }
        }
        @media print {
            .search-box, .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    @php
        // ইংরেজি দিন নামকে বাংলায় রূপান্তর
        function getBanglaDayName($dateStr) {
            if (!$dateStr) return '';
            $days = [
                'Sunday' => 'রবিবার',
                'Monday' => 'সোমবার',
                'Tuesday' => 'মঙ্গলবার',
                'Wednesday' => 'বুধবার',
                'Thursday' => 'বৃহস্পতিবার',
                'Friday' => 'শুক্রবার',
                'Saturday' => 'শনিবার'
            ];
            $dayName = date('l', strtotime($dateStr));
            return $days[$dayName] ?? '';
        }
    @endphp

    <div class="container-fluid p-0">
        <!-- হেডার অংশ -->
        <div class="header-card d-flex align-items-center justify-content-between">
            <div>
                <h5 class="font-weight-bold m-0"><i class="fas fa-landmark mr-1"></i>উপজেলা ভূমি অফিস, ভৈরব</h5>
                <small style="opacity: 0.9;">বিবিধ মোকদ্দমার হালনাগাদ তথ্য ও শুনানির সময়সূচি</small>
            </div>
            <div>
                <span class="badge badge-light px-2 py-1 text-dark font-weight-bold" style="font-size: 12px;">
                    মোট: <span id="total-count">{{ bangla($files->count()) }}</span> টি
                </span>
            </div>
        </div>

        <!-- লাইভ অনুসন্ধান বক্স (জাভাস্ক্রিপ্ট) -->
        <div class="search-box">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0 text-muted"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" id="caseSearchInput" class="form-control border-left-0 pl-0" placeholder="মোকদ্দমা নং, বাদি, বিবাদি, শুনানি দিন বা গতিবিধি দিয়ে খুঁজুন..." onkeyup="searchCaseTable()">
            </div>
        </div>

        <!-- আল্ট্রা-কম্প্যাক্ট ডাটা টেবিল -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-compact" id="caseDataTable">
                <thead>
                    <tr>
                        <th width="4%" class="text-center">ক্রমিক</th>
                        <th width="16%">বিবিধ মোকদ্দমা নং</th>
                        <th width="20%">বাদি</th>
                        <th width="20%">বিবাদি</th>
                        <th width="16%">নথির গতিবিধি</th>
                        <th width="16%" class="text-center">পরবর্তী শুনানির তারিখ</th>
                        <th width="8%">মন্তব্য</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($files as $index => $file)
                        <tr>
                            <td class="text-center font-weight-bold text-muted">{{ bangla($index + 1) }}</td>
                            <td>
                                <strong class="text-primary">{{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}</strong>
                            </td>
                            <td>
                                <span class="font-weight-bold text-dark">{{ $file->badi_name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="font-weight-bold text-dark">{{ $file->bibadi_name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge-desk">
                                    <i class="fas fa-user-circle mr-1"></i>{{ $file->currentDesk->designation ?? ($file->currentDesk->name ?? 'অজ্ঞাত') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($file->next_hearing_date)
                                    <span class="badge-hearing">
                                        <i class="far fa-calendar-alt mr-1"></i>{{ bangla(date('d/m/Y', strtotime($file->next_hearing_date))) }} ({{ getBanglaDayName($file->next_hearing_date) }})
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
                            <td colspan="7" class="text-center py-3 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-1 text-secondary d-block"></i>
                                বর্তমানে কোনো বিবিধ মোকদ্দমার তথ্য পাওয়া যায়নি।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ক্লায়েন্ট সাইড জাভাস্ক্রিপ্ট সার্চ (এমবেড ফ্রেন্ডলি) -->
    <script>
        function searchCaseTable() {
            var input = document.getElementById("caseSearchInput");
            var filter = input.value.toLowerCase().trim();
            var table = document.getElementById("caseDataTable");
            var tr = table.getElementsByTagName("tr");
            var visibleCount = 0;

            for (var i = 1; i < tr.length; i++) {
                if (tr[i].id === "no-data-row") continue;

                var tdText = tr[i].textContent || tr[i].innerText;
                
                if (tdText.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    visibleCount++;
                } else {
                    tr[i].style.display = "none";
                }
            }

            document.getElementById("total-count").innerText = toBanglaNumber(visibleCount);
        }

        function toBanglaNumber(num) {
            var banglaDigits = {'0':'০', '1':'১', '2':'২', '3':'৩', '4':'৪', '5':'৫', '6':'৬', '7':'৭', '8':'৮', '9':'৯'};
            return num.toString().replace(/[0-9]/g, function(w) {
                return banglaDigits[w];
            });
        }
    </script>
</body>
</html>