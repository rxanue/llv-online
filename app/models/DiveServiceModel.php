<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class DiveServiceModel extends Model {
    protected $table = 'dive_services'; // Base sa Navicat mo kanina, ito ang table name

    public function getAllServices() {
        // Kunin lahat ng dive services
        return $this->db->table($this->table)->get_all();
    }
}