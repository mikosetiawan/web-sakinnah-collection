<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Print Transaction Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        .status-completed {
            color: green;
        }

        .status-pending {
            color: orange;
        }

        .status-rejected {
            color: red;
        }

        .status-cancelled {
            color: gray;
        }

        @media print {
            body {
                margin: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Print Report</button>
        <a href="{{ route('admin.reports.index') }}">Back to Reports</a>
    </div>

    <h1>Report Transaksi</h1>
    <p><b>Sakinnah Collections</b></p>
    <table>
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>User</th>
                <th>Items</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->user ? $transaction->user->name : 'N/A' }}</td>
                    <td>
                        @foreach ($transaction->items as $item)
                            {{ $item->barang ? $item->barang->name : ($item->jasa ? $item->jasa->name : 'N/A') }}
                            (Qty: {{ $item->quantity }})
                            <br>
                        @endforeach
                    </td>
                    <td>Rp {{ number_format($transaction->total_price, 2) }}</td>
                    <td class="status-{{ strtolower($transaction->status) }}">{{ $transaction->status }}</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
