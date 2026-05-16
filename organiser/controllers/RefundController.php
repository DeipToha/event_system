<?php
class RefundController {
    public function index() {
        $db = getDB();
        $org_id = $_SESSION['user_id'];
        $filter = $_GET['status'] ?? '';

        $sql = "SELECT rr.*, b.ticket_code, b.total_price, e.title as event_title, u.name as attendee_name, u.email as attendee_email
                FROM refund_requests rr
                JOIN bookings b ON rr.booking_id=b.id
                JOIN events e ON b.event_id=e.id
                JOIN users u ON rr.attendee_id=u.id
                WHERE e.organiser_id=?";
        $params = [$org_id]; $types = 'i';
        if ($filter) { $sql .= " AND rr.status=?"; $params[] = $filter; $types .= 's'; }
        $sql .= " ORDER BY rr.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $refunds = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $flash = getFlash();
        $db->close();
        require BASE_PATH . 'views/refund/index.php';
    }

    public function process() {
        if (!isPost()) redirect('index.php?page=refunds');
        $db = getDB();
        $org_id    = $_SESSION['user_id'];
        $refund_id = (int)($_POST['refund_id'] ?? 0);
        $action    = $_POST['action'] ?? '';
        $note      = sanitize($_POST['organiser_note'] ?? '');

        // Verify ownership
        $stmt = $db->prepare("SELECT rr.* FROM refund_requests rr JOIN bookings b ON rr.booking_id=b.id JOIN events e ON b.event_id=e.id WHERE rr.id=? AND e.organiser_id=?");
        $stmt->bind_param("ii", $refund_id, $org_id);
        $stmt->execute();
        $refund = $stmt->get_result()->fetch_assoc();
        if (!$refund) redirect('index.php?page=refunds');

        if ($action === 'approve') {
            $status = 'approved';
            // Mark booking as refunded
            $stmt2 = $db->prepare("UPDATE bookings SET status='refunded' WHERE id=?");
            $stmt2->bind_param("i", $refund['booking_id']);
            $stmt2->execute();
        } else {
            $status = 'rejected';
        }

        $stmt3 = $db->prepare("UPDATE refund_requests SET status=?, organiser_note=? WHERE id=?");
        $stmt3->bind_param("ssi", $status, $note, $refund_id);
        $stmt3->execute();
        $db->close();
        setFlash('success', 'Refund request ' . $status . '.');
        redirect('index.php?page=refunds');
    }
}
