<!DOCTYPE html>
<html>
<head>
    <title>New Complaint Assigned | Consumer Affairs</title>
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
        .content p {
            line-height: 1.6;
            font-size: 16px;
        }
        .complaint-id {
            font-weight: bold;
            color: #e53935;
            font-size: 18px;
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
            <h2>New Complaint Assigned to You</h2>

            <p>Dear {{ $officer_name ?? 'Officer' }},</p>

            <p>We would like to inform you that a new complaint has been assigned to you for investigation.</p>

            <p>
                <strong>Complaint ID:</strong>
                <span class="complaint-id"> #CID{{ $complaint_id }}</span>
            </p>

            <p>
                Please log in to the internal portal and review the complaint details. Kindly take appropriate action as soon as possible.
            </p>

            <p>If you have any questions or require assistance, please contact the administration team.</p>

            <p>Thank you for your dedication and prompt attention to this matter.</p>

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
