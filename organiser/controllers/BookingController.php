<?php
class BookingController {
    public function index() {
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_GET['event_id'] ?? 0);
        $filter_tier   = (int)($_GET['tier_id'] ?? 0);
        $filter_checkin= $_GET['checked_in'] ?? '';

        $stmt = $db->prepare("SELECT * FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        if (!$event) redirect('index.php?page=events');

        $sql = "SELECT b.*, u.name as attendee_name, u.email as attendee_email, t.name as tier_name
                FROM bookings b
                JOIN users u ON b.attendee_id=u.id
                JOIN ticket_tiers t ON b.tier_id=t.id
                WHERE b.event_id=?";
        $params = [$event_id]; $types = 'i';

        if ($filter_tier) { $sql .= " AND b.tier_id=?"; $params[] = $filter_tier; $types .= 'i'; }
        if ($filter_checkin !== '') { $sql .= " AND b.checked_in=?"; $params[] = (int)$filter_checkin; $types .= 'i'; }
        $sql .= " ORDER BY b.created_at DESC";

        $stmt2 = $db->prepare($sql);
        $stmt2->bind_param($types, ...$params);
        $stmt2->execute();
        $bookings = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

        $tiers = $db->prepare("SELECT * FROM ticket_tiers WHERE event_id=?");
        $tiers->bind_param("i", $event_id);
        $tiers->execute();
        $tierList = $tiers->get_result()->fetch_all(MYSQLI_ASSOC);
        $db->close();
        require BASE_PATH . 'views/booking/index.php';
    }
}
