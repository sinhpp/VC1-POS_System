<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12pt;
        line-height: 1.4;
        color: #333;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }
    .store-info {
        text-align: center;
        margin-bottom: 20px;
        font-size: 10pt;
    }
    .receipt-details {
        margin-bottom: 20px;
        width: 100%;
    }
    .receipt-details table {
        width: 100%;
    }
    .receipt-details td {
        padding: 5px;
    }
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .items-table th, .items-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .items-table th {
        background-color: #f2f2f2;
    }
    .totals {
        width: 100%;
        margin-bottom: 20px;
    }
    .totals td {
        padding: 5px;
    }
    .totals .label {
        text-align: right;
        font-weight: bold;
    }
    .totals .value {
        text-align: right;
        width: 100px;
    }
    .footer {
        text-align: center;
        margin-top: 20px;
        font-size: 10pt;
        color: #777;
        border-top: 1px solid #eee;
        padding-top: 10px;
    }
    .thank-you {
        text-align: center;
        font-size: 14pt;
        margin: 20px 0;
        color: #4CAF50;
    }
</style>

<div class="header">
    <h1><?php echo $receiptData['store']['name']; ?></h1>
</div>

<div class="store-info">
    <p><?php echo $receiptData['store']['address']; ?></p>
    <p>Phone: <?php echo $receiptData['store']['phone']; ?> | Email: <?php echo $receiptData['store']['email']; ?></p>
    <p>Website: <?php echo $receiptData['store']['website']; ?></p>
</div>

<div class="receipt-details">
    <table>
        <tr>
            <td><strong>Receipt #:</strong></td>
            <td><?php echo $receiptData['receipt_id']; ?></td>
            <td><strong>Date:</strong></td>
            <td><?php echo date('M d, Y h:i A', strtotime($receiptData['date'])); ?></td>
        </tr>
        <tr>
            <td><strong>Transaction ID:</strong></td>
            <td><?php echo $receiptData['transaction_id']; ?></td>
            <td><strong>Payment Method:</strong></td>
            <td><?php echo ucfirst($receiptData['payment_method']); ?></td>
        </tr>
        <?php if (!empty($receiptData['customer'])): ?>
        <tr>
            <td><strong>Customer:</strong></td>
            <td colspan="3"><?php echo $receiptData['customer']['name']; ?> (<?php echo $receiptData['customer']['email']; ?>)</td>
        </tr>
        <?php endif; ?>
    </table>
</div>

<h3>Purchase Details</h3>
<table class="items-table">
    <thead>
        <tr>
            <th>Item</th>
            <th>SKU</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($receiptData['items'] as $item): ?>
        <tr>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo $item['sku']; ?></td>
            <td>$<?php echo number_format($item['price'], 2); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<table class="totals">
    <tr>
        <td class="label">Subtotal:</td>
        <td class="value">$<?php echo number_format($receiptData['subtotal'], 2); ?></td>
    </tr>
    <?php if ($receiptData['discount'] > 0): ?>
    <tr>
        <td class="label">Discount:</td>
        <td class="value">$<?php echo number_format($receiptData['discount'], 2); ?></td>
    </tr>
    <?php endif; ?>
    <tr>
        <td class="label">Tax:</td>
        <td class="value">$<?php echo number_format($receiptData['tax'], 2); ?></td>
    </tr>
    <tr>
        <td class="label"><strong>Total:</strong></td>
        <td class="value"><strong>$<?php echo number_format($receiptData['total'], 2); ?></strong></td>
    </tr>
</table>

<div class="thank-you">
    <p>Thank you for your purchase!</p>
</div>

<div class="footer">
    <p>For any questions or concerns, please contact us at <?php echo $receiptData['store']['email']; ?></p>
</div>

