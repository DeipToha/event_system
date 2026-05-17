<?php

// ========== CSRF Protection ==========
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

function verifyCsrfToken() {
    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        http_response_code(403);
        die('Invalid CSRF token. Please go back and try again.');
    }
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}

// ========== Validation Helpers ==========
function isValidLength($value, $min = 1, $max = 255) {
    $len = mb_strlen(trim($value));
    return $len >= $min && $len <= $max;
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isPositiveInt($value) {
    return filter_var($value, FILTER_VALIDATE_INT) !== false && (int)$value > 0;
}
function isEndAfterStart($start, $end) {
    return strtotime($end) > strtotime($start);
}

function loginUser($user) {
     $_SESSION['user_id'] = $user['id'];
    $_SESSION['role']    = $user['role'];  
    $_SESSION['name']    = $user['name'];
}
function logoutUser() {
    session_unset();
     session_destroy();
}
function redirect($url) {
    header("Location: $url");
     exit;
}

function isPost() {
return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function sanitize($data) {
return htmlspecialchars(strip_tags(trim($data)));
}

function jsonResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
     exit;
}

function uploadFile($file, $dir, $allowed = ['jpg','jpeg','png','gif']) {
       $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
       if (!in_array($ext, $allowed)) return false;
        $filename = uniqid() . '.' . $ext;
       $target = $dir . $filename;
       if (move_uploaded_file($file['tmp_name'], $target)) return $filename;
      return false;
}

function setFlash($type, $msg) {
      $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function getFlash() { if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function formatDate($dt) {
    return date('d M Y, h:i A', strtotime($dt));
}

function timeAgo($dt) {
    $diff = time() - strtotime($dt);
    if ($diff < 60) return $diff . 's ago';
    if ($diff < 3600) return round($diff/60) . 'm ago';
    if ($diff < 86400) return round($diff/3600) . 'h ago';
    return round($diff/86400) . 'd ago';
}
