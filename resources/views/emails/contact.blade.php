<!DOCTYPE html>
<html>
<head>
    <title>Contact Form Submission</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        h1 { color: #4CAF50; }
        .label { font-weight: bold; }
        .content { margin-bottom: 15px; }
        .footer { font-size: 12px; color: #666; text-align: center; }
        .btn { display: inline-block; padding: 10px 20px; background: #4CAF50; color: #fff; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Contact Form Submission</h1>
        <div class="content">
            <p><span class="label">Name:</span> {{ e($data['name']) }}</p>
            <p><span class="label">Email:</span> {{ e($data['email']) }}</p>
            <p><span class="label">Message:</span></p>
            <p>{{ e($data['message']) }}</p>
        </div>
        <p>Reply to this email to respond to the sender.</p>
        <p><a href="https://petalpawsfungi.com" class="btn">Visit Paws, Petals & Fungi</a></p>
        <div class="footer">
            <p>Paws, Petals & Fungi | <a href="https://t.me/paws_petals_fungi">Chat with us on Telegram</a></p>
            <p>info@petalpawsfungi.com</p>
        </div>
    </div>
</body>
</html>