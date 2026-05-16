<?php
class DiscountController {

    public function index() {
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_GET['event_id'] ?? 0);

        $stmt = $db->prepare("SELECT * FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        if (!$event) redirect('index.php?page=events');

        $stmt2 = $db->prepare("SELECT * FROM discount_codes WHERE event_id=? AND organiser_id=? ORDER BY id DESC");
        $stmt2->bind_param("ii", $event_id, $org_id);
        $stmt2->execute();
        $codes = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        $flash = getFlash();
        $db->close();
        require 'views/organiser/discount/index.php';
    }

    public function create() {
        if (!isPost()) redirect('index.php?page=events');
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_POST['event_id'] ?? 0);

        $stmt = $db->prepare("SELECT id FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        if (!$stmt->get_result()->fetch_assoc()) redirect('index.php?page=events');

        $code         = strtoupper(sanitize($_POST['code'] ?? ''));
        $discount_pct = (float)($_POST['discount_pct'] ?? 0);
        $max_uses     = (int)($_POST['max_uses'] ?? 1);
        $valid_until  = $_POST['valid_until'] ?? '';

        if (!$code || $discount_pct <= 0 || $discount_pct > 100) {
            setFlash('error', 'Invalid discount data.');
        } else {
            $stmt2 = $db->prepare("INSERT INTO discount_codes (event_id, organiser_id, code, discount_pct, max_uses, valid_until) VALUES (?,?,?,?,?,?)");
            $stmt2->bind_param("iisdis", $event_id, $org_id, $code, $discount_pct, $max_uses, $valid_until);
            $stmt2->execute();
            setFlash('success', 'Discount code created!');
        }
        $db->close();
        redirect('index.php?page=discounts&action=index&event_id=' . $event_id);
    }

    public function toggle() {
        if (!isPost()) redirect('index.php?page=events');
        $db = getDB();
        $org_id  = $_SESSION['user_id'];
        $code_id = (int)($_POST['code_id'] ?? 0);
        $event_id= (int)($_POST['event_id'] ?? 0);

        $stmt = $db->prepare("UPDATE discount_codes SET is_active = NOT is_active WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $code_id, $org_id);
        $stmt->execute();
        $db->close();
        redirect('index.php?page=discounts&action=index&event_id=' . $event_id);
    }
}
