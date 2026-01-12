<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $order['id'] ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; padding: 40px; color: #333; max-width: 800px; mx-auto; }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .company-name { font-size: 24px; font-weight: bold; }
        .invoice-title { font-size: 32px; font-weight: bold; color: #555; text-align: right;}
        .details { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .meta { text-align: right; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .table th { text-align: left; padding: 10px; border-bottom: 2px solid #333; }
        .table td { padding: 10px; border-bottom: 1px solid #eee; }
        .total { text-align: right; font-size: 20px; font-weight: bold; }
        .footer { margin-top: 60px; text-align: center; color: #999; font-size: 12px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">GreatCompany Inc.</div>
        <div>
            <div class="invoice-title">INVOICE</div>
        </div>
    </div>

    <div class="details">
        <div>
            <strong>Bill To:</strong><br>
            <?= htmlspecialchars($order['user_name']) ?><br>
            <?= htmlspecialchars($order['user_email']) ?>
        </div>
        <div class="meta">
            <p><strong>Invoice No:</strong> #<?= $order['id'] ?></p>
            <p><strong>Date:</strong> <?= date('F d, Y', strtotime($order['created_at'])) ?></p>
            <p><strong>Status:</strong> <span style="color: green; font-weight: bold;">PAID</span></p>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= htmlspecialchars($order['product_title']) ?></td>
                <td style="text-align: right;">$<?= number_format($order['amount'], 2) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Total: $<?= number_format($order['amount'], 2) ?>
    </div>
    
    <div class="footer">
        <p>Thank you for your business.</p>
        <p>Transaction ID: <?= htmlspecialchars($order['transaction_id'] ?? 'N/A') ?></p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
