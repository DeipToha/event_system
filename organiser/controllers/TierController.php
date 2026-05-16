<?php
class TierController {

    public function manage() {
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_GET['event_id'] ?? 0);

        $stmt = $db->prepare("SELECT * FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        $event = $stmt->get_result()->fetch_assoc();
        if (!$event) redirect('index.php?page=events');

        $stmt2 = $db->prepare("SELECT t.*, (SELECT COUNT(*) FROM bookings b WHERE b.tier_id=t.id AND b.status='active') as sold FROM ticket_tiers t WHERE t.event_id=?");
        $stmt2->bind_param("i", $event_id);
        $stmt2->execute();
        $tiers = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        $flash = getFlash();
        $db->close();
        require 'views/tier/manage.php';
    }

    public function create() {
        if (!isPost()) redirect('index.php?page=events');
        $db = getDB();
        $org_id   = $_SESSION['user_id'];
        $event_id = (int)($_POST['event_id'] ?? 0);

        // Verify ownership
        $stmt = $db->prepare("SELECT id FROM events WHERE id=? AND organiser_id=?");
        $stmt->bind_param("ii", $event_id, $org_id);
        $stmt->execute();
        if (!$stmt->get_result()->fetch_assoc()) redirect('index.php?page=events');

        $name        = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $price       = (float)($_POST['price'] ?? 0);
        $total_seats = (int)($_POST['total_seats'] ?? 0);
        $sales_start = $_POST['sales_start'] ?? '';
        $sales_end   = $_POST['sales_end'] ?? '';

        if (!$name || $price < 0 || $total_seats < 1) {
            setFlash('error', 'Invalid tier data.');
        } else {
            $stmt2 = $db->prepare("INSERT INTO ticket_tiers (event_id, name, description, price, total_seats, sales_start, sales_end) VALUES (?,?,?,?,?,?,?)");
            $stmt2->bind_param("isssdss", $event_id, $name, $description, $price, $total_seats, $sales_start, $sales_end);
            $stmt2->execute();
            setFlash('success', 'Ticket tier added successfully!');
        }
        $db->close();
        redirect('index.php?page=tiers&action=manage&event_id=' . $event_id);
    }

    public function edit() {
        $db = getDB();
        $org_id  = $_SESSION['user_id'];
        $tier_id = (int)($_GET['id'] ?? 0);

        $stmt = $db->prepare("SELECT t.*, e.organiser_id FROM ticket_tiers t JOIN events e ON t.event_id=e.id WHERE t.id=? AND e.organiser_id=?");
        $stmt->bind_param("ii", $tier_id, $org_id);
        $stmt->execute();
        $tier = $stmt->get_result()->fetch_assoc();
        if (!$tier) redirect('index.php?page=events');

        if (isPost()) {
            $name        = sanitize($_POST['name'] ?? '');
            $description = sanitize($_POST['description'] ?? '');
            $price       = (float)($_POST['price'] ?? 0);
            $total_seats = (int)($_POST['total_seats'] ?? 0);
            $sales_start = $_POST['sales_start'] ?? '';
            $sales_end   = $_POST['sales_end'] ?? '';

            $stmt2 = $db->prepare("UPDATE ticket_tiers SET name=?, description=?, price=?, total_seats=?, sales_start=?, sales_end=? WHERE id=?");
            $stmt2->bind_param("ssdsssi", $name, $description, $price, $total_seats, $sales_start, $sales_end, $tier_id);
            $stmt2->execute();
            $db->close();
            setFlash('success', 'Tier updated.');
            redirect('index.php?page=tiers&action=manage&event_id=' . $tier['event_id']);
        }
        $db->close();
        require 'views/tier/edit.php';
    }

    public function delete() {
        if (!isPost()) redirect('index.php?page=events');
        $db = getDB();
        $org_id  = $_SESSION['user_id'];
        $tier_id = (int)($_POST['tier_id'] ?? 0);

        $stmt = $db->prepare("SELECT t.*, e.organiser_id FROM ticket_tiers t JOIN events e ON t.event_id=e.id WHERE t.id=? AND e.organiser_id=?");
        $stmt->bind_param("ii", $tier_id, $org_id);
        $stmt->execute();
        $tier = $stmt->get_result()->fetch_assoc();
        if (!$tier) redirect('index.php?page=events');

        // Check if tickets sold
        $stmt2 = $db->prepare("SELECT COUNT(*) as cnt FROM bookings WHERE tier_id=? AND status='active'");
        $stmt2->bind_param("i", $tier_id);
        $stmt2->execute();
        $cnt = $stmt2->get_result()->fetch_assoc()['cnt'];

        if ($cnt > 0) {
            setFlash('error', 'Cannot delete tier — tickets have already been sold.');
        } else {
            $stmt3 = $db->prepare("DELETE FROM ticket_tiers WHERE id=?");
            $stmt3->bind_param("i", $tier_id);
            $stmt3->execute();
            setFlash('success', 'Tier deleted.');
        }
        $event_id = $tier['event_id'];
        $db->close();
        redirect('index.php?page=tiers&action=manage&event_id=' . $event_id);
    }
}
