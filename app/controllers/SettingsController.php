<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class SettingsController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $db = load_class('database');
        $userId = $_SESSION['user_id'];
        
        $user = $db->table('users')->where('id', $userId)->get();
        $u = (array) $user;

        $data = [
            'user' => $u
        ];

        $this->call->view('customer/settings', $data);
    }

    public function update_profile()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $userId = $_SESSION['user_id'];
        $name = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($name) || empty($email)) {
            $_SESSION['error'] = 'Name and email are required.';
            redirect('settings');
            exit;
        }

        try {
            $db = load_class('database');
            
            $db->table('users')->where('id', $userId)->update([
                'username' => $name,
                'email' => $email
            ]);

            // I-update ang session para agad mag-reflect sa navbar
            $_SESSION['user_name'] = $name;
            $_SESSION['email'] = $email;

            $_SESSION['success'] = 'Profile updated successfully!';
            redirect('settings');

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to update profile: ' . $e->getMessage();
            redirect('settings');
        }
    }

    public function update_password()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user_id'])) { redirect('login'); exit; }

        $userId = $_SESSION['user_id'];
        $currentPass = $_POST['current_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        $confirmPass = $_POST['confirm_password'] ?? '';

        if (empty($currentPass) || empty($newPass) || empty($confirmPass)) {
            $_SESSION['error'] = 'All password fields are required.';
            redirect('settings');
            exit;
        }

        if ($newPass !== $confirmPass) {
            $_SESSION['error'] = 'New passwords do not match.';
            redirect('settings');
            exit;
        }

        try {
            $db = load_class('database');
            $user = $db->table('users')->where('id', $userId)->get();
            $u = (array) $user;

            if (!password_verify($currentPass, $u['password'])) {
                $_SESSION['error'] = 'Current password is incorrect.';
                redirect('settings');
                exit;
            }

            $db->table('users')->where('id', $userId)->update([
                'password' => password_hash($newPass, PASSWORD_DEFAULT)
            ]);

            $_SESSION['success'] = 'Password changed successfully!';
            redirect('settings');

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to update password: ' . $e->getMessage();
            redirect('settings');
        }
    }
}