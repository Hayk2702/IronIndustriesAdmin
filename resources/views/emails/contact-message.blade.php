<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contact Message</title>
</head>
<body>
<h2>New Contact Message</h2>

<p><strong>Full name:</strong> {{ $fullname }}</p>
<p><strong>Email:</strong> {{ $email }}</p>

@if(!empty($phone))
    <p><strong>Phone:</strong> {{ $phone }}</p>
@endif

<hr>

<p><strong>Message:</strong></p>
<p>{!! nl2br(e($messageText)) !!}</p>
</body>
</html>
