<?php
class ReviewController {
    public function index() {
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_GET['event_id'] ?? 0);

        // Validation
        if ($event_id <= 0) redirect('index.php?page=events');

        $stmt = $db->prepare("SELECT * FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        if (!$event) redirect('index.php?page=events');

        $stmt2 = $db->prepare("SELECT r.*, u.name as attendee_name FROM event_reviews r JOIN users u ON r.attendee_id=u.id WHERE r.event_id=? ORDER BY r.created_at DESC");
        $stmt2->bind_param("i", $event_id);
        $stmt2->execute();
        $reviews = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        $flash = getFlash();
        $db->close();
        require BASE_PATH . 'views/review/index.php';
    }

    public function reply() {
        if (!isPost()) redirect('index.php?page=events');

        // CSRF check
        verifyCsrfToken();

        $db        = getDB();
        $org_id    = $_SESSION['user_id'];
        $review_id = (int)($_POST['review_id'] ?? 0);
        $reply     = sanitize($_POST['organiser_reply'] ?? '');
        $event_id  = (int)($_POST['event_id'] ?? 0);

        // Validation
        if ($review_id <= 0 || $event_id <= 0) {
            redirect('index.php?page=events');
        }

        if (!isValidLength($reply)) {
            setFlash('error', 'Reply cannot be empty.');
            redirect('index.php?page=reviews&action=index&event_id=' . $event_id);
        }

        if (!isValidLength($reply, 1, 1000)) {
            setFlash('error', 'Reply must be under 1000 characters.');
            redirect('index.php?page=reviews&action=index&event_id=' . $event_id);
        }

        $stmt = $db->prepare("SELECT r.* FROM event_reviews r JOIN events e ON r.event_id=e.id WHERE r.id=? AND e.organiser_id=?");
        $stmt->bind_param("ii", $review_id, $org_id);
        $stmt->execute();
        if (!$stmt->get_result()->fetch_assoc()) redirect('index.php?page=events');

        $stmt2 = $db->prepare("UPDATE event_reviews SET organiser_reply=? WHERE id=?");
        $stmt2->bind_param("si", $reply, $review_id);
        $stmt2->execute();
        $db->close();
        setFlash('success', 'Reply posted.');
        redirect('index.php?page=reviews&action=index&event_id=' . $event_id);
    }
}