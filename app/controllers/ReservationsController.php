<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ReservationsController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $userId = $_SESSION['user_id'];
        $reservations = [];

        try {
            $db = load_class('database');
            
            // Kunin ang reservations kasama ang kaukulang transaction (para sa payment ID)
            $results = $db->table('reservations')
                ->join('rooms', 'reservations.room_id = rooms.id')
                ->select('reservations.id, reservations.booking_reference, reservations.check_in, reservations.check_out, reservations.booking_status, reservations.total_amount, rooms.room_name')
                ->where('reservations.customer_id', $userId)
                ->order_by('reservations.created_at', 'DESC')
                ->get_all();

            if (is_array($results) || is_object($results)) {
                foreach ($results as $res) {
                    $r = (array) $res;
                    
                    // Hanapin ang transaction ID base sa booking_reference
                    $trans = $db->table('transactions')->where('reference_no', $r['booking_reference'])->get();
                    $t = (array) $trans;
                    
                    $r['transaction_id'] = $t['id'] ?? 0;
                    $r['payment_status'] = $t['status'] ?? 'pending';

                    $reservations[] = $r;
                }
            }

        } catch (\Exception $e) {
            $reservations = [];
        }

        $data = [
            'reservations' => $reservations
        ];

        $this->call->view('customer/reservations', $data);
    }
}