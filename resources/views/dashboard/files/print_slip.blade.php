<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>শুনানি স্লিপ - {{ $file->case_number ?? 'রুজু হয়নি' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'SolaimanLipi', 'Nikosh', 'Kalpurush', sans-serif; }
        body { background: #f1f5f9; display: flex; flex-direction: column; align-items: center; padding: 0; }
        
        .pos-slip {
        	background: #fff;
            width: 56mm;
            padding: 1.5mm 1mm;
            border: 1px solid #ccc;
        }

        .header { text-align: center; border-bottom: 1px solid #000; padding-bottom: 3px; margin-bottom: 4px; }
        .header h3 { font-size: 13px; font-weight: bold; line-height: 1.1; }
        .header h4 { font-size: 10px; font-weight: bold; }
        .header p { font-size: 9.5px; border-top: 1px dashed #000; margin-top: 2px; padding-top: 1px; font-weight: bold; }

        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 2px 0; vertical-align: top; font-size: 10.5px; line-height: 1.2; border-bottom: 1px dotted #ccc; }
        .info-table tr:last-child td { border-bottom: none; }
        
        .qr-section { 
            display: flex; 
            justify-content: center; 
            margin-top: 4px; 
            padding-top: 4px; 
            border-top: 1px solid #000; 
        }

        #qrcode canvas, #qrcode img {
            display: block;
            width: 70px !important;
            height: 70px !important;
        }

        .btn-print {
            background: #059669; color: white; border: none; padding: 6px 12px;
            font-size: 12px; font-weight: bold; border-radius: 4px; cursor: pointer; margin: 10px 0;
        }

        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none !important; }
            .pos-slip { border: none; width: 56mm; padding: 1mm 1mm; }
            @page { size: 58mm auto; margin: 0mm; }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <button class="btn-print no-print" onclick="window.print()">🖨️ প্রিন্ট করুন</button>

    <div class="pos-slip">
        <div class="header">
            <h3>উপজেলা ভূমি অফিস</h3>
            <h4>ভৈরব, কিশোরগঞ্জ</h4>
            <p>শুনানি প্রাপ্তিস্বীকার নোটিশ</p>
        </div>

        @php
            // ইংরেজি বারকে বাংলায় রূপান্তর
            function getBanglaDay($dateStr) {
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

        <table class="info-table">
            <tr>
                <td width="38%"><strong>ধরন:</strong></td>
                <td>{{ $file->type }}</td>
            </tr>
            <tr>
                <td><strong>মামলা নম্বর:</strong></td>
                <td><strong>{{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}</strong></td>
            </tr>

            @if(!empty($file->badi_name))
            <tr>
                <td><strong>বাদি:</strong></td>
                <td>
                    {{ $file->badi_name }}
                    @if(!empty($file->badi_mobile)) ({{ bangla($file->badi_mobile) }}) @endif
                </td>
            </tr>
            @endif

            @if(!empty($file->bibadi_name))
            <tr>
                <td><strong>বিবাদি:</strong></td>
                <td>
                    {{ $file->bibadi_name }}
                    @if(!empty($file->bibadi_mobile)) ({{ bangla($file->bibadi_mobile) }}) @endif
                </td>
            </tr>
            @endif

            <tr>
                <td><strong>তদন্তে/অবস্থান:</strong></td>
                <td>{{ $file->currentDesk->designation ?? ($file->currentDesk->name ?? 'অজ্ঞাত') }}</td>
            </tr>

            <tr>
                <td><strong>আদেশের তারিখ:</strong></td>
                <td>{{ bangla(date('d/m/Y')) }} ({{ getBanglaDay(date('Y-m-d')) }})</td>
            </tr>
            <tr>
                <td><strong>পরবর্তী তারিখ:</strong></td>
                <td>
                    <strong>
                        @if($file->next_hearing_date)
                            {{ bangla(date('d/m/Y', strtotime($file->next_hearing_date))) }} ({{ getBanglaDay($file->next_hearing_date) }})
                        @else
                            ধার্য হয়নি
                        @endif
                    </strong>
                </td>
            </tr>
            <tr>
                <td><strong>অবস্থা:</strong></td>
                <td>{{ $file->status }}</td>
            </tr>
        </table>

        <div class="qr-section">
            <div id="qrcode"></div>
        </div>
    </div>

    <script>
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $file->uuid }}",
            width: 70,
            height: 70,
            correctLevel: QRCode.CorrectLevel.M
        });
    </script>
</body>
</html>