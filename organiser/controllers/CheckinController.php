<?php
class CheckinController {

    public function index() {
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_GET['event_id'] ?? 0);

        $stmt = $db->prepare("SELECT * FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        if (!$event) redirect('index.php?page=events');
        $db->close();
        require BASE_PATH . 'views/checkin/index.php';
    }

    // AJAX endpoint — returns JSON
    public function ajaxCheckin() {
        if (!isPost()) { jsonResponse(['success'=>false,'message'=>'Invalid request']); }

        $db = getDB();
        $org_id      = $_SESSION['user_id'];
        $ticket_code = sanitize($_POST['ticket_code'] ?? '');
        $event_id    = (int)($_POST['event_id'] ?? 0);

        if (!$ticket_code) {
            jsonResponse(['success'=>false,'message'=>'Please enter a ticket code.']);
        }

        // Verify ticket belongs to organiser's event
        $stmt = $db->prepare("
            SELECT b.*, u.name as attendee_name, t.name as tier_name, e.organiser_id
            FROM bookings b
            JOIN users u ON b.attendee_id=u.id
            JOIN ticket_tiers t ON b.tier_id=t.id
            JOIN events e ON b.event_id=e.id
            WHERE b.ticket_code=? AND b.event_id=? AND e.organiser_id=?
        ");
        $stmt->bind_param("sii", $ticket_code, $event_id, $org_id);
        $stmt->execute();
        $booking = $stmt->get_result()->fetch_assoc();

        if (!$booking) {
            jsonResponse(['success'=>false,'message'=>'Invalid ticket code. No booking found.']);
        }

        if ($booking['status'] !== 'active') {
            jsonResponse(['success'=>false,'message'=>'Ticket is ' . $booking['status'] . ' and cannot be checked in.']);
        }

        if ($booking['checked_in']) {
            jsonResponse([
                'success' => false,
                'message' => 'Ticket already checked in at ' . formatDate($booking['checked_in_at']) . '.',
                'attendee' => $booking['attendee_name'],
                'tier'     => $booking['tier_name'],
            ]);
        }

        // Mark as checked in
        $now = date('Y-m-d H:i:s');
        $stmt2 = $db->prepare("UPDATE bookings SET checked_in=1, checked_in_at=? WHERE id=?");
        $stmt2->bind_param("si", $now, $booking['id']);
        $stmt2->execute();
        $db->close();

        jsonResponse([
            'success'  => true,
            'message'  => 'Check-in successful!',
            'attendee' => $booking['attendee_name'],
            'tier'     => $booking['tier_name'],
            'quantity' => $booking['quantity'],
            'checked_in_at' => formatDate($now),
        ]);
    }

    public function stats() {
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_GET['event_id'] ?? 0);

        $stmt = $db->prepare("SELECT * FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        if (!$event) redirect('index.php?page=events');

        // Overall stats
        $stmt2 = $db->prepare("SELECT COUNT(*) as total_sold, SUM(checked_in) as total_checked FROM bookings WHERE event_id=? AND status='active'");
        $stmt2->bind_param("i", $event_id);
        $stmt2->execute();
        $overall = $stmt2->get_result()->fetch_assoc();

        // By tier
        $stmt3 = $db->prepare("
            SELECT t.name, t.total_seats,
                   COUNT(b.id) as sold,
                   SUM(b.checked_in) as checked_in
            FROM ticket_tiers t
            LEFT JOIN bookings b ON b.tier_id=t.id AND b.status='active'
            WHERE t.event_id=?
            GROUP BY t.id
        ");
        $stmt3->bind_param("i", $event_id);
        $stmt3->execute();
        $tierStats = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);
        $db->close();
        require BASE_PATH . 'views/checkin/stats.php';
    }
}
