<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class FoodCategoryModel extends Model {
    protected $table = 'food_categories';

    public function getAllCategories() {
        // Kunin lang yung mga active categories
        return $this->db->table($this->table)->where('status', 'Active')->get_all();
    }
}