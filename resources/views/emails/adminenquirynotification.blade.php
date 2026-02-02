<!DOCTYPE html>
<html>
<head>
    <title>New Enquiry Submission | Consumer Affairs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0; padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4CAF50;
            text-align: center;
            padding: 20px;
        }
        .header img {
            max-width: 165px !important;
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
            color: #4CAF50;
            margin-top: 0;
        }
        .detail-label {
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
            <img src="{{ url('public/frontend/img/consumer-affairs-logo.png') }}" alt="Consumer Affairs Logo">
            <h1 class="header-title">Consumer Affairs</h1>
        </div>

        <div class="content">
            <h2>New Enquiry Received</h2>

            <p><span class="detail-label">Name:</span> {{ $data['name'] ?? 'N/A' }}</p>
            <p><span class="detail-label">Email:</span> {{ $data['email'] ?? 'N/A' }}</p>
            @if(!empty($data['phone']))
                <p><span class="detail-label">Phone:</span> {{ $data['country_code'] }} {{ $data['phone'] }}</p>
            @endif
            @if(!empty($data['message']))
                <p><span class="detail-label">Message:</span><br>{{ $data['message'] }}</p>
            @endif

            <p>Please check the admin panel or database for more details.</p>
        </div>

        <div class="footer">
            &copy; 2025 Consumer Affairs. All Rights Reserved.<br>
            <a href="https://affairs.digitalnoticeboard.biz/">Visit Our Website</a> | 
            <a href="mailto:consumeraffairs@gov.kn">Contact Support</a>
        </div>
    </div>
</body>
</html>
