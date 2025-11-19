<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            margin: 0;
        }
        .info {
            margin-bottom: 20px;
        }
        .info p {
            margin: 0 0 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #777;
            margin-top: 20px;
        }
        .amount {
            text-align: right;
        }
        .status-completed {
            color: green;
        }
        .status-pending {
            color: orange;
        }
        .status-failed {
            color: red;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Transaction Report</h1>
        <p>Generated on: {{ date('F d, Y H:i:s') }}</p>
    </div>

    <div class="info">
        <p><strong>Member:</strong> {{ $user->name }}</p>
        <p><strong>Member Number:</strong> {{ $user->member_number }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        @if(!empty($filters['start_date']) || !empty($filters['end_date']))
            <p>
                <strong>Period:</strong> {{ !empty($filters['start_date']) ? date('M d, Y', strtotime($filters['start_date'])) : 'All time' }} to {{ !empty($filters['end_date']) ? date('M d, Y', strtotime($filters['end_date'])) : 'Present' }}
            </p>
        @endif
        @if(!empty($filters['type']))
            <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $filters['type'])) }}</p>
        @endif
        @if(!empty($filters['status']))
            <p><strong>Status:</strong> {{ ucfirst($filters['status']) }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th>Reference</th>
                <th>Status</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            @if(count($transactions) > 0)
                @php $totalAmount = 0; @endphp
                @foreach($transactions as $transaction)
                    @php $totalAmount += $transaction->amount; @endphp
                    <tr>
                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->reference }}</td>
                        <td class="status-{{ $transaction->status }}">{{ ucfirst($transaction->status) }}</td>
                        <td class="amount">₦{{ number_format($transaction->amount, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" style="text-align: right;"><strong>Total:</strong></td>
                    <td class="amount"><strong>₦{{ number_format($totalAmount, 2) }}</strong></td>
                </tr>
            @else
                <tr>
                    <td colspan="6" style="text-align: center;">No transactions found for the selected criteria.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>This is an official document of ACICS Cooperative Society. For any inquiries, please contact support.</p>
        <p>© {{ date('Y') }} ACICS Cooperative Society. All rights reserved.</p>
    </div>
</body>
</html>