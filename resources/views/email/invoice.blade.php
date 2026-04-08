<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $invoice->invoice_id }}</title>
</head>
<body>
    <h2>Invoice #{{ $invoice->invoice_id }}</h2>
    <p>Status: {{ $invoice->status == 2 ? 'Unpaid' : 'Paid' }}</p>
    <p>Due Date: {{ \Carbon\Carbon::parse($dueDate)->format('d-m-Y') }}</p>
    <p>Total Amount: ${{ number_format($totalAmount, 2) }}</p>

    <h4>Student Details:</h4>
    <p>Name: {{ $student->name ?? 'N/A' }}</p>
    <p>Roll Number: {{ $student->roll_number ?? 'N/A' }}</p>
@if(!empty($items))
    <h4>Selected Services:</h4>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Service</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item['service'] }}</td>
                    <td>{{ $item['description'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>${{ number_format($item['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No services selected.</p>
@endif

</body>
</html>
