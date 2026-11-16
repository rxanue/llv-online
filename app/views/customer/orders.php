<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$active_page = 'orders';
$title = 'My Orders';

include __DIR__ . '/../layouts/header.php'; 
?>

<style>
    .orders-container { padding: 40px 10%; background: var(--bg-color); min-height: 70vh; }
    .orders-header { text-align: center; margin-bottom: 30px; }
    .orders-header h1 { color: var(--text-color); margin: 0 0 10px 0; font-size: 36px; }

    .receipt-box { background: var(--card-bg); border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 40px; border-top: 5px solid #ff7900; }
    .receipt-title { font-size: 24px; color: var(--text-color); margin-top: 0; border-bottom: 1px dashed var(--border-color); padding-bottom: 15px; margin-bottom: 20px;}
    
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
    th { font-weight: 600; color: var(--text-muted); text-transform: uppercase; font-size: 12px;}
    
    .qty-input { width: 60px; padding: 5px; border: 1px solid #ccc; border-radius: 5px; text-align: center; }
    .btn-remove { color: #dc3545; text-decoration: none; font-weight: bold; font-size: 14px; }
    .btn-remove:hover { text-decoration: underline; }
    
    .receipt-total { display: flex; justify-content: space-between; align-items: center; font-size: 24px; font-weight: bold; color: #07559b; margin-top: 20px; padding-top: 20px; border-top: 2px solid #333; }
    .btn-checkout { background: #28a745; color: white; border: none; padding: 12px 30px; border-radius: 5px; font-weight: bold; font-size: 16px; cursor: pointer; }
    .btn-checkout:hover { background: #218838; }
    
    .btn-add-more { display: inline-block; background: #ff7900; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 15px; }
    
    .empty { text-align: center; color: var(--text-muted); padding: 40px; }

    .status { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: capitalize; display: inline-block; }
    .status-paid { background: #d4edda; color: #155724; }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-cancelled { background: #f8d7da; color: #721c24; }

    /* QR Code Box */
    .qr-box { text-align: center; padding: 20px; background: var(--card-bg); margin-bottom: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 2px dashed #28a745; }
    .qr-box img { width: 200px; height: 200px; border-radius: 8px; margin-bottom: 15px; }
    .btn-download { display: inline-block; background: #14243d; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; }
</style>

<div class="orders-container">
    <div class="orders-header">
        <h1>My Orders</h1>
        <p>Review your current order and checkout.</p>
    </div>

    <!-- QR CODE RECEIPT DISPLAY -->
    <?php if(isset($_SESSION['receipt_qr'])): ?>
        <div class="qr-box">
            <h3 style="color: #28a745; margin-top: 0;">Payment Successful! Here is your Receipt</h3>
            <p style="color: var(--text-muted); margin-bottom: 20px;">Scan this QR code to view your digital receipt.</p>
            <img src="<?= base_url($_SESSION['receipt_qr']) ?>" alt="Receipt QR Code">
            <br>
            
            <!-- ITO YUNG BINAGO NATIN: PDF DOWNLOAD LINK -->
            <a href="<?= base_url('payment/receipt/' . ($_SESSION['receipt_txn_id'] ?? 0)) ?>" class="btn-download" style="background: #07559b; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Download PDF Receipt
            </a>
        </div>
        <?php unset($_SESSION['receipt_qr']); unset($_SESSION['receipt_txn_id']); ?>
    <?php endif; ?>
    <!-- END QR CODE RECEIPT -->

    <div class="receipt-box">
        <h3 class="receipt-title">Current Order (Receipt)</h3>
        
        <?php if (!empty($cart_items)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                            <td>₱<?= number_format($item['price'], 2) ?></td>
                            <td>
                                <form action="<?= base_url('orders/update_cart') ?>" method="POST" style="display:inline;">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="qty-input" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td><strong>₱<?= number_format($item['subtotal'], 2) ?></strong></td>
                            <td><a href="<?= base_url('orders/remove/' . $item['id']) ?>" class="btn-remove">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="receipt-total">
                <span>TOTAL:</span>
                <span>₱<?= number_format($total, 2) ?></span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                <a href="<?= base_url('restaurant') ?>" class="btn-add-more">Add More Items</a>
                <button type="button" class="btn-checkout" onclick="document.getElementById('checkoutForm').submit()">Checkout</button>
            </div>

            <form id="checkoutForm" action="<?= base_url('orders/checkout') ?>" method="POST" style="display:none;"></form>

        <?php else: ?>
            <div class="empty">
                <p>Your cart is empty.</p>
                <a href="<?= base_url('restaurant') ?>" class="btn-add-more">Go to Restaurant</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="receipt-box">
        <h3 class="receipt-title" style="border-bottom-color: #ff7900;">Past Orders</h3>
        
        <?php if (!empty($order_history)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_history as $order): ?>
                        <?php 
                            $stat = strtolower($order['status'] ?? 'pending');
                            $statusClass = 'status-pending';
                            if ($stat === 'paid') $statusClass = 'status-paid';
                            if ($stat === 'cancelled') $statusClass = 'status-cancelled';
                        ?>
                        <tr>
                            <td>#<?= htmlspecialchars($order['id']) ?></td>
                            <td><?= htmlspecialchars($order['notes']) ?></td>
                            <td>₱<?= number_format((float)($order['total_amount'] ?? 0), 2) ?></td>
                            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                            <td><span class="status <?= $statusClass ?>"><?= htmlspecialchars($order['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty">
                <p>You haven't placed any orders yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
include __DIR__ . '/../layouts/footer.php'; 
?>