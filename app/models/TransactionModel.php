<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class TransactionModel extends Model {
    
    protected $table = 'transactions';

    public function getUserTransactions($userId) {
        // Simpleng query lang muna, walang NOT LIKE para hindi mag-error
        return $this->db->table('transactions')
            ->where('customer_id', $userId)
            ->order_by('created_at', 'DESC')
            ->get_all();
    }
}