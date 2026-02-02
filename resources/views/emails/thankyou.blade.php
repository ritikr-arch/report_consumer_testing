<!DOCTYPE html>
<html>
<head>
    <title>Thank You for Your Submission | Consumer Affairs</title>
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
        @if($formType == 'getintouch')
            <h2>Thank You for Getting in Touch!</h2>
            <p>Hi {{ $name }},</p>
            <p>Thank you for getting in touch with us! We’ve received your message and will get back to you as soon as possible.</p>
            <p>If your enquiry is urgent, feel free to contact us directly at <a href="mailto:consumeraffairs@gov.kn">consumeraffairs@gov.kn</a>.</p>
            <p>We appreciate your interest and look forward to assisting you.</p>
        @elseif($formType == 'feedback')
            <h2>Thanks for Your Feedback!</h2>
            <p>Hi {{ $name }},</p>
            <p>Thank you for sharing your feedback with us! We truly value your thoughts, suggestions, and comments.</p>
            <p>Your input helps us improve and serve you better. If you have any further suggestions, feel free to reach out anytime.</p>
            <p>Thanks again for helping us grow.</p>
        @else
            <h2>Thank You!</h2>
            <p>Hi {{ $name }},</p>
            <p>Thank you for contacting us.</p>
        @endif

        <p>Best regards,<br>Consumer Affairs Team</p>
    </div>

    <div class="footer">
        &copy; 2025 Consumer Affairs. All Rights Reserved.<br>
        <a href="https://affairs.digitalnoticeboard.biz/">Visit Our Website</a> |
        <a href="mailto:consumeraffairs@gov.kn">Contact Support</a>
    </div>
</div>

</body>
</html>
