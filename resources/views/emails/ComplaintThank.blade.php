<!DOCTYPE html>
<html>
<head>
    <title>Thank You for Submitting Your Complaint | Consumer Affairs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4CAF50;
            text-align: center;
            padding: 20px;
        }
        .header img {
            max-width: 120px;
            margin-bottom: 10px;
        }
        .header-title {
            font-size: 24px;
            color: #fff;
            margin: 0;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
        }
        .content h2 {
            color:#4CAF50;
            margin-top: 0;
        }
        .complaint-id {
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 15px;
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
            <img src="{{ url('public/frontend/img/consumer-affairs-logo.png') }}" alt="Consumer Affair Logo" style="width: 165px!important;">
            <h1 class="header-title">Consumer Affairs</h1>
        </div>

        <div class="content">
            <h2>Thank You for Submitting Your Complaint</h2>
            <p>Dear {{ $name }},</p>
            <p>We have successfully received your complaint. Your complaint ID is: 
                <span class="complaint-id">{{ 'CID'.$complaint_id }}</span>
            </p>
            <p>Our team will review your submission and get back to you soon.</p>
            <p>If you have any further questions, please feel free to 
                <a href="mailto:consumeraffairs@gov.kn">contact us</a>.
            </p>
            <p>Best Regards,<br>Consumer Affairs Team</p>
        </div>
        <div class="footer">
            &copy; 2025 Consumer Affairs. All Rights Reserved.<br>
            <a href="https://affairs.digitalnoticeboard.biz/">Visit Our Website</a> | 
            <a href="mailto:consumeraffairs@gov.kn">Contact Support</a>
        </div>
    </div>
</body>
</html>
