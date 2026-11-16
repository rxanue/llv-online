<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class RoomModel extends Model {
    protected $table = 'rooms';
    
    public function getAllRooms() {
        // Ito ang tamang paraan ng pagkuha ng lahat ng data sa LavaLust
        return $this->db->table($this->table)->get_all();
    }
}