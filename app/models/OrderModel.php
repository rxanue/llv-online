<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class OrderModel extends Model {
    
    protected $table = 'food_orders';

    public function getUserOrders($userName) {
        return $this->db->table('food_orders')
            ->where('customer_name', $userName)
            ->order_by('created_at', 'DESC')
            ->get_all();
    }

    public function getCartItems() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $items = [];
        if (!empty($_SESSION['cart'])) {
            $db = load_class('database');
            foreach ($_SESSION['cart'] as $itemId => $qty) {
                $item = $db->table('menu_items')->where('id', $itemId)->get();
                $i = (array) $item;
                if (!empty($i)) {
                    $items[] = [
                        'id' => $itemId,
                        'name' => $i['item_name'],
                        'price' => $i['price'],
                        'quantity' => $qty,
                        'subtotal' => ($i['price'] * $qty)
                    ];
                }
            }
        }
        return $items;
    }
}