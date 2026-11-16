<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class MenuItemModel extends Model {
    protected $table = 'menu_items';

    public function getAllItems() {
        // I-specify natin nang isa-isa ang columns imbes na menu_items.*
        return $this->db->table('menu_items')
            ->join('food_categories', 'menu_items.category_id = food_categories.id')
            ->select('menu_items.id, menu_items.item_name, menu_items.description, menu_items.price, menu_items.availability, food_categories.category_name')
            ->get_all();
    }
}