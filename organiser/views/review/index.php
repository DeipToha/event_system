<?php $pageTitle = 'Reviews — ' . htmlspecialchars($event['title']); require BASE_PATH . 'views/layout/header.php'; ?>

<div class="flex gap-2 mb-2">
    <a href="index.php?page=events" class="btn btn-secondary btn-sm">← Events</a>
</div>

<div class="card">
<?php if ($reviews): ?>
<?php foreach($reviews as $r): ?>
<div style="border:1px solid var(--border);border-radius:8px;padding:16px;margin-bottom:14px;">
    <div class="flex justify-between items-center mb-1">
        <span class="font-bold"><?= htmlspecialchars($r['attendee_name']) ?></span>
        <div class="flex gap-2 items-center">
            <span class="stars"><?= str_repeat('★', $r['rating']) ?><?= str_repeat('☆', 5 - $r['rating']) ?></span>
            <span class="text-muted text-sm"><?= date('d M Y', strtotime($r['created_at'])) ?></span>
        </div>
    </div>
    <p class="mb-2"><?= htmlspecialchars($r['review_text']) ?></p>

    <?php if ($r['organiser_reply']): ?>
    <div style="background:var(--surface2);border-left:3px solid var(--accent);padding:10px 14px;border-radius:0 6px 6px 0;margin-bottom:8px;">
        <span class="text-sm text-accent font-bold">Your reply:</span>
        <p class="text-sm mt-1"><?= htmlspecialchars($r['organiser_reply']) ?></p>
    </div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=reviews&action=reply">
        <?= csrfField() ?>
        <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
        <div class="flex gap-2">
            <input type="text" name="organiser_reply" class="form-control" placeholder="<?= $r['organiser_reply'] ? 'Update reply...' : 'Write a reply...' ?>" value="<?= htmlspecialchars($r['organiser_reply'] ?? '') ?>">
            <button type="submit" class="btn btn-primary btn-sm"><?= $r['organiser_reply'] ? 'Update' : 'Reply' ?></button>
        </div>
    </form>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="empty-state"><div class="empty-state-icon">⭐</div><p>No reviews yet for this event.</p></div>
<?php endif; ?>
</div>

<?php require BASE_PATH . 'views/layout/footer.php'; ?>
