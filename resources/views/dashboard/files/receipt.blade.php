<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>QR Sticker - {{ $file->case_number ?? 'File' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Kalpurush', 'SolaimanLipi', sans-serif; }
        body { background: #f1f5f9; display: flex; flex-direction: column; align-items: center; padding: 20px; }
        
        /* Rongta 58mm প্রিন্টারের প্রিন্টযোগ্য এলাকা (Printable Area = 48mm) */
        .sticker-card {
            background: #fff;
            width: 48mm;
            padding: 1mm;
            border: 1px solid #ccc;
            color: #000;
            overflow: hidden;
        }

        /* ২-কলামের ফ্লুইড লেআউট */
        .sticker-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2px;
            width: 100%;
        }

        /* বাম কলাম (হেডার ও বিস্তারিত তথ্য) */
        .left-col {
            flex: 1;
            min-width: 0;
            text-align: left;
            padding-right: 2px;
        }

        .header h3 { font-size: 9px; font-weight: bold; line-height: 1.1; margin-bottom: 1px; white-space: nowrap; }
        .header p { font-size: 7px; border-bottom: 1px dashed #000; padding-bottom: 1px; margin-bottom: 2px; line-height: 1; }

        .details { font-size: 7.5px; line-height: 1.2; color: #000; }
        .details div { 
            margin-bottom: 1px; 
            white-space: normal; 
            word-wrap: break-word;
            overflow: hidden; 
        }

        .uuid-text { 
            font-size: 6px; 
            font-family: monospace; 
            word-break: break-all; 
            margin-top: 2px; 
            line-height: 1; 
            letter-spacing: -0.2px;
        }

        /* ডান কলাম (Rongta-তে কাটা না পড়ার মতো কম্প্যাক্ট QR কোড) */
        .right-col {
            width: 65px;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #qrcode canvas, #qrcode img {
            display: block;
            width: 65px !important;
            height: 65px !important;
        }

        .btn-print {
            background: #059669; color: white; border: none; padding: 10px 20px;
            font-size: 14px; font-weight: bold; border-radius: 6px; cursor: pointer; margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* নেভিগেশন বাটন স্টাইল */
        .bottom-buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-nav {
            text-decoration: none;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 6px;
            color: white;
            transition: background 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-blue { background: #2563eb; }
        .btn-blue:hover { background: #1d4ed8; }

        .btn-dark { background: #475569; }
        .btn-dark:hover { background: #334155; }

        @media print {
            body { background: white; padding: 0; margin: 0; }
            .no-print { display: none !important; }
            .sticker-card { 
                border: none; 
                width: 48mm; 
                padding: 0mm; 
                margin: 0 auto;
            }
            @page { 
                size: 58mm auto; 
                margin: 0mm; 
            }
        }
    </style>
    <!-- Pure JS QR Code Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <!-- প্রিন্ট বাটন (প্রিন্টে আসবে না) -->
    <button class="btn-print no-print" onclick="window.print()">🖨️ প্রিন্ট করুন (Print Sticker)</button>

    <!-- Rongta 58mm ফ্রেন্ডলি স্টিকার ভিউ -->
    <div class="sticker-card">
        <div class="sticker-body">
            
            <!-- বাম কলাম -->
            <div class="left-col">
                <div class="header">
                    <h3>উপজেলা ভূমি অফিস</h3>
                    <p>ভৈরব, কিশোরগঞ্জ</p>
                </div>

                <div class="details">
                    <div><strong>ধরন:</strong> {{ $file->type }}</div>
                    <div><strong>মামলা:</strong> {{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}</div>
                    @if($file->badi_name)
                    <div><strong>বাদি:</strong> {{ $file->badi_name }}</div>
                    @endif
                    @if($file->bibadi_name)
                    <div><strong>বিবাদি:</strong> {{ $file->bibadi_name }}</div>
                    @endif
                    <div><strong>তারিখ:</strong> {{ bangla(date('d/m/Y', strtotime($file->created_at))) }}</div>
                </div>

                <div class="uuid-text">
                    {{ substr($file->uuid, 0, 18) }}
                </div>
            </div>

            <!-- ডান কলাম (৬৫px QR কোড) -->
            <div class="right-col">
                <div id="qrcode"></div>
            </div>

        </div>
    </div>

    <!-- নিচে ফিরে যাওয়ার বাটনসমূহ (প্রিন্টে আসবে না) -->
    <div class="bottom-buttons no-print">
        <a href="{{ route('files.index') }}" class="btn-nav btn-blue">📋 সকল নথি তালিকা</a>
        <a href="{{ route('files.my_desk') }}" class="btn-nav btn-dark">📥 আমার ডেস্ক</a>
    </div>

    <script>
        // Generate QR code (Width/Height adjusted to 65px for Rongta 58mm)
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $file->uuid }}",
            width: 65,
            height: 65,
            correctLevel : QRCode.CorrectLevel.M
        });
    </script>
</body>
</html>