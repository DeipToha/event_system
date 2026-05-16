<?php
class AnalyticsController {
    public function index() {
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_GET['event_id'] ?? 0);

        $stmt = $db->prepare("SELECT * FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        if (!$event) redirect('index.php?page=events');

        // Total revenue & tickets
        $stmt2 = $db->prepare("SELECT COUNT(*) as total_bookings, SUM(quantity) as total_tickets, SUM(total_price) as total_revenue FROM bookings WHERE event_id=? AND status='active'");
        $stmt2->bind_param("i", $event_id);
        $stmt2->execute();
        $summary = $stmt2->get_result()->fetch_assoc();

        // Revenue per tier
        $stmt3 = $db->prepare("SELECT t.name, t.total_seats, t.price, COUNT(b.id) as bookings_count, SUM(b.quantity) as sold, SUM(b.total_price) as revenue FROM ticket_tiers t LEFT JOIN bookings b ON b.tier_id=t.id AND b.status='active' WHERE t.event_id=? GROUP BY t.id");
        $stmt3->bind_param("i", $event_id);
        $stmt3->execute();
        $tierRevenue = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);

        // Ticket sales over time (daily)
        $stmt4 = $db->prepare("SELECT DATE(created_at) as sale_date, SUM(quantity) as tickets_sold, SUM(total_price) as revenue FROM bookings WHERE event_id=? AND status='active' GROUP BY DATE(created_at) ORDER BY sale_date ASC");
        $stmt4->bind_param("i", $event_id);
        $stmt4->execute();
        $salesOverTime = $stmt4->get_result()->fetch_all(MYSQLI_ASSOC);

        // Occupancy rate
        $stmt5 = $db->prepare("SELECT SUM(total_seats) as total_capacity FROM ticket_tiers WHERE event_id=?");
        $stmt5->bind_param("i", $event_id);
        $stmt5->execute();
        $capacity = $stmt5->get_result()->fetch_assoc()['total_capacity'] ?? 0;
        $occupancyRate = $capacity > 0 ? round(($summary['total_tickets'] / $capacity) * 100, 1) : 0;

        // Repeat attendees (attended more than 1 event by this organiser)
        $stmt6 = $db->prepare("
            SELECT u.name, u.email, COUNT(DISTINCT b.event_id) as events_attended
            FROM bookings b
            JOIN events e ON b.event_id=e.id
            JOIN users u ON b.attendee_id=u.id
            WHERE e.organiser_id=? AND b.status='active'
            GROUP BY b.attendee_id
            HAVING events_attended > 1
            ORDER BY events_attended DESC
            LIMIT 10
        ");
        $stmt6->bind_param("i", $org_id);
        $stmt6->execute();
        $repeatAttendees = $stmt6->get_result()->fetch_all(MYSQLI_ASSOC);

        $db->close();
        require BASE_PATH . 'views/analytics/index.php';
    }
}
