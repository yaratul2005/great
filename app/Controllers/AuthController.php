<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller {
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $token = bin2hex(random_bytes(32));
            $userModel = new User();
            
            if ($userModel->register($name, $email, $password, $token)) {
                // Send Email
                $mailer = new \App\Helpers\SMTPMailer();
                $activateUrl = \App\Config\App::getBaseUrl() . "/activate?token=$token";
                $subject = "Activate your Account";
                $body = "Hi $name,<br><br>Please click the link below to activate your account:<br><a href='$activateUrl'>$activateUrl</a><br><br>Thanks!";
                
                if ($mailer->send($email, $subject, $body)) {
                    $this->view('auth/login', ['success' => 'Registration successful! Please check your email to activate your account.']);
                } else {
                     // Fallback if email fails (for testing/localhost without STMP)
                     // In strict prod, we might show error. For now, warn user.
                     $this->view('auth/login', ['error' => 'Account created but email failed to send. Contact admin.']);
                }
            } else {
                $this->view('auth/register', ['error' => 'Registration failed. Email may already exist.']);
            }
        } else {
            $this->view('auth/register');
        }
    }

    public function activate() {
        $token = $_GET['token'] ?? '';
        if ($token) {
            $userModel = new User();
            if ($userModel->activate($token)) {
                $this->view('auth/login', ['success' => 'Account activated! You can now login.']);
            } else {
                $this->view('auth/login', ['error' => 'Invalid or expired activation token.']);
            }
        } else {
            $this->redirect('/login');
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $userModel = new User();
            $user = $userModel->login($email, $password);
            
            if ($user === 'not_active') {
                $this->view('auth/login', ['error' => 'Please activate your account via email before logging in.']);
                return;
            }

            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['name'];
                
                if ($user['role'] === 'admin') {
                    $this->redirect('/admin/dashboard');
                } else {
                    $this->redirect('/dashboard');
                }
            } else {
                $this->view('auth/login', ['error' => 'Invalid credentials.']);
            }
        } else {
            $this->view('auth/login');
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        $this->redirect('/');
    }
}
