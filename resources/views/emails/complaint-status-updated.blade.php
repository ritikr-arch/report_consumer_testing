<!DOCTYPE html>
<html>
<head>
    <title>Complaint Status Updated | Consumer Affairs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #4CAF50;
            padding: 25px 20px;
            text-align: center;
        }
        .header img {
            max-width: 165px;
            margin-bottom: 10px;
        }
        .header-title {
            font-size: 24px;
            color: #fff;
            margin: 0;
        }
        .content {
            padding: 25px;
        }
        .content h2 {
            margin-top: 0;
            color: #4CAF50;
        }
        .status {
            font-weight: bold;
            color: #007bff;
        }
        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 18px;
            font-size: 14px;
            color: #555;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ url('public/frontend/img/consumer-affairs-logo.png') }}" alt="Consumer Affairs Logo">
            <h1 class="header-title">Consumer Affairs</h1>
        </div>

        <div class="content">
    <h2>Complaint Status Updated</h2>
    <p>Hi {{ $name }},</p>
    <p>We wanted to inform you that an update has been made regarding your complaint (ID: #CID{{ $complaint_id }}) submitted on {{ $date }}</p>
   @php
    $statusLabels = [
        0 => 'New',
        1 => 'Resolved',
        2 => 'In Progress',
        3 => 'Dismissed',
        4 => 'Closed',
    ];
@endphp

<p><strong>Action Taken / Update:</strong></p>
<p>
    <strong>Status:</strong> {{ $statusLabels[$status] ?? 'Unknown' }}<br>
    {{ $result }}
    @if(!empty($remark)) - {{ $remark }} @endif
    @if(!empty($supervisior)) (By: {{ $supervisior }}) @endif
</p>

<p>If you have any further questions or concerns, feel free to reply to this email or contact our support team.</p>
    <p>Thank you for your patience.</p>

    <p>Best Regards,<br>Consumer Affairs Team</p>
</div>


        <div class="footer">
            &copy; 2025 Consumer Affairs. All rights reserved.<br>
            <a href="https://affairs.digitalnoticeboard.biz/">Visit Our Website</a> |
            <a href="mailto:consumeraffairs@gov.kn">Contact Support</a>
        </div>
    </div>
</body>
</html>
