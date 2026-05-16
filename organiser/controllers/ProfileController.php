<?php
require_once 'models/UserModel.php';
require_once 'models/OrganiserModel.php';

class ProfileController {
    public function index() {
        $user    = UserModel::findById($_SESSION['user_id']);
        $profile = OrganiserModel::getProfileByUserId($_SESSION['user_id']);
        $flash   = getFlash();
        require 'views/profile/index.php';
    }

    public function update() {
        if (!isPost()) redirect('index.php?page=profile');
        $db = getDB();
        $uid = $_SESSION['user_id'];

        $name    = sanitize($_POST['name'] ?? '');
        $phone   = sanitize($_POST['phone'] ?? '');
        $org_name= sanitize($_POST['org_name'] ?? '');
        $org_desc= sanitize($_POST['org_description'] ?? '');
        $website = sanitize($_POST['website'] ?? '');

        // Update user
        $stmt = $db->prepare("UPDATE users SET name=?, phone=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $phone, $uid);
        $stmt->execute();

        // Handle logo upload
        $logo_sql = "";
        $logo_path = null;
        if (!empty($_FILES['org_logo']['name'])) {
            $logo_path = uploadFile($_FILES['org_logo'], 'public/uploads/');
            if ($logo_path) {
                $stmt2 = $db->prepare("UPDATE organiser_profiles SET org_name=?, org_description=?, website=?, org_logo_path=? WHERE user_id=?");
                $stmt2->bind_param("ssssi", $org_name, $org_desc, $website, $logo_path, $uid);
                $stmt2->execute();
            }
        } else {
            $stmt2 = $db->prepare("UPDATE organiser_profiles SET org_name=?, org_description=?, website=? WHERE user_id=?");
            $stmt2->bind_param("sssi", $org_name, $org_desc, $website, $uid);
            $stmt2->execute();
        }

        $_SESSION['name']     = $name;
        $_SESSION['org_name'] = $org_name;
        $db->close();
        setFlash('success', 'Profile updated successfully.');
        redirect('index.php?page=profile');
    }

    public function changePassword() {
        if (!isPost()) redirect('index.php?page=profile');
        $uid     = $_SESSION['user_id'];
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $user = UserModel::findById($uid);
        if (!password_verify($current, $user['password_hash'])) {
            setFlash('error', 'Current password is incorrect.');
        } elseif (strlen($new) < 6) {
            setFlash('error', 'New password must be at least 6 characters.');
        } elseif ($new !== $confirm) {
            setFlash('error', 'Passwords do not match.');
        } else {
            $hash = password_hash($new, PASSWORD_BCRYPT);
            $db = getDB();
            $stmt = $db->prepare("UPDATE users SET password_hash=? WHERE id=?");
            $stmt->bind_param("si", $hash, $uid);
            $stmt->execute();
            $db->close();
            setFlash('success', 'Password changed successfully.');
        }
        redirect('index.php?page=profile');
    }
}
