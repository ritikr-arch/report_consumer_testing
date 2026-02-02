<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Complaint Submitted</title>
    <style>
        .container {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 20px 0;
            color: #333;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        .content p {
            margin: 8px 0;
            color: #333;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ url('public/frontend/img/consumer-affairs-logo.png') }}" alt="Consumer Affairs Logo">
            <h1>New Complaint Submitted</h1>
        </div>
        <div class="content">
               <p>Dear Admin,</p>
              <p>You have a new complaint submitted. Please review the details below:</p>
             <p><span class="label">Complaint id:</span> #CID{{ $complaint->complaint_id }}</p>
            <p><span class="label">Name:</span> {{ $complaint->first_name }} {{ $complaint->last_name }}</p>
            <p><span class="label">Email:</span> {{ $complaint->email }}</p>
            <p><span class="label">Phone:</span> {{ $complaint->country_code }} {{ $complaint->phone }}</p>
            <p><span class="label">Address:</span> {{ $complaint->address ?? 'N/A' }}</p>
            <p><span class="label">Gender:</span> {{ $complaint->gender ?? 'N/A' }}</p>
            <p><span class="label">Age Group:</span> {{ $complaint->age_group ?? 'N/A' }}</p>
        

            <p>Please log in to the admin panel to review and take action on this complaint.</p>
        </div>
        <div class="footer">
            &copy; 2025 Consumer Affairs. All rights reserved.
        </div>
    </div>
</body>
</html>
