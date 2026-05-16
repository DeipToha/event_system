<?php
class AnnouncementController {
    public function index() {
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_GET['event_id'] ?? 0);

        $stmt = $db->prepare("SELECT * FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        if (!$event) redirect('index.php?page=events');

        $stmt2 = $db->prepare("SELECT * FROM announcements WHERE event_id=? ORDER BY sent_at DESC");
        $stmt2->bind_param("i", $event_id);
        $stmt2->execute();
        $announcements = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        $flash = getFlash();
        $db->close();
        require BASE_PATH . 'views/announcement/index.php';
    }

    public function send() {
        if (!isPost()) redirect('index.php?page=events');
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_POST['event_id'] ?? 0);
        $title    = sanitize($_POST['title'] ?? '');
        $body     = sanitize($_POST['body'] ?? '');

        $stmt = $db->prepare("SELECT id FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        if (!$stmt->get_result()->fetch_assoc()) redirect('index.php?page=events');

        if (!$title || !$body) {
            setFlash('error', 'Title and body are required.');
        } else {
            $stmt2 = $db->prepare("INSERT INTO announcements (event_id, organiser_id, title, body) VALUES (?,?,?,?)");
            $stmt2->bind_param("iiss", $event_id, $org_id, $title, $body);
            $stmt2->execute();
            setFlash('success', 'Announcement sent to all ticket holders!');
        }
        $db->close();
        redirect('index.php?page=announcements&action=index&event_id=' . $event_id);
    }
}
