<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StaffController extends Controller
{
    private function checkStaff() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $role = strtolower($_SESSION['role'] ?? '');
        if (empty($_SESSION['user_id']) || ($role !== 'staff' && $role !== 'admin')) {
            redirect('login');
            exit;
        }
    }

    // 1. STAFF DASHBOARD
    public function index()
    {
        $this->checkStaff();
        $db = load_class('database');

        $pendingOrders = [];
        $pendingBookings = [];

        try {
            $orders = $db->table('food_orders')->where('status', 'pending')->get_all();
            if (is_array($orders) || is_object($orders)) {
                foreach($orders as $o) { $pendingOrders[] = (array)$o; }
            }

            $bookings = $db->table('reservations')->where('booking_status', 'pending')->get_all();
            if (is_array($bookings) || is_object($bookings)) {
                foreach($bookings as $b) { $pendingBookings[] = (array)$b; }
            }
        } catch (\Exception $e) {}

        $data = [
            'pendingOrders' => $pendingOrders,
            'pendingBookings' => $pendingBookings
        ];

        $this->call->view('staff/dashboard', $data);
    }

    // 2. MANAGE ALL ORDERS
    public function manage_orders()
    {
        $this->checkStaff();
        $db = load_class('database');
        $orders = [];
        
        try {
            $results = $db->table('food_orders')->order_by('created_at', 'DESC')->get_all();
            if (is_array($results) || is_object($results)) {
                foreach($results as $r) { $orders[] = (array)$r; }
            }
        } catch (\Exception $e) {}

        $this->call->view('staff/orders', ['orders' => $orders]);
    }

    public function update_order_status()
    {
        $this->checkStaff();
        $db = load_class('database');
        
        $orderId = $_POST['id'] ?? 0;
        $newStatus = $_POST['status'] ?? 'pending';

        if ($orderId > 0) {
            $db->table('food_orders')->where('id', $orderId)->update([
                'status' => $newStatus
            ]);
            $_SESSION['success'] = 'Order status updated to ' . $newStatus;
        }

        redirect('staff/orders');
    }

    // 3. MANAGE ALL RESERVATIONS
    public function manage_reservations()
    {
        $this->checkStaff();
        $db = load_class('database');
        $reservations = [];

        try {
            $results = $db->table('reservations')
                ->join('rooms', 'reservations.room_id = rooms.id')
                ->select('reservations.id, reservations.booking_reference, reservations.check_in, reservations.check_out, reservations.booking_status, reservations.total_amount, rooms.room_name')
                ->order_by('reservations.created_at', 'DESC')
                ->get_all();
                
            if (is_array($results) || is_object($results)) {
                foreach($results as $r) { $reservations[] = (array)$r; }
            }
        } catch (\Exception $e) {}

        $this->call->view('staff/reservations', ['reservations' => $reservations]);
    }

    public function update_reservation_status()
    {
        $this->checkStaff();
        $db = load_class('database');
        
        $resId = $_POST['id'] ?? 0;
        $newStatus = $_POST['status'] ?? 'pending';

        if ($resId > 0) {
            $db->table('reservations')->where('id', $resId)->update([
                'booking_status' => $newStatus
            ]);
            $_SESSION['success'] = 'Reservation status updated to ' . $newStatus;
        }

        redirect('staff/reservations');
    }

    // 4. MANAGE DIVE BOOKINGS (BAGONG DAGDAG)
    public function manage_divebookings()
    {
        $this->checkStaff();
        $db = load_class('database');
        $diveBookings = [];

        try {
            $results = $db->table('dive_bookings')
                ->join('dive_services', 'dive_bookings.service_id = dive_services.id')
                ->select('dive_bookings.id, dive_bookings.booking_reference, dive_bookings.scheduled_at, dive_bookings.participants, dive_bookings.total_amount, dive_bookings.booking_status, dive_services.service_name')
                ->order_by('dive_bookings.created_at', 'DESC')
                ->get_all();
                
            if (is_array($results) || is_object($results)) {
                foreach($results as $r) { $diveBookings[] = (array)$r; }
            }
        } catch (\Exception $e) {}

        $this->call->view('staff/divebookings', ['divebookings' => $diveBookings]);
    }

    public function update_divebooking_status()
    {
        $this->checkStaff();
        $db = load_class('database');
        
        $bookingId = $_POST['id'] ?? 0;
        $newStatus = $_POST['status'] ?? 'pending';

        if ($bookingId > 0) {
            $db->table('dive_bookings')->where('id', $bookingId)->update([
                'booking_status' => $newStatus
            ]);
            $_SESSION['success'] = 'Dive Booking status updated to ' . $newStatus;
        }

        redirect('staff/divebookings');
    }
}