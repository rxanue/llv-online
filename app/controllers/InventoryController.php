
<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class InventoryController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (empty($_SESSION['user_id'])) {
            redirect('login');
            exit;
        }

        // Allow admin and customer access check
        if (($_SESSION['role'] ?? '') !== 'admin') {
            redirect('dashboard');
            exit;
        }

        // Load Inventory page
        $this->call->view('inventory');
    }
}
