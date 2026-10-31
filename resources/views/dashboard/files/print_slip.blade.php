<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>শুনানি স্লিপ - {{ $file->case_number ?? 'রুজু হয়নি' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'SolaimanLipi', 'Nikosh', 'Kalpurush', sans-serif; }
        
        body { 
            background: #f1f5f9; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            padding: 10px; 
        }

        /* POS থার্মাল প্রিন্টার কম্প্যাক্ট স্লিপ বক্স (80mm/58mm Friendly) */
        .pos-slip {
            background: #fff;
            width: 72mm;
            padding: 4mm 3mm;
            border: 1px dashed #000;
            color: #000;
        }

        .header { text-align: center; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 6px; }
        .header h3 { font-size: 14px; font-weight: bold; line-height: 1.1; margin-bottom: 2px; }
        .header h4 { font-size: 11px; font-weight: bold; margin-bottom: 3px; }
        .header p { font-size: 10px; border-top: 1px dashed #000; padding-top: 2px; font-weight: bold; }

        .info-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .info-table td { padding: 3px 0; vertical-align: top; font-size: 11px; line-height: 1.25; border-bottom: 1px dotted #ccc; }
        .info-table tr:last-child td { border-bottom: none; }
        
        .qr-section { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            margin-top: 8px; 
            padding-top: 6px; 
            border-top: 1px solid #000; 
        }

        #qrcode canvas, #qrcode img {
            display: block;
            width: 75px !important;
            height: 75px !important;
        }

        .btn-print {
            background: #059669; color: white; border: none; padding: 8px 16px;
            font-size: 13px; font-weight: bold; border-radius: 5px; cursor: pointer; margin-bottom: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none !important; }
            .pos-slip { border: none; width: 100%; padding: 0; }
            @page { margin: 0; size: auto; }
        }
    </style>
    <!-- Pure JS QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <!-- প্রিন্ট বাটন (প্রিন্টে আসবে না) -->
    <button class="btn-print no-print" onclick="window.print()">🖨️ স্লিপ প্রিন্ট করুন</button>

    <div class="pos-slip">
        <div class="header">
            <h3>উপজেলা ভূমি অফিস</h3>
            <h4>ভৈরব, কিশোরগঞ্জ</h4>
            <p>শুনানি প্রাপ্তিস্বীকার নোটিশ</p>
        </div>

        <table class="info-table">
            <tr>
                <td width="40%"><strong>ধরন:</strong></td>
                <td>{{ $file->type }}</td>
            </tr>
            <tr>
                <td><strong>মামলা নম্বর:</strong></td>
                <td>
                    <strong>{{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}</strong>
                </td>
            </tr>

            <!-- বাদি থাকলে দেখাবে -->
            @if(!empty($file->badi_name))
            <tr>
                <td><strong>বাদি:</strong></td>
                <td>
                    {{ $file->badi_name }}
                    @if(!empty($file->badi_mobile))
                        <br><small>({{ bangla($file->badi_mobile) }})</small>
                    @endif
                </td>
            </tr>
            @endif

            <!-- বিবাদি থাকলে দেখাবে -->
            @if(!empty($file->bibadi_name))
            <tr>
                <td><strong>বিবাদি:</strong></td>
                <td>
                    {{ $file->bibadi_name }}
                    @if(!empty($file->bibadi_mobile))
                        <br><small>({{ bangla($file->bibadi_mobile) }})</small>
                    @endif
                </td>
            </tr>
            @endif

            <!-- তদন্তে/যার ডেস্কে বর্তমানে আছে -->
            <tr>
                <td><strong>তদন্তে/অবস্থান:</strong></td>
                <td>
                    {{ $file->currentDesk->designation ?? ($file->currentDesk->name ?? 'অজ্ঞাত') }}
                </td>
            </tr>

            <tr>
                <td><strong>আদেশের তারিখ:</strong></td>
                <td>{{ bangla(date('d/m/Y')) }}</td>
            </tr>
            <tr>
                <td><strong>পরবর্তী তারিখ:</strong></td>
                <td>
                    <strong style="font-size: 13px;">
                        {{ $file->next_hearing_date ? bangla(date('d/m/Y', strtotime($file->next_hearing_date))) : 'ধার্য হয়নি' }}
                    </strong>
                </td>
            </tr>
            <tr>
                <td><strong>অবস্থা:</strong></td>
                <td>{{ $file->status }}</td>
            </tr>
        </table>

        <!-- QR Code Area -->
        <div class="qr-section">
            <div id="qrcode"></div>
        </div>
    </div>

    <script>
        // UUID দিয়ে QR Code জেনারেট
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $file->uuid }}",
            width: 75,
            height: 75,
            correctLevel: QRCode.CorrectLevel.M
        });
    </script>
</body>
</html>