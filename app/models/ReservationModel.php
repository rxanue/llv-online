<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ReservationModel extends Model {
    
    protected $table = 'reservations';

    public function getUserReservations($userId) {
        return $this->db->table('reservations')
            ->join('rooms', 'reservations.room_id = rooms.id')
            // Ginamit natin ang booking_status at tinanggal ang status
            ->select('reservations.id, reservations.booking_reference, reservations.check_in, reservations.check_out, reservations.booking_status, reservations.total_amount, rooms.room_name')
            // customer_id ang gamit sa table mo, hindi user_id
            ->where('reservations.customer_id', $userId)
            ->order_by('reservations.created_at', 'DESC')
            ->get_all();
    }
}