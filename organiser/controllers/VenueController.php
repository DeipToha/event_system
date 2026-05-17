<?php
class VenueController {

    public function index() {
        $db = getDB();
        $search = sanitize($_GET['search'] ?? '');
        $city   = sanitize($_GET['city'] ?? '');
        $facility = sanitize($_GET['facility'] ?? '');

        $sql = "SELECT v.*, u.name as manager_name FROM venues v JOIN users u ON v.manager_id=u.id WHERE v.is_active=1";
        $params = []; $types = '';

        if ($search) { $sql .= " AND v.name LIKE ?"; $params[] = "%$search%"; $types .= 's'; }
        if ($city)   { $sql .= " AND v.city LIKE ?"; $params[] = "%$city%"; $types .= 's'; }
        if ($facility){ $sql .= " AND v.facilities LIKE ?"; $params[] = "%$facility%"; $types .= 's'; }

        $stmt = $db->prepare($sql);
        if ($params) { $stmt->bind_param($types, ...$params); }
        $stmt->execute();
        $venues = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $db->close();
        require 'views/venue/index.php';
    }

    public function detail() {
        $venue_id = (int)($_GET['id'] ?? 0);
        $db = getDB();

        $stmt = $db->prepare("SELECT v.*, u.name as manager_name FROM venues v JOIN users u ON v.manager_id=u.id WHERE v.id=?");
        $stmt->bind_param("i", $venue_id);
        $stmt->execute();
        $venue = $stmt->get_result()->fetch_assoc();
        if (!$venue) { redirect('index.php?page=venues'); }

        $stmt2 = $db->prepare("SELECT * FROM venue_pricing WHERE venue_id=?");
        $stmt2->bind_param("i", $venue_id);
        $stmt2->execute();
        $pricing = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

        // Availability for next 60 days
        $stmt3 = $db->prepare("SELECT * FROM venue_availability WHERE venue_id=? AND date >= CURDATE() AND date <= DATE_ADD(CURDATE(), INTERVAL 60 DAY)");
        $stmt3->bind_param("i", $venue_id);
        $stmt3->execute();
        $availability = $stmt3->get_result()->fetch_all(MYSQLI_ASSOC);

        $db->close();
        require 'views/venue/detail.php';
    }

    public function bookingRequest() {
        $venue_id = (int)($_GET['id'] ?? 0);
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM venues WHERE id=? AND is_active=1");
        $stmt->bind_param("i", $venue_id);
        $stmt->execute();
        $venue = $stmt->get_result()->fetch_assoc();
        if (!$venue) redirect('index.php?page=venues');

        $error = null;
        if (isPost()) {
            verifyCsrfToken();
            $title   = sanitize($_POST['event_title_preview'] ?? '');
            $dates   = $_POST['requested_dates'] ?? '';
            $message = sanitize($_POST['message'] ?? '');
            $dateArr = array_filter(array_map('trim', explode(',', $dates)));

            if (!$title || !$dates || !$message) {
                $error = 'Please fill all fields.';
            } else {
                $datesJson = json_encode($dateArr);
                $org_id = $_SESSION['user_id'];
                $stmt2 = $db->prepare("INSERT INTO venue_booking_requests (venue_id, organiser_id, event_title_preview, requested_dates, message) VALUES (?,?,?,?,?)");
                $stmt2->bind_param("iisss", $venue_id, $org_id, $title, $datesJson, $message);
                $stmt2->execute();
                $db->close();
                setFlash('success', 'Booking request submitted successfully!');
                redirect('index.php?page=venues&action=myRequests');
            }
        }
        $db->close();
        require 'views/venue/booking_request.php';
    }

    public function myRequests() {
        $db = getDB();
        $org_id = $_SESSION['user_id'];
        $stmt = $db->prepare("SELECT vbr.*, v.name as venue_name, v.city FROM venue_booking_requests vbr JOIN venues v ON vbr.venue_id=v.id WHERE vbr.organiser_id=? ORDER BY vbr.submitted_at DESC");
        $stmt->bind_param("i", $org_id);
        $stmt->execute();
        $requests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $db->close();
        require 'views/venue/my_requests.php';
    }
}