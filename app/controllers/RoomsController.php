<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class RoomsController extends Controller {
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            redirect('login');
            exit;
        }

        $rooms = [];
        
        try {
            $this->call->model('RoomModel');
            $rooms = $this->RoomModel->getAllRooms(); 
        } catch (\Exception $e) {
            $rooms = [];
        }

        $data = [
            'rooms' => $rooms
        ];

        $this->call->view('customer/rooms', $data); 
    }

    public function store() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            redirect('login');
            exit;
        }

        $customerId = $_SESSION['user_id']; 
        $roomId = $_POST['room_id'] ?? 0;
        $checkIn = $_POST['check_in'] ?? '';
        $checkOut = $_POST['check_out'] ?? '';

        if (empty($roomId) || empty($checkIn) || empty($checkOut)) {
            $_SESSION['error'] = 'Please fill in all booking fields.';
            redirect('rooms');
            exit;
        }

        try {
            $db = load_class('database');
            
            $room = $db->table('rooms')->where('id', $roomId)->get();
            $roomArray = (array) $room;
            $pricePerNight = $roomArray['price_per_night'] ?? 0;
            $roomName = $roomArray['room_name'] ?? 'Room';

            $date1 = new DateTime($checkIn);
            $date2 = new DateTime($checkOut);
            $days = $date1->diff($date2)->days;
            if ($days <= 0) $days = 1;

            $totalAmount = $days * $pricePerNight;
            $bookingRef = 'LLV-' . strtoupper(substr(uniqid(), -5));

            $db->table('reservations')->insert([
                'customer_id' => $customerId,
                'room_id' => $roomId,
                'booking_reference' => $bookingRef,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests' => 1,
                'total_amount' => $totalAmount,
                'booking_status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $db->table('transactions')->insert([
                'customer_id' => $customerId,
                'reference_no' => $bookingRef,
                'description' => 'Room Reservation: ' . $roomName,
                'amount' => $totalAmount,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $_SESSION['success'] = 'Your reservation has been submitted successfully! Reference: ' . $bookingRef;
            redirect('reservations'); // Pinalitan natin ito para sa Reservations page pumunta

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to save reservation. Error: ' . $e->getMessage();
            redirect('rooms');
        }
    }
}