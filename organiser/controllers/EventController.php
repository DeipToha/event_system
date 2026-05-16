<?php
class EventController {

    public function index() {
        $db = getDB();
        $org_id = $_SESSION['user_id'];
        $stmt = $db->prepare("SELECT e.*, c.name as category_name FROM events e LEFT JOIN categories c ON e.category_id=c.id WHERE e.organiser_id=? ORDER BY e.created_at DESC");
        $stmt->bind_param("i", $org_id);
        $stmt->execute();
        $events = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $db->close();
        require BASE_PATH . 'views/event/index.php';
    }

    public function create() {
        $db = getDB();
        $org_id = $_SESSION['user_id'];

        $cats = $db->query("SELECT * FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);

        $stmt = $db->prepare("SELECT vbr.*, v.name as venue_name, v.id as venue_real_id FROM venue_booking_requests vbr JOIN venues v ON vbr.venue_id=v.id WHERE vbr.organiser_id=? AND vbr.status='approved'");
        $stmt->bind_param("i", $org_id);
        $stmt->execute();
        $approvedVenues = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $error = null;
        if (isPost()) {
            $title          = sanitize($_POST['title'] ?? '');
            $description    = sanitize($_POST['description'] ?? '');
            $category_id    = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
            $event_dt       = $_POST['event_datetime'] ?? '';
            $end_dt         = $_POST['end_datetime'] ?? '';
            $status         = in_array($_POST['status'] ?? '', ['draft','published']) ? $_POST['status'] : 'draft';
            $venue_type     = $_POST['venue_type'] ?? 'custom';
            $venue_id       = null;
            $venue_override = null;

            if ($venue_type === 'platform') {
                $venue_id = !empty($_POST['venue_id']) ? (int)$_POST['venue_id'] : null;
            } else {
                $venue_override = sanitize($_POST['venue_name_override'] ?? '');
            }

            if (!$title || !$event_dt || !$end_dt) {
                $error = 'Title and dates are required.';
            } else {
                $banner = null;
                if (!empty($_FILES['banner_image']['name'])) {
                    $banner = uploadFile($_FILES['banner_image'], BASE_PATH . 'public/uploads/banners/');
                }
                $stmt2 = $db->prepare("INSERT INTO events (organiser_id, venue_id, category_id, title, description, venue_name_override, event_datetime, end_datetime, banner_image_path, status) VALUES (?,?,?,?,?,?,?,?,?,?)");
                $stmt2->bind_param("iiisssssss", $org_id, $venue_id, $category_id, $title, $description, $venue_override, $event_dt, $end_dt, $banner, $status);
                $stmt2->execute();
                $new_id = $stmt2->insert_id;
                $db->close();
                setFlash('success', 'Event created successfully!');
                redirect('index.php?page=tiers&action=manage&event_id=' . $new_id);
            }
        }
        $db->close();
        require BASE_PATH . 'views/event/create.php';
    }

    public function edit() {
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_GET['id'] ?? 0);

        $stmt = $db->prepare("SELECT * FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        if (!$event) redirect('index.php?page=events');

        $cats = $db->query("SELECT * FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
        $stmt2 = $db->prepare("SELECT vbr.*, v.name as venue_name, v.id as venue_real_id FROM venue_booking_requests vbr JOIN venues v ON vbr.venue_id=v.id WHERE vbr.organiser_id=? AND vbr.status='approved'");
        $stmt2->bind_param("i", $org_id);
        $stmt2->execute();
        $approvedVenues = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

        $error = null;
        if (isPost()) {
            $title          = sanitize($_POST['title'] ?? '');
            $description    = sanitize($_POST['description'] ?? '');
            $category_id    = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
            $event_dt       = $_POST['event_datetime'] ?? '';
            $end_dt         = $_POST['end_datetime'] ?? '';
            $venue_type     = $_POST['venue_type'] ?? 'custom';
            $venue_id       = null;
            $venue_override = null;

            if ($venue_type === 'platform') {
                $venue_id = !empty($_POST['venue_id']) ? (int)$_POST['venue_id'] : null;
            } else {
                $venue_override = sanitize($_POST['venue_name_override'] ?? '');
            }

            $banner = $event['banner_image_path'];
            if (!empty($_FILES['banner_image']['name'])) {
                $newBanner = uploadFile($_FILES['banner_image'], BASE_PATH . 'public/uploads/banners/');
                if ($newBanner) $banner = $newBanner;
            }

            $stmt3 = $db->prepare("UPDATE events SET title=?, description=?, category_id=?, venue_id=?, venue_name_override=?, event_datetime=?, end_datetime=?, banner_image_path=? WHERE id=? AND organiser_id=?");
            $stmt3->bind_param("ssiissssii", $title, $description, $category_id, $venue_id, $venue_override, $event_dt, $end_dt, $banner, $event_id, $org_id);
            $stmt3->execute();
            $db->close();
            setFlash('success', 'Event updated successfully!');
            redirect('index.php?page=events');
        }
        $db->close();
        require BASE_PATH . 'views/event/edit.php';
    }

    public function changeStatus() {
        if (!isPost()) redirect('index.php?page=events');
        $db       = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_POST['event_id'] ?? 0);
        $status   = $_POST['status'] ?? '';

        if (!in_array($status, ['published','draft','cancelled'])) redirect('index.php?page=events');

        $stmt = $db->prepare("SELECT * FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        if (!$event) redirect('index.php?page=events');

        $stmt2 = $db->prepare("UPDATE events SET status=? WHERE id=? AND organiser_id=?");
        $stmt2->bind_param("sii", $status, $event_id, $org_id);
        $stmt2->execute();

        if ($status === 'cancelled') {
            $stmt3 = $db->prepare("INSERT INTO announcements (event_id, organiser_id, title, body) VALUES (?,?,'Event Cancelled','We regret to inform you that this event has been cancelled. Refund requests can be submitted from your ticket dashboard.')");
            $stmt3->bind_param("ii", $event_id, $org_id);
            $stmt3->execute();
        }

        $db->close();
        setFlash('success', 'Event status updated to ' . $status . '.');
        redirect('index.php?page=events');
    }
}