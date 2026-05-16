<?php


class DashboardController {
    public function index() {
        $organiser_id = $_SESSION['user_id'];
        $db = getDB();

        // Stats
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM events WHERE organiser_id=? AND status='published'");
        $stmt->bind_param("i", $organiser_id);
        $stmt->execute();
        $totalEvents = $stmt->get_result()->fetch_assoc()['total'];

        $stmt = $db->prepare("SELECT COUNT(*) as total FROM bookings b JOIN events e ON b.event_id=e.id WHERE e.organiser_id=? AND b.status='active'");
        $stmt->bind_param("i", $organiser_id);
        $stmt->execute();
        $totalTickets = $stmt->get_result()->fetch_assoc()['total'];

        $stmt = $db->prepare("SELECT COALESCE(SUM(b.total_price),0) as total FROM bookings b JOIN events e ON b.event_id=e.id WHERE e.organiser_id=? AND b.status='active'");
        $stmt->bind_param("i", $organiser_id);
        $stmt->execute();
        $totalRevenue = $stmt->get_result()->fetch_assoc()['total'];

        $stmt = $db->prepare("SELECT COUNT(*) as total FROM refund_requests rr JOIN bookings b ON rr.booking_id=b.id JOIN events e ON b.event_id=e.id WHERE e.organiser_id=? AND rr.status='pending'");
        $stmt->bind_param("i", $organiser_id);
        $stmt->execute();
        $pendingRefunds = $stmt->get_result()->fetch_assoc()['total'];

        // Recent events
        $stmt = $db->prepare("SELECT * FROM events WHERE organiser_id=? ORDER BY created_at DESC LIMIT 5");
        $stmt->bind_param("i", $organiser_id);
        $stmt->execute();
        $recentEvents = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Pending venue requests
        $stmt = $db->prepare("SELECT vbr.*, v.name as venue_name FROM venue_booking_requests vbr JOIN venues v ON vbr.venue_id=v.id WHERE vbr.organiser_id=? AND vbr.status='pending'");
        $stmt->bind_param("i", $organiser_id);
        $stmt->execute();
        $pendingRequests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $db->close();
        require 'views/dashboard.php';
    }
}
