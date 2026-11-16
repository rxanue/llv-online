<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class TransactionsController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            redirect('login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $transactions = [];

        try {
            // Gagamitin natin ang TransactionModel dito
            $this->call->model('TransactionModel');
            $transactions = $this->TransactionModel->getUserTransactions($userId);
        } catch (\Exception $e) {
            $transactions = [];
        }

        $data = [
            'transactions' => $transactions
        ];

        $this->call->view('customer/transactions', $data);
    }
}