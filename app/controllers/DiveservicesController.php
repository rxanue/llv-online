<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class DiveservicesController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $this->call->model('DiveServiceModel');
        $services = $this->DiveServiceModel->getAllServices();

        $data = ['dive_services' => $services];
        $this->call->view('customer/diveservices', $data);
    }

    public function store()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $userId = $_SESSION['user_id'];
        $serviceId = $_POST['service_id'] ?? 0;
        $bookingDate = $_POST['booking_date'] ?? date('Y-m-d'); 
        $scheduledAt = $bookingDate . ' 08:00:00';

        if (empty($serviceId)) {
            $_SESSION['error'] = 'Please select a valid service.';
            redirect('diveservices');
            exit;
        }

        try {
            $db = load_class('database');
            
            $service = $db->table('dive_services')->where('id', $serviceId)->get();
            $s = (array) $service;
            $price = $s['price'] ?? 0;
            $serviceName = $s['service_name'] ?? 'Dive Service';
            $bookingRef = 'DIVE-' . strtoupper(substr(uniqid(), -5));

            $db->table('dive_bookings')->insert([
                'customer_id' => $userId,
                'service_id' => $serviceId,
                'booking_reference' => $bookingRef,
                'scheduled_at' => $scheduledAt,
                'participants' => 1, 
                'total_amount' => $price,
                'booking_status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $db->table('transactions')->insert([
                'customer_id' => $userId,
                'reference_no' => $bookingRef,
                'description' => 'Dive Service: ' . $serviceName,
                'amount' => $price,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $_SESSION['success'] = 'Dive service booked successfully! Ref: ' . $bookingRef;
            redirect('transactions');

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to book dive service. Error: ' . $e->getMessage();
            redirect('diveservices');
        }
    }

    // ITO YUNG BAGONG FUNCTION PARA SA MAPA
    public function map()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $this->call->view('customer/map', []);
    }
}