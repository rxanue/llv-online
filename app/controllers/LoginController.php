<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class LoginController extends Controller
{
    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // LOGIN PAGE
    public function index()
    {
        $this->startSession();

        if (!empty($_SESSION['is_logged_in']) && !empty($_SESSION['user_id'])) {
            // Kung naka-login na, doon siya dalhin sa tamang dashboard base sa role
            $role = strtolower($_SESSION['role'] ?? 'user');
            if ($role === 'admin') {
                redirect('admin/dashboard');
            } elseif ($role === 'staff') {
                redirect('staff/dashboard');
            } else {
                redirect('dashboard');
            }
            exit;
        }

        $this->call->view('login');
    }

    // PROCESS LOGIN
    public function authenticate()
    {
        $this->startSession();

        $login = trim($_POST['email'] ?? $_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($login === '' || $password === '') {
            $_SESSION['error'] = 'Please enter your email and password.';
            redirect('login');
            exit;
        }

        $this->call->model('UserModel');
        $user = $this->UserModel->findByLogin($login);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Invalid email/username or password.';
            redirect('login');
            exit;
        }

        if (isset($user['is_active']) && (int)$user['is_active'] !== 1) {
            $_SESSION['error'] = 'Your account is inactive.';
            redirect('login');
            exit;
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;

        unset($_SESSION['error']);

        // I-redirect base sa role
        $role = strtolower($_SESSION['role']);
        if ($role === 'admin') {
            redirect('admin/dashboard');
        } elseif ($role === 'staff') {
            redirect('staff/dashboard');
        } else {
            redirect('dashboard');
        }
        exit;
    }

    // SIGNUP PAGE
    public function showSignup()
    {
        $this->startSession();
        $this->call->view('signup');
    }

    // PROCESS SIGNUP
    public function register()
    {
        $this->startSession();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            $_SESSION['error'] = 'Please fill in all fields.';
            redirect('signup');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Please enter a valid email address.';
            redirect('signup');
            exit;
        }

        $this->call->model('UserModel');

        if ($this->UserModel->findByLogin($email)) {
            $_SESSION['error'] = 'Email already exists.';
            redirect('signup');
            exit;
        }

        $data = [
            'username' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user', // Default role
            'is_active' => 1
        ];

        $result = $this->UserModel->createUser($data);

        if ($result) {
            $_SESSION['success'] = 'Account created successfully. Please login.';
            redirect('login');
            exit;
        }

        $_SESSION['error'] = 'Unable to create your account.';
        redirect('signup');
        exit;
    }

    // LOGOUT
    public function logout()
    {
        $this->startSession();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        redirect('login');
        exit;
    }
}