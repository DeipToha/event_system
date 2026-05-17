<?php $pageTitle = 'Announcements — ' . htmlspecialchars($event['title']); require BASE_PATH .'views/layout/header.php'; ?>

<div class="flex gap-2 mb-2">
    <a href="index.php?page=events" class="btn btn-secondary btn-sm">← Events</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
<!-- Sent Announcements -->
<div class="card">
    <div class="card-title">Sent Announcements</div>
    <?php if ($announcements): ?>
    <?php foreach($announcements as $a): ?>
    <div style="border:1px solid var(--border);border-radius:8px;padding:14px;margin-bottom:12px;">
        <div class="flex justify-between items-center mb-1">
            <span class="font-bold"><?= htmlspecialchars($a['title']) ?></span>
            <span class="text-muted text-sm"><?= formatDate($a['sent_at']) ?></span>
        </div>
        <p class="text-sm"><?= htmlspecialchars($a['body']) ?></p>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="empty-state"><div class="empty-state-icon">📢</div><p>No announcements sent yet.</p></div>
    <?php endif; ?>
</div>

<!-- Send New -->
<div class="card">
    <div class="card-title">Send Announcement</div>
    <p class="text-muted text-sm mb-2">This will appear on all ticket holders' dashboards.</p>
    <form method="POST" action="index.php?page=announcements&action=send" id="announcement-form">
        <?= csrfField() ?>
        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
        <div class="form-group mb-2">
            <label class="form-label">Title *</label>
            <input type="text" name="title" id="ann_title" class="form-control" placeholder="e.g. Schedule Update">
            <span id="err-ann_title" style="color:var(--error,#e53e3e);font-size:12px;"></span>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Message *</label>
            <textarea name="body" id="ann_body" class="form-control" rows="5" placeholder="Your message to all ticket holders..."></textarea>
            <span id="err-ann_body" style="color:var(--error,#e53e3e);font-size:12px;"></span>
        </div>
        <button type="submit" class="btn btn-primary w-full">📢 Send to All Ticket Holders</button>
    </form>
</div>
</div>

<?php require BASE_PATH .'views/layout/footer.php'; ?>
