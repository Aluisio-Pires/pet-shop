<!DOCTYPE html>
<html>
<head>
    <title>{{ $pdfName }}</title>
</head>
<body>
<h1>Store name: {{ $store }}</h1>
<p>Date of order creation: {{ $creation }}</p>
<p>Invoice number: {{ $invoice }}</p>
<p>Customer details: {{ $customer }}</p>
<p>Payment Details: {{ $payment }}</p>
<p>Address Details: {{ $address }}</p>
<p>Items Details: {{ $item }}</p>
<p>Fee: {{ $fee }}</p>
<p>Total: {{ $total }}</p>

</body>
</html>
