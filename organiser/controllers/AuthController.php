<?php
require_once BASE_PATH . 'models/UserModel.php';
require_once BASE_PATH . 'models/OrganiserModel.php';

class AuthController {

    public function login() {
        if (isset($_SESSION['user_id'])) redirect('index.php?page=dashboard');

        $error = null;
        if (isPost()) {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = UserModel::findByEmail($email);
            if ($user && $user['role'] === 'organiser' && password_verify($password, $user['password_hash'])) {
                $profile = OrganiserModel::getProfileByUserId($user['id']);
                if (!$profile || $profile['status'] !== 'approved') {
                    $error = 'Your organiser account is pending admin approval.';
                } elseif (!$user['is_active']) {
                    $error = 'Your account has been suspended.';
                } else {
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['role']      = $user['role'];
                    $_SESSION['name']      = $user['name'];
                    $_SESSION['org_name']  = $profile['org_name'];
                    $_SESSION['profile_id']= $profile['id'];
                    redirect('index.php?page=dashboard');
                }
            } else {
                $error = 'Invalid email or password.';
            }
        }
        require BASE_PATH . 'views/organiser/auth/login.php';
    }

    public function register() {
        if (isset($_SESSION['user_id'])) redirect('index.php?page=dashboard');

        $error = null;
        $success = null;

        if (isPost()) {
            $name     = sanitize($_POST['name'] ?? '');
            $email    = sanitize($_POST['email'] ?? '');
            $phone    = sanitize($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $org_name = sanitize($_POST['org_name'] ?? '');
            $org_desc = sanitize($_POST['org_description'] ?? '');
            $website  = sanitize($_POST['website'] ?? '');

            if (!$name || !$email || !$password || !$org_name) {
                $error = 'Please fill all required fields.';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } elseif (UserModel::findByEmail($email)) {
                $error = 'Email already registered.';
            } else {
                $logo_path = null;
                if (!empty($_FILES['org_logo']['name'])) {
                    $logo_path = uploadFile($_FILES['org_logo'], BASE_PATH . 'public/uploads/logos/');
                }

                $hash = password_hash($password, PASSWORD_BCRYPT);
                $userId = UserModel::create($name, $email, $hash, $phone, 'organiser');
                OrganiserModel::create($userId, $org_name, $org_desc, $logo_path, $website);
                $success = 'Registration successful! Please wait for admin approval before logging in.';
            }
        }
        require BASE_PATH . 'views/organiser/auth/register.php';
    }

    public function logout() {
        session_destroy();
        redirect('index.php?page=login');
    }
}