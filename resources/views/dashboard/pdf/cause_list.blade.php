<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>শুনানির কার্যতালিকা - {{ bangla(date('d/m/Y', strtotime($selectedDate))) }}</title>
    <style>
        body {
            font-family: 'Kalpurush', 'Nikosh', sans-serif;
            font-size: 11pt;
            color: #222;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .office-title {
            text-align: center;
        }

        .office-title h2 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
        }

        .office-title h4 {
            margin: 2px 0 0 0;
            font-size: 12pt;
            font-weight: normal;
        }

        .office-title p {
            margin: 4px 0 0 0;
            font-size: 11pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .meta-info {
            width: 100%;
            margin-bottom: 10px;
            font-size: 11px;
        }

        .cause-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .cause-table th, .cause-table td {
            border: 1px solid #444;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        .cause-table th {
            background-color: #f0f2f5;
            font-weight: bold;
            font-size: 12px;
            text-align: center;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 9pt;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <!-- হেডার সেকশন -->
    <table class="header-table">
        <tr>
            <td class="office-title">
                <h2>উপজেলা ভূমি অফিস</h2>
                <h4>ভৈরব, কিশোরগঞ্জ</h4>
                <p>শুনানির কার্যতালিকা (Cause List)</p>
            </td>
        </tr>
    </table>

    <!-- মেটা ইনফরমেশন -->
    <table class="meta-info">
        <tr>
            <td><strong>ধার্য তারিখ:</strong> {{ bangla(date('d F, Y', strtotime($selectedDate))) }} ({{ getBanglaDay($selectedDate) }})</td>
            <td class="text-right"><strong>মোট নথি/মামলা:</strong> {{ bangla(count($files)) }} টি</td>
        </tr>
    </table>

    @php
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

    <!-- মূল ডাটা টেবিল -->
    <table class="cause-table">
        <thead>
            <tr>
                <th width="5%">ক্র.নং</th>
                <th width="20%">মামলা / নথি নম্বর</th>
                <th width="12%">ধরন</th>
                <th width="33%">বাদি ও বিবাদির নাম</th>
                <th width="18%">বর্তমান অবস্থান</th>
                <th width="12%">অবস্থা</th>
            </tr>
        </thead>
        <tbody>
            @forelse($files as $index => $file)
                <tr>
                    <td class="text-center">{{ bangla($index + 1) }}</td>
                    <td>
                        <span class="font-bold">{{ $file->case_number ? bangla($file->case_number) : 'রুজু হয়নি' }}</span>
                        <br><small style="color: #666;">আইডি: {{ substr($file->uuid, 0, 8) }}</small>
                    </td>
                    <td class="text-center">{{ $file->type }}</td>
                    <td>
                        <strong>বাদি:</strong> {{ $file->badi_name ?? '-' }}
                        @if($file->badi_mobile) ({{ bangla($file->badi_mobile) }}) @endif
                        <br>
                        <strong>বিবাদি:</strong> {{ $file->bibadi_name ?? '-' }}
                        @if($file->bibadi_mobile) ({{ bangla($file->bibadi_mobile) }}) @endif
                    </td>
                    <td>{{ $file->currentDesk->designation ?? ($file->currentDesk->name ?? '-') }}</td>
                    <td class="text-center">{{ $file->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">
                        এই তারিখে কোনো শুনানির বাধ্যবাধকতা নেই।
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ফুটার সেকশন (প্রিন্ট ডেট ও পেজিং) -->
    <htmlpagefooter name="page-footer">
        <table class="footer">
            <tr>
                <td width="50%">প্রিন্টের তারিখ ও সময়: {{ bangla(date('d/m/Y h:i A')) }}</td>
                <td width="50%" class="text-right">পৃষ্ঠা {PAGENO} / {nbpg}</td>
            </tr>
        </table>
    </htmlpagefooter>
    <sethtmlpagefooter name="page-footer" value="on" />

</body>
</html>