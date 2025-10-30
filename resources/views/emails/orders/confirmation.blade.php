<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111; background-color: #f7f7f7; padding: 24px;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
        <tr>
            <td style="padding: 24px;">
                <h1 style="margin-top: 0; font-size: 24px;">Thank you for your order!</h1>
                <p style="line-height: 1.5; margin-bottom: 16px;">
                    We received your payment receipt and created order <strong>#{{ $order->id }}</strong>.
                    Use the button below to review your order details at any time.
                </p>
                <p style="line-height: 1.5; margin-bottom: 16px;">
                    If you did not place this order, please ignore this message.
                </p>
                <p style="margin: 24px 0;">
                    <a href="{{ $confirmationUrl }}" style="background-color: #1f2937; color: #ffffff; text-decoration: none; padding: 12px 20px; border-radius: 4px; display: inline-block;">
                        View Order Confirmation
                    </a>
                </p>
                <p style="line-height: 1.5; margin-bottom: 0;">
                    Best regards,<br>
                    Paws, Petals &amp; Fungi
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
