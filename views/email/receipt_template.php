<?php
function generateReceiptContent($customerName, $orderDetails) {
    ob_start(); // Start output buffering to capture the HTML
    ?>
    <div style="width:100%; font-family: Arial, sans-serif;">
        <h4>Hello <?php echo htmlspecialchars($customerName); ?>,</h4>
        <p>Thank you for your purchase! Here's your receipt:</p>
        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <th style="border: 1px solid #ddd; padding: 8px;">Item</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Quantity</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Price</th>
            </tr>
            <?php 
            $total = 0;
            foreach ($orderDetails['items'] as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $total += $subtotal;
            ?>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($item['item_name']); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px;">$<?php echo number_format($item['price'], 2); ?></td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="2" style="border: 1px solid #ddd; padding: 8px; text-align: right;">Total:</td>
                <td style="border: 1px solid #ddd; padding: 8px;">$<?php echo number_format($total, 2); ?></td>
            </tr>
        </table>
        <p>Best regards,<br>Cashier Service Team</p>
    </div>
    <?php
    $content = ob_get_clean(); // Get the buffered content and clean the buffer
    return $content;
}