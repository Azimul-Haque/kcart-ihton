<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>শুনানি স্লিপ - {{ $file->case_number }}</title>
    <style>
        body { font-family: 'SolaimanLipi', 'Nikosh', sans-serif; padding: 20px; background: #fff; color: #000; }
        .slip-box { border: 2px dashed #000; padding: 20px; max-width: 600px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 1px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .header h3 { margin: 0; font-size: 20px; }
        .header p { margin: 2px 0; font-size: 14px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 5px 0; vertical-align: top; font-size: 15px; }
        .qr-area { text-align: center; margin-top: 15px; border-top: 1px solid #ddd; padding-top: 10px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .slip-box { border: 1px solid #000; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: center; margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 8px 20px; background: #007bff; color: #fff; border: none; cursor: pointer; font-size: 14px; font-weight: bold;">
            প্রিন্ট করুন
        </button>
    </div>

    <div class="slip-box">
        <div class="header">
            <h3>উপজেলা ভূমি অফিস, ভৈরব, কিশোরগঞ্জ</h3>
            <p><strong>শুনানি গ্রহণের প্রাপ্তিস্বীকার ও নোটিশ স্লিপ</strong></p>
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
                    <strong style="font-size: 18px; color: #d9534f;">
                        {{ $file->next_hearing_date ? bangla(date('d/m/Y', strtotime($file->next_hearing_date))) : 'ধার্য হয়নি' }}
                    </strong>
                </td>
            </tr>
            <tr>
                <td><strong>নথির বর্তমান অবস্থা:</strong></td>
                <td>{{ $file->status }}</td>
            </tr>
        </table>

        <div class="qr-area">
            {!! QrCode::size(90)->generate($file->uuid) !!}
            <div style="font-size: 11px; margin-top: 5px;"><code>UUID: {{ $file->uuid }}</code></div>
        </div>
    </div>

</body>
</html>