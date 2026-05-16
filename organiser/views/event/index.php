<?php $pageTitle = 'My Events'; $topbarActions = '<a href="index.php?page=events&action=create" class="btn btn-primary">+ Create Event</a>'; require 'views/layout/header.php'; ?>

<div class="card">
<?php if ($events): ?>
<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Event Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($events as $ev): ?>
    <tr>
        <td class="font-bold"><?= htmlspecialchars($ev['title']) ?></td>
        <td class="text-muted"><?= htmlspecialchars($ev['category_name'] ?? '—') ?></td>
        <td class="text-sm text-muted"><?= date('d M Y, h:i A', strtotime($ev['event_datetime'])) ?></td>
        <td><span class="badge badge-<?= $ev['status'] ?>"><?= ucfirst($ev['status']) ?></span></td>
        <td>
            <div class="flex gap-2">
                <a href="index.php?page=events&action=edit&id=<?= $ev['id'] ?>" class="btn btn-secondary btn-sm">✏️ Edit</a>
                <a href="index.php?page=tiers&action=manage&event_id=<?= $ev['id'] ?>" class="btn btn-secondary btn-sm">🎫 Tiers</a>
                <a href="index.php?page=discounts&action=index&event_id=<?= $ev['id'] ?>" class="btn btn-secondary btn-sm">🏷️ Codes</a>
                <a href="index.php?page=bookings&action=index&event_id=<?= $ev['id'] ?>" class="btn btn-secondary btn-sm">👥 Bookings</a>
                <a href="index.php?page=checkin&action=index&event_id=<?= $ev['id'] ?>" class="btn btn-secondary btn-sm">✅ Check-in</a>
                <a href="index.php?page=announcements&action=index&event_id=<?= $ev['id'] ?>" class="btn btn-secondary btn-sm">📢</a>
                <a href="index.php?page=reviews&action=index&event_id=<?= $ev['id'] ?>" class="btn btn-secondary btn-sm">⭐</a>
                <a href="index.php?page=analytics&action=index&event_id=<?= $ev['id'] ?>" class="btn btn-secondary btn-sm">📊</a>

                <?php if ($ev['status'] !== 'cancelled'): ?>
                <!-- Status Change -->
                <form method="POST" action="index.php?page=events&action=changeStatus" style="display:inline;">
                    <input type="hidden" name="event_id" value="<?= $ev['id'] ?>">
                    <?php if ($ev['status'] === 'draft'): ?>
                    <input type="hidden" name="status" value="published">
                    <button type="submit" class="btn btn-success btn-sm">▶ Publish</button>
                    <?php elseif ($ev['status'] === 'published'): ?>
                    <input type="hidden" name="status" value="draft">
                    <button type="submit" class="btn btn-secondary btn-sm">⏸ Unpublish</button>
                    <?php endif; ?>
                </form>
                <form method="POST" action="index.php?page=events&action=changeStatus" style="display:inline;">
                    <input type="hidden" name="event_id" value="<?= $ev['id'] ?>">
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="btn btn-danger btn-sm" data-confirm="Cancel this event? Attendees will be notified.">✖ Cancel</button>
                </form>
                <?php endif; ?>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php else: ?>
<div class="empty-state"><div class="empty-state-icon">🎭</div><p>No events yet. <a href="index.php?page=events&action=create">Create your first event!</a></p></div>
<?php endif; ?>
</div>

<?php require 'views/layout/footer.php'; ?>
