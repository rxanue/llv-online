<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class RestaurantController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $this->call->model('MenuItemModel');
        $this->call->model('FoodCategoryModel'); 
        $this->call->model('RecommendationModel'); // <--- IDINAGDAG
        
        $items = $this->MenuItemModel->getAllItems();
        $categories = $this->FoodCategoryModel->getAllCategories();
        
        // Kunin ang rekomendasyon base sa unang item sa cart kung meron man
        $recommendations = [];
        if (!empty($_SESSION['cart'])) {
            $firstItemId = array_key_first($_SESSION['cart']);
            $recommendations = $this->RecommendationModel->getRecommendations($firstItemId);
        } else {
            $recommendations = $this->RecommendationModel->getRecommendations(0);
        }

        $cartCount = 0;
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $qty) {
                $cartCount += $qty;
            }
        }

        $data = [
            'menu_items' => $items,
            'categories' => $categories,
            'cart_count' => $cartCount,
            'recommendations' => $recommendations // <--- IPINASA SA VIEW
        ];

        $this->call->view('customer/restaurant', $data);
    }

    public function add_to_cart()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $itemId = $_POST['item_id'] ?? 0;
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($itemId > 0 && $quantity > 0) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$itemId])) {
                $_SESSION['cart'][$itemId] += $quantity;
            } else {
                $_SESSION['cart'][$itemId] = $quantity;
            }
            
            $_SESSION['success'] = 'Item added to your order!';
        }

        header("Location: " . base_url('restaurant'));
        exit;
    }
}