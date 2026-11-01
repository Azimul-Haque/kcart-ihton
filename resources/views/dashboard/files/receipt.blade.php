<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>QR Sticker - {{ $file->case_number ?? 'File' }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Kalpurush', 'SolaimanLipi', sans-serif; }
        body { background: #f1f5f9; display: flex; flex-direction: column; align-items: center; padding: 20px; }
        
        /* Rongta 58mm প্রিন্টারের জন্য প্যাডিং ও মার্জিন বাড়ানো হয়েছে (Safe Printable Area) */
        .sticker-card {
            background: #fff;
            width: 54mm;
            padding: 2mm 3mm; /* ডানে ও বামে প্যাডিং বাড়ানো হয়েছে */
            border: 1px solid #ccc;
            color: #000;
            overflow: hidden;
        }

        /* ২-কলামের ফ্লুইড লেআউট */
        .sticker-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 4px;
            width: 100%;
        }

        /* বাম কলাম (বড় ফন্ট ও অতিরিক্ত প্যাডিং সহ) */
        .left-col {
            flex: 1;
            min-width: 0;
            text-align: left;
            padding-right: 5px; /* ডানে পর্যাপ্ত ফাঁকা জায়গা */
        }

        .header h3 { font-size: 11px; font-weight: bold; line-height: 1.15; margin-bottom: 2px; white-space: nowrap; }
        .header p { font-size: 9px; border-bottom: 1px dashed #000; padding-bottom: 2px; margin-bottom: 4px; line-height: 1; font-weight: bold; }

        .details { font-size: 8.5px; line-height: 1.2; color: #000; font-weight: bold; }
        .details div { 
            margin-bottom: 2px; 
            white-space: normal; 
            word-wrap: break-word;
            overflow: hidden;
        }

        /* ডান কলাম (সেফটি মার্জিন সহ ৫০px QR কোড) */
        .right-col {
            width: 80px;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-left: 1px;
        }

        #qrcode canvas, #qrcode img {
            display: block;
            width: 80px !important;
            height: 80px !important;
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
                width: 54mm; 
                padding: 2mm 3mm; /* প্রিন্ট অপশনে সেফ মার্জিন */
                margin: 0 auto;
            }
            @page { 
                size: 54mm auto; 
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

    <!-- Rongta 58mm অতিরিক্ত প্যাডিং যুক্ত স্টিকার ভিউ -->
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
                    <div><strong>এন্ট্রি:</strong> {{ bangla(date('d/m/Y', strtotime($file->created_at))) }}</div>
                </div>
            </div>

            <!-- ডান কলাম (৫০px QR কোড) -->
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
        // Generate QR code (Width/Height set to 50px for extra margin safety)
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $file->uuid }}",
            width: 80,
            height: 80,
            correctLevel : QRCode.CorrectLevel.M
        });
    </script>
</body>
</html>