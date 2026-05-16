<?php $pageTitle = 'Bookings — ' . htmlspecialchars($event['title']); require BASE_PATH . 'views/layout/header.php'; ?>

<div class="flex gap-2 mb-2">
    <a href="index.php?page=events" class="btn btn-secondary btn-sm">← Events</a>
</div>

<!-- Filters -->
<form method="GET" action="index.php" class="card" style="padding:14px;">
    <input type="hidden" name="page" value="bookings">
    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
    <div class="filter-bar">
        <select name="tier_id" class="form-control">
            <option value="">All Tiers</option>
            <?php foreach($tierList as $t): ?>
            <option value="<?= $t['id'] ?>" <?= ($_GET['tier_id']??'')==$t['id']?'selected':'' ?>><?= htmlspecialchars($t['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="checked_in" class="form-control">
            <option value="">All Check-in Status</option>
            <option value="0" <?= isset($_GET['checked_in'])&&$_GET['checked_in']==='0'?'selected':'' ?>>Not Checked In</option>
            <option value="1" <?= ($_GET['checked_in']??'')==='1'?'selected':'' ?>>Checked In</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="index.php?page=bookings&action=index&event_id=<?= $event['id'] ?>" class="btn btn-secondary">Reset</a>
        <span class="text-muted text-sm"><?= count($bookings) ?> results</span>
    </div>
</form>

<div class="card">
<?php if ($bookings): ?>
<div class="table-wrap">
<table>
    <thead><tr><th>Attendee</th><th>Email</th><th>Tier</th><th>Qty</th><th>Total Paid</th><th>Ticket Code</th><th>Status</th><th>Check-in</th></tr></thead>
    <tbody>
    <?php foreach($bookings as $b): ?>
    <tr>
        <td class="font-bold"><?= htmlspecialchars($b['attendee_name']) ?></td>
        <td class="text-muted text-sm"><?= htmlspecialchars($b['attendee_email']) ?></td>
        <td><?= htmlspecialchars($b['tier_name']) ?></td>
        <td><?= $b['quantity'] ?></td>
        <td class="text-accent">৳<?= number_format($b['total_price'], 2) ?></td>
        <td style="font-family:monospace;font-size:12px;letter-spacing:1px;"><?= htmlspecialchars($b['ticket_code']) ?></td>
        <td><span class="badge badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
        <td>
            <?php if ($b['checked_in']): ?>
            <span class="badge badge-approved">✅ <?= date('d M, H:i', strtotime($b['checked_in_at'])) ?></span>
            <?php else: ?>
            <span class="badge badge-draft">Not yet</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php else: ?>
<div class="empty-state"><div class="empty-state-icon">🎫</div><p>No bookings found for this filter.</p></div>
<?php endif; ?>
</div>

<?php require BASE_PATH . 'views/layout/footer.php'; ?>
