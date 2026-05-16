<?php $pageTitle = 'Refund Requests'; require BASE_PATH .'views/layout/header.php'; ?>

<form method="GET" action="index.php" class="card" style="padding:14px;">
    <input type="hidden" name="page" value="refunds">
    <div class="filter-bar">
        <select name="status" class="form-control">
            <option value="">All Statuses</option>
            <option value="pending"  <?= ($_GET['status']??'')==='pending'?'selected':'' ?>>Pending</option>
            <option value="approved" <?= ($_GET['status']??'')==='approved'?'selected':'' ?>>Approved</option>
            <option value="rejected" <?= ($_GET['status']??'')==='rejected'?'selected':'' ?>>Rejected</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="index.php?page=refunds" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="card">
<?php if ($refunds): ?>
<?php foreach($refunds as $r): ?>
<div style="border:1px solid var(--border);border-radius:8px;padding:16px;margin-bottom:12px;">
    <div class="flex justify-between items-center mb-1">
        <div>
            <span class="font-bold"><?= htmlspecialchars($r['attendee_name']) ?></span>
            <span class="text-muted text-sm"> — <?= htmlspecialchars($r['attendee_email']) ?></span>
        </div>
        <span class="badge badge-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span>
    </div>
    <p class="text-sm text-muted mb-1">Event: <strong><?= htmlspecialchars($r['event_title']) ?></strong> | Ticket: <code><?= htmlspecialchars($r['ticket_code']) ?></code> | Amount: <span class="text-accent">৳<?= number_format($r['total_price'], 2) ?></span></p>
    <p class="text-sm mb-2">Reason: <?= htmlspecialchars($r['reason']) ?></p>

    <?php if ($r['organiser_note']): ?>
    <p class="text-sm text-muted mb-2">Your note: <?= htmlspecialchars($r['organiser_note']) ?></p>
    <?php endif; ?>

    <?php if ($r['status'] === 'pending'): ?>
    <form method="POST" action="index.php?page=refunds&action=process" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
        <input type="hidden" name="refund_id" value="<?= $r['id'] ?>">
        <div class="form-group" style="flex:1;min-width:200px;">
            <label class="form-label">Note (optional)</label>
            <input type="text" name="organiser_note" class="form-control" placeholder="Reason for decision...">
        </div>
        <button type="submit" name="action" value="approve" class="btn btn-success">✅ Approve</button>
        <button type="submit" name="action" value="reject"  class="btn btn-danger" data-confirm="Reject this refund request?">✖ Reject</button>
    </form>
    <?php endif; ?>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="empty-state"><div class="empty-state-icon">💸</div><p>No refund requests found.</p></div>
<?php endif; ?>
</div>

<?php require BASE_PATH . 'views/layout/footer.php'; ?>
