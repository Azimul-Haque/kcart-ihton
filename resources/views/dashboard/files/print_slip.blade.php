<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>শুনানি স্লিপ - {{ $file->case_number ?? 'File' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'SolaimanLipi', 'Nikosh', 'Kalpurush', sans-serif; }
        body { background: #f1f5f9; display: flex; flex-direction: column; align-items: center; padding: 20px; }
        
        .slip-box { 
            background: #fff;
            border: 2px dashed #334155; 
            padding: 25px; 
            width: 100%;
            max-width: 600px; 
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 18px; }
        .header h3 { margin: 0; font-size: 22px; font-weight: bold; color: #1e293b; }
        .header p { margin: 4px 0 0 0; font-size: 15px; color: #475569; font-weight: bold; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 8px 0; vertical-align: top; font-size: 15px; border-bottom: 1px dashed #e2e8f0; }
        .info-table td strong { color: #334155; }
        
        .qr-area { 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 15px; 
            border-top: 1px solid #cbd5e1; 
            padding-top: 15px; 
        }

        #qrcode canvas, #qrcode img {
            display: block;
            width: 100px !important;
            height: 100px !important;
        }

        .uuid-text {
            font-size: 12px;
            font-family: monospace;
            color: #64748b;
            margin-top: 6px;
        }

        .btn-print {
            background: #059669; color: white; border: none; padding: 10px 24px;
            font-size: 15px; font-weight: bold; border-radius: 6px; cursor: pointer; margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: background 0.2s;
        }
        .btn-print:hover { background: #047857; }

        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none !important; }
            .slip-box { border: 1px solid #000; box-shadow: none; max-width: 100%; }
            @page { margin: 15mm; }
        }
    </style>
    <!-- Pure JS QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <!-- প্রিন্ট বাটন (প্রিন্টে আসবে না) -->
    <button class="btn-print no-print" onclick="window.print()">🖨️ স্লিপ প্রিন্ট করুন (Print Slip)</button>

    <div class="slip-box">
        <div class="header">
            <h3>উপজেলা ভূমি অফিস, ভৈরব, কিশোরগঞ্জ</h3>
            <p>শুনানি গ্রহণের প্রাপ্তিস্বীকার ও নোটিশ স্লিপ</p>
        </div>

        <table class="info-table">
            <tr>
                <td width="35%"><strong>নথি / মামলা নং:</strong></td>
                <td>{{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }} ({{ $file->type }})</td>
            </tr>
            <tr>
                <td><strong>বাদির নাম:</strong></td>
                <td>{{ $file->badi_name ?? 'N/A' }} {{ $file->badi_mobile ? '('.bangla($file->badi_mobile).')' : '' }}</td>
            </tr>
            <tr>
                <td><strong>বিবাদির নাম:</strong></td>
                <td>{{ $file->bibadi_name ?? 'N/A' }} {{ $file->bibadi_mobile ? '('.bangla($file->bibadi_mobile).')' : '' }}</td>
            </tr>
            <tr>
                <td><strong>সর্বশেষ আদেশের তারিখ:</strong></td>
                <td>{{ bangla(date('d/m/Y')) }}</td>
            </tr>
            <tr>
                <td><strong>পরবর্তী ধার্য তারিখ:</strong></td>
                <td>
                    <strong style="font-size: 18px; color: #dc2626;">
                        {{ $file->next_hearing_date ? bangla(date('d/m/Y', strtotime($file->next_hearing_date))) : 'ধার্য হয়নি' }}
                    </strong>
                </td>
            </tr>
            <tr>
                <td><strong>নথির বর্তমান অবস্থা:</strong></td>
                <td>{{ $file->status }}</td>
            </tr>
        </table>

        <!-- JavaScript দিয়ে জেনারেট হওয়া কিউআর কোড -->
        <div class="qr-area">
            <div id="qrcode"></div>
            <div class="uuid-text">
                <code>UUID: {{ $file->uuid }}</code>
            </div>
        </div>
    </div>

    <script>
        // UUID দিয়ে জাভাস্ক্রিপ্ট QR Code জেনারেট
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $file->uuid }}",
            width: 100,
            height: 100,
            correctLevel: QRCode.CorrectLevel.M
        });
    </script>
</body>
</html>