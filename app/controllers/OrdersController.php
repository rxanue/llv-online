<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class OrdersController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $cartItems = [];
        $total = 0;
        $db = load_class('database');

        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $itemId => $qty) {
                $item = $db->table('menu_items')->where('id', $itemId)->get();
                $i = (array) $item;
                if (!empty($i)) {
                    $subtotal = $i['price'] * $qty;
                    $total += $subtotal;
                    $cartItems[] = [
                        'id' => $itemId,
                        'name' => $i['item_name'],
                        'price' => $i['price'],
                        'quantity' => $qty,
                        'subtotal' => $subtotal
                    ];
                }
            }
        }

        $userId = $_SESSION['user_id'];
        $user = $db->table('users')->where('id', $userId)->get();
        $u = (array) $user;
        $customerName = $u['username'] ?? '';

        $orderHistory = [];
        try {
            $results = $db->table('food_orders')
                ->where('customer_name', $customerName)
                ->order_by('created_at', 'DESC')
                ->get_all();
            if (is_array($results) || is_object($results)) {
                foreach ($results as $res) {
                    $orderHistory[] = (array) $res;
                }
            }
        } catch (\Exception $e) {
            $orderHistory = [];
        }

        $data = [
            'cart_items' => $cartItems,
            'total' => $total,
            'order_history' => $orderHistory
        ];

        $this->call->view('customer/orders', $data);
    }

    public function update_cart()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $itemId = $_POST['item_id'] ?? 0;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($itemId > 0) {
            if ($quantity > 0) {
                $_SESSION['cart'][$itemId] = $quantity;
            } else {
                unset($_SESSION['cart'][$itemId]);
            }
        }

        redirect('orders');
    }

    public function remove_item($itemId)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        if (isset($_SESSION['cart'][$itemId])) {
            unset($_SESSION['cart'][$itemId]);
        }

        redirect('orders');
    }

    public function checkout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }
        if (empty($_SESSION['cart'])) { redirect('orders'); exit; }

        $userId = $_SESSION['user_id'];
        $db = load_class('database');
        
        $user = $db->table('users')->where('id', $userId)->get();
        $u = (array) $user;
        $customerName = $u['username'] ?? 'Customer';
        $customerEmail = $u['email'] ?? '';

        $orderRef = 'ORD-' . strtoupper(substr(uniqid(), -5));
        $totalAmount = 0;
        $notes = "";

        // Kalkulahin ang lahat para sa receipt
        foreach ($_SESSION['cart'] as $itemId => $qty) {
            $item = $db->table('menu_items')->where('id', $itemId)->get();
            $i = (array) $item;
            if (!empty($i)) {
                $totalAmount += ($i['price'] * $qty);
                $notes .= $qty . 'x ' . $i['item_name'] . ', ';
            }
        }
        $notes = rtrim($notes, ', ');

        // I-save sa food_orders
        $db->table('food_orders')->insert([
            'customer_name' => $customerName,
            'customer_contact' => $customerEmail,
            'order_type' => 'dine_in',
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'notes' => $notes,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $newOrder = $db->table('food_orders')->where('customer_name', $customerName)->order_by('id', 'DESC')->limit(1)->get();
        $o = (array) $newOrder;
        $orderId = $o['id'] ?? 0;

        // I-save ang mga items sa order_items
        foreach ($_SESSION['cart'] as $itemId => $qty) {
            $item = $db->table('menu_items')->where('id', $itemId)->get();
            $i = (array) $item;
            if (!empty($i)) {
                $db->table('order_items')->insert([
                    'order_id' => $orderId,
                    'menu_item_id' => $itemId,
                    'quantity' => $qty,
                    'unit_price' => $i['price'],
                    'subtotal' => ($i['price'] * $qty),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        // I-save sa transactions (para mabayaran sa Stripe)
        $db->table('transactions')->insert([
            'customer_id' => $userId,
            'reference_no' => $orderRef,
            'description' => 'Food Order: ' . $notes,
            'amount' => $totalAmount,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Kunin ang bagong transaction ID para sa Stripe Payment
        $newTransaction = $db->table('transactions')->where('reference_no', $orderRef)->get();
        $nt = (array) $newTransaction;
        $transactionId = $nt['id'] ?? 0;

        // I-clear ang cart
        unset($_SESSION['cart']);

        // I-redirect sa Stripe Checkout imbes na sa Orders page
        header("Location: " . base_url('payment/checkout/' . $transactionId));
        exit;
    }
}