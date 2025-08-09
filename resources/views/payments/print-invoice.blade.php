<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>فاتورة دفع</title>
    <style>
        body { font-family: 'Cairo', Tahoma, Arial, sans-serif; direction: rtl; background: #f7f7f7; }
        .invoice-box {
            max-width: 750px;
            margin: 40px auto;
            padding: 30px 40px;
            border: 1px solid #eee;
            background: #fff;
            box-shadow: 0 0 10px #ddd;
            border-radius: 10px;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .company-details {
            text-align: right;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #2a3f54;
        }
        .company-info {
            font-size: 15px;
            color: #555;
        }
        .invoice-title {
            font-size: 22px;
            color: #2196f3;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }
        .student-info, .payment-info {
            margin-bottom: 20px;
        }
        .info-label {
            color: #888;
            font-weight: bold;
            min-width: 120px;
            display: inline-block;
        }
        .info-value {
            color: #222;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #eee;
            padding: 10px 12px;
            text-align: right;
        }
        th {
            background: #f0f4f8;
            color: #333;
        }
        .total-row td {
            font-weight: bold;
            background: #f9f9f9;
        }
        .footer {
            text-align: center;
            color: #888;
            font-size: 14px;
            margin-top: 30px;
        }
        .print-btn {
            margin-bottom: 20px;
            background: #2196f3;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .print-btn:hover {
            background: #1769aa;
        }
        @media print {
            .print-btn { display: none; }
            body { background: #fff; }
            .invoice-box { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <button class="print-btn" onclick="window.print()">طباعة</button>
        <div class="header">
            <div class="company-details">
                <div class="company-name">شركة أبجريدكس للتدريب</div>
                <div class="company-info">
                    العنوان: مصر، مدينة السادات، المنطقة الثانية<br>
                    الهاتف: 0555555555<br>
                    البريد الإلكتروني: info@upgradex.com
                </div>
            </div>
            <!-- شعار الشركة (يمكنك وضع صورة شعار هنا) -->
            <!-- <img src="{{ asset('path/to/logo.png') }}" alt="شعار الشركة" style="height:70px;"> -->
        </div>

        <div class="invoice-title">فاتورة دفع</div>

        <div class="student-info">
            <span class="info-label">اسم الطالب:</span>
            <span class="info-value">{{ $payment->coursePayment->student->full_name ?? '-' }}</span><br>
            <span class="info-label">كود الطالب:</span>
            <span class="info-value">{{ $payment->coursePayment->student->code ?? '-' }}</span>
        </div>

        <div class="payment-info">
            <span class="info-label">رقم الفاتورة:</span>
            <span class="info-value">{{ $payment->id }}</span><br>
            <span class="info-label">تاريخ الدفع:</span>
            <span class="info-value">{{ $payment->payment_date }}</span><br>
            <span class="info-label">طريقة الدفع:</span>
            <span class="info-value">
                @php
                    $methods = [1 => 'نقدي', 2 => 'تحويل بنكي', 3 => 'بطاقة', 4 => 'أخرى'];
                @endphp
                {{ $methods[$payment->payment_method_id] ?? 'غير محدد' }}
            </span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>الوصف</th>
                    <th>المبلغ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        رسوم كورس: {{ $payment->coursePayment->course->name ?? '-' }}
                    </td>
                    <td>
                        {{ number_format($payment->amount, 2) }} جنيه
                    </td>
                </tr>
                <!-- يمكنك إضافة تفاصيل أخرى هنا -->
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td>الإجمالي</td>
                    <td>{{ number_format($payment->amount, 2) }} جنيه</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            شكراً لاختياركم شركة أبجريدكس للتدريب.<br>
            هذه الفاتورة صالحة بدون توقيع أو ختم.
        </div>
    </div>
</body>
</html> 