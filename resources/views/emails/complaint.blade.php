<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Feedback - Consumer Affairs</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f2f3f8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            padding: 40px;
        }
         .header {
            background-color: #4CAF50;
            color: #ffffff;
            text-align: center;
            padding: 20px 0;
        }
        .header img {
            width: 200px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #4CAF50;
        }
        .content p {
            color: #455056;
            font-size: 16px;
            line-height: 24px;
        }
        .content strong {
            color: #333;
        }
        .footer {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://consumeraffairs.digitalnoticeboard.biz/frontend/img/consumer-affairs-logo.png" alt="Consumer Affairs">
             <h1>Consumer Affairs</h1>
        </div>
        <div class="content">
            <p>Dear {{ ucfirst($complains->first_name) . ' ' . ucfirst($complains->last_name) }},</p>
            <p>We are writing to inform you about the feedback on your complaint. Below are the details:</p>
            <p><strong>Complaint ID:</strong> CID{{ $complains->complaint_id }}</p>
            <p><strong>Supervisor Name:</strong> {{ ucfirst($complains->official_use_supervisior) }}</p>
            <p><strong>Investigating Officer(s):</strong> {{ ucfirst($complains->investing_officer) }}</p>
            <p><strong>Exhibits:</strong> {{ ucfirst($complains->official_use_exhibits) }}</p>
            <p><strong>Result:</strong> {{ ucfirst($complains->official_use_result) }}</p>
            <p><strong>Complaint Date:</strong> {{ date('d M Y', strtotime($complains->created_at)) }}</p>
            <p><strong>Feedback Date:</strong> {{ date('d M Y', strtotime($complains->official_use_end_date)) }}</p>
            <p>If you have any questions or need assistance, please don't hesitate to reach out. Our support team is here to help ensure a smooth experience.</p>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2023 - {{ date('Y') }} Consumer Affairs - All Rights Reserved.</p>
    </div>
</body>
</html>
