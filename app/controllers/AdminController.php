<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AdminController extends Controller
{
    private function checkAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
            redirect('login');
            exit;
        }
    }

    // ==============================
    // 1. DASHBOARD
    // ==============================
    public function index()
    {
        $this->checkAdmin();
        $db = load_class('database');

        $totalUsers = 0; $totalReservations = 0; $totalOrders = 0; $totalRevenue = 0;
        $resCount = 0; $foodCount = 0; $diveCount = 0;

        try {
            $totalUsers = count($db->table('users')->get_all());
            $totalReservations = count($db->table('reservations')->get_all());
            $totalOrders = count($db->table('food_orders')->get_all());

            $paidTxns = $db->table('transactions')->where('status', 'paid')->get_all();
            foreach($paidTxns as $t) { $totalRevenue += (float)((array)$t)['amount']; }

            $resCount = count($db->table('reservations')->get_all());
            $foodCount = count($db->table('food_orders')->get_all());
            $diveCount = count($db->table('dive_bookings')->get_all());
        } catch (\Exception $e) {}

        $data = [
            'totalUsers' => $totalUsers, 'totalReservations' => $totalReservations,
            'totalOrders' => $totalOrders, 'totalRevenue' => $totalRevenue,
            'chartData' => ['reservations' => $resCount, 'food_orders' => $foodCount, 'dive_bookings' => $diveCount]
        ];

        $this->call->view('admin/dashboard', $data);
    }

    // ==============================
    // 2. MANAGE USERS
    // ==============================
    public function manage_users()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $users = $db->table('users')->get_all();
        $this->call->view('admin/users', ['users' => $users]);
    }

    public function store_user()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $db->table('users')->insert([
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'role' => $_POST['role'],
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $_SESSION['success'] = 'User added successfully!';
        redirect('admin/users');
    }

    public function update_user()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $updateData = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'role' => $_POST['role']
        ];
        
        if (!empty($_POST['password'])) {
            $updateData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $db->table('users')->where('id', $_POST['id'])->update($updateData);
        $_SESSION['success'] = 'User updated successfully!';
        redirect('admin/users');
    }

    public function delete_user($id)
    {
        $this->checkAdmin();
        $db = load_class('database');
        $db->table('users')->where('id', $id)->delete();
        $_SESSION['success'] = 'User deleted!';
        redirect('admin/users');
    }

    // ==============================
    // 3. MANAGE ROOMS
    // ==============================
    public function manage_rooms()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $rooms = $db->table('rooms')->get_all();
        $this->call->view('admin/rooms', ['rooms' => $rooms]);
    }

    public function store_room()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $db->table('rooms')->insert([
            'room_number' => $_POST['room_number'],
            'room_name' => $_POST['room_name'],
            'room_type' => $_POST['room_type'],
            'description' => $_POST['description'],
            'capacity' => $_POST['capacity'],
            'price_per_night' => $_POST['price_per_night'],
            'status' => 'Available',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $_SESSION['success'] = 'Room added!';
        redirect('admin/rooms');
    }

    public function edit_room($id)
    {
        $this->checkAdmin();
        $db = load_class('database');
        $room = $db->table('rooms')->where('id', $id)->get();
        $this->call->view('admin/edit_room', ['room' => (array)$room]);
    }

    public function update_room()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $db->table('rooms')->where('id', $_POST['id'])->update([
            'room_number' => $_POST['room_number'],
            'room_name' => $_POST['room_name'],
            'room_type' => $_POST['room_type'],
            'description' => $_POST['description'],
            'capacity' => $_POST['capacity'],
            'price_per_night' => $_POST['price_per_night']
        ]);
        $_SESSION['success'] = 'Room updated!';
        redirect('admin/rooms');
    }

    public function delete_room($id)
    {
        $this->checkAdmin();
        $db = load_class('database');
        $db->table('rooms')->where('id', $id)->delete();
        $_SESSION['success'] = 'Room deleted!';
        redirect('admin/rooms');
    }

    // ==============================
    // 4. MANAGE DIVE SERVICES
    // ==============================
    public function manage_diveservices()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $services = $db->table('dive_services')->get_all();
        $this->call->view('admin/diveservices', ['services' => $services]);
    }

    public function store_diveservice()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $db->table('dive_services')->insert([
            'service_name' => $_POST['service_name'],
            'description' => $_POST['description'],
            'duration_minutes' => $_POST['duration_minutes'],
            'price' => $_POST['price'],
            'status' => 'available',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $_SESSION['success'] = 'Dive service added!';
        redirect('admin/diveservices');
    }

    public function edit_diveservice($id)
    {
        $this->checkAdmin();
        $db = load_class('database');
        $service = $db->table('dive_services')->where('id', $id)->get();
        $this->call->view('admin/edit_diveservice', ['service' => (array)$service]);
    }

    public function update_diveservice()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $db->table('dive_services')->where('id', $_POST['id'])->update([
            'service_name' => $_POST['service_name'],
            'description' => $_POST['description'],
            'duration_minutes' => $_POST['duration_minutes'],
            'price' => $_POST['price']
        ]);
        $_SESSION['success'] = 'Dive service updated!';
        redirect('admin/diveservices');
    }

    public function delete_diveservice($id)
    {
        $this->checkAdmin();
        $db = load_class('database');
        $db->table('dive_services')->where('id', $id)->delete();
        $_SESSION['success'] = 'Dive service deleted!';
        redirect('admin/diveservices');
    }

    // ==============================
    // 5. MANAGE RESTAURANT
    // ==============================
    public function manage_restaurant()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $items = $db->table('menu_items')->get_all();
        $this->call->view('admin/restaurant', ['items' => $items]);
    }

    public function store_menuitem()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $db->table('menu_items')->insert([
            'category_id' => $_POST['category_id'],
            'item_name' => $_POST['item_name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'availability' => 'Available',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $_SESSION['success'] = 'Menu item added!';
        redirect('admin/restaurant');
    }

    public function edit_menuitem($id)
    {
        $this->checkAdmin();
        $db = load_class('database');
        $item = $db->table('menu_items')->where('id', $id)->get();
        $this->call->view('admin/edit_menuitem', ['item' => (array)$item]);
    }

    public function update_menuitem()
    {
        $this->checkAdmin();
        $db = load_class('database');
        $db->table('menu_items')->where('id', $_POST['id'])->update([
            'item_name' => $_POST['item_name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'category_id' => $_POST['category_id']
        ]);
        $_SESSION['success'] = 'Menu item updated!';
        redirect('admin/restaurant');
    }

    public function delete_menuitem($id)
    {
        $this->checkAdmin();
        $db = load_class('database');
        $db->table('menu_items')->where('id', $id)->delete();
        $_SESSION['success'] = 'Menu item deleted!';
        redirect('admin/restaurant');
    }
}