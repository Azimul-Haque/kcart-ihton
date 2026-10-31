<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>QR Sticker - {{ $file->case_number ?? 'File' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Kalpurush', sans-serif; }
        body { background: #f1f5f9; display: flex; flex-direction: column; align-items: center; padding: 20px; }
        
        .sticker-card {
            background: #fff;
            width: 58mm;
            padding: 3mm 2mm;
            border: 1px solid #ccc;
            text-align: center;
            color: #000;
        }

        .header h3 { font-size: 13px; font-weight: bold; margin-bottom: 2px; }
        .header p { font-size: 10px; border-bottom: 1px dashed #000; padding-bottom: 3px; margin-bottom: 5px; }

        .details { font-size: 11px; text-align: left; line-height: 1.3; margin-bottom: 5px; }
        .details div { margin-bottom: 2px; }

        .qr-container { margin: 6px 0; display: flex; justify-content: center; }

        .btn-print {
            background: #059669; color: white; border: none; padding: 10px 20px;
            font-size: 14px; font-weight: bold; border-radius: 6px; cursor: pointer; margin-bottom: 15px;
        }

        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none !important; }
            .sticker-card { border: none; width: 58mm; padding: 1mm 2mm; }
            @page { size: 58mm auto; margin: 0mm; }
        }
    </style>
    <!-- Pure JS QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <button class="btn-print no-print" onclick="window.print()">🖨️ প্রিন্ট করুন (Print Sticker)</button>

    <div class="sticker-card">
        <div class="header">
            <h3>উপজেলা ভূমি অফিস</h3>
            <p>ভৈরব, কিশোরগঞ্জ</p>
        </div>

        <div class="details">
            <div><strong>ধরন:</strong> {{ $file->type }}</div>
            <div><strong>মামলা নং:</strong> {{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}</div>
            @if($file->badi_name)
            <div><strong>বাদি:</strong> {{ $file->badi_name }}</div>
            @endif
            @if($file->bibadi_name)
            <div><strong>বিবাদি:</strong> {{ $file->bibadi_name }}</div>
            @endif
            <div><strong>তারিখ:</strong> {{ bangla(date('d/m/Y', strtotime($file->created_at))) }}</div>
        </div>

        <!-- Dynamic QR Code Container -->
        <div class="qr-container">
            <div id="qrcode"></div>
        </div>

        <div style="font-size: 8px; font-family: monospace; word-break: break-all;">
            {{ $file->uuid }}
        </div>
    </div>

    <script>
        // Generate QR code with UUID
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $file->uuid }}",
            width: 85,
            height: 85,
            correctLevel : QRCode.CorrectLevel.M
        });
    </script>
</body>
</html>