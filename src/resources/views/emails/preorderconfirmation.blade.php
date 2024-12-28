<!DOCTYPE html>
<html>
<head>
    <title>Pre-Order Confirmation</title>
</head>
<body>
    <h1>Pre-Order Confirmation</h1>
    <p>Dear {{ $recipientType === 'user' ? $preOrder->name : 'Admin' }},</p>
    <p>Your pre-order has been successfully placed.</p>
    <p>Order Details:</p>
    <ul>
        <li>Name: {{ $preOrder->name }}</li>
        <li>Email: {{ $preOrder->email }}</li>
        <li>Product: {{ $preOrder->product?->name }}</li>
        <li>Phone: {{ $preOrder->phone }}</li>
    </ul>
    <p>Thank you for your order!</p>
</body>
</html>
