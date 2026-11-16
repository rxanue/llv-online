<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class DashboardController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $db = load_class('database');
        $userId = $_SESSION['user_id'];
        $userName = $_SESSION['user_name'] ?? 'Guest';

        // 1. Latest Room Reservation
        $latestReservation = null;
        try {
            $res = $db->table('reservations')
                ->where('customer_id', $userId)
                ->order_by('created_at', 'DESC')
                ->limit(1)
                ->get();
            if (!empty($res)) $latestReservation = (array) $res;
        } catch (\Exception $e) {}

        // 2. Latest Food Order
        $latestOrder = null;
        try {
            $user = $db->table('users')->where('id', $userId)->get();
            $u = (array) $user;
            $customerName = $u['username'] ?? '';

            if (!empty($customerName)) {
                $food = $db->table('food_orders')
                    ->where('customer_name', $customerName)
                    ->order_by('created_at', 'DESC')
                    ->limit(1)
                    ->get();
                if (!empty($food)) $latestOrder = (array) $food;
            }
        } catch (\Exception $e) {}

        // 3. Latest Dive Booking
        $latestDive = null;
        try {
            $dive = $db->table('dive_bookings')
                ->join('dive_services', 'dive_bookings.service_id = dive_services.id')
                ->select('dive_bookings.booking_reference, dive_bookings.scheduled_at, dive_bookings.total_amount, dive_bookings.booking_status, dive_services.service_name')
                ->where('dive_bookings.customer_id', $userId)
                ->order_by('dive_bookings.created_at', 'DESC')
                ->limit(1)
                ->get();
            if (!empty($dive)) $latestDive = (array) $dive;
        } catch (\Exception $e) {}

        // 4. Total Spent
        $totalSpent = 0;
        try {
            $txns = $db->table('transactions')
                ->where('customer_id', $userId)
                ->where('status', 'paid')
                ->get_all();
            if (is_array($txns) || is_object($txns)) {
                foreach ($txns as $t) {
                    $row = (array)$t;
                    $totalSpent += (float)($row['amount'] ?? 0);
                }
            }
        } catch (\Exception $e) {}

        // 5. Active Bookings
        $activeBookings = 0;
        try {
            $pending = $db->table('reservations')
                ->where('customer_id', $userId)
                ->where('booking_status', 'pending')
                ->get_all();
            if (is_array($pending) || is_object($pending)) $activeBookings += count($pending);
        } catch (\Exception $e) {}

        // 6. Chart Data
        $chartData = [
            'reservations' => 0,
            'food_orders' => 0,
            'dive_bookings' => 0
        ];

        try {
            $chartData['reservations'] = count($db->table('reservations')->where('customer_id', $userId)->get_all());
            $chartData['dive_bookings'] = count($db->table('dive_bookings')->where('customer_id', $userId)->get_all());
            
            $user = $db->table('users')->where('id', $userId)->get();
            $u = (array) $user;
            $cName = $u['username'] ?? '';
            if (!empty($cName)) {
                $chartData['food_orders'] = count($db->table('food_orders')->where('customer_name', $cName)->get_all());
            }
        } catch (\Exception $e) {}

        $data = [
            'userName' => $userName,
            'latestReservation' => $latestReservation,
            'latestOrder' => $latestOrder,
            'latestDive' => $latestDive,
            'totalSpent' => $totalSpent,
            'activeBookings' => $activeBookings,
            'chartData' => $chartData
        ];

        $this->call->view('user_dashboard', $data);
    }
}