<?php $pageTitle = 'Ticket Tiers — ' . htmlspecialchars($event['title']); require 'views/layout/header.php'; ?>

<div class="flex gap-2 mb-2">
    <a href="index.php?page=events" class="btn btn-secondary btn-sm">← Back to Events</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
<!-- Existing Tiers -->
<div class="card">
    <div class="card-title">Current Tiers</div>
    <?php if ($tiers): ?>
    <?php foreach($tiers as $t): ?>
    <div style="border:1px solid var(--border);border-radius:8px;padding:14px;margin-bottom:12px;">
        <div class="flex justify-between items-center mb-1">
            <span class="font-bold"><?= htmlspecialchars($t['name']) ?></span>
            <span class="text-accent font-bold">৳<?= number_format($t['price'], 2) ?></span>
        </div>
        <p class="text-muted text-sm mb-1"><?= htmlspecialchars($t['description'] ?? '') ?></p>
        <div class="flex gap-2 text-sm text-muted mb-2">
            <span>🪑 <?= $t['sold'] ?>/<?= $t['total_seats'] ?> sold</span>
        </div>
        <div class="progress mb-2">
            <div class="progress-bar" style="width:<?= $t['total_seats']>0 ? round(($t['sold']/$t['total_seats'])*100) : 0 ?>%"></div>
        </div>
        <div class="flex gap-2">
            <a href="index.php?page=tiers&action=edit&id=<?= $t['id'] ?>" class="btn btn-secondary btn-sm">✏️ Edit</a>
            <?php if ($t['sold'] == 0): ?>
            <form method="POST" action="index.php?page=tiers&action=delete" style="display:inline;">
                <input type="hidden" name="tier_id" value="<?= $t['id'] ?>">
                <button type="submit" class="btn btn-danger btn-sm" data-confirm="Delete this tier?">🗑 Delete</button>
            </form>
            <?php else: ?>
            <span class="btn btn-secondary btn-sm" style="opacity:0.4;cursor:not-allowed;" title="Cannot delete — tickets sold">🗑 Delete</span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="empty-state"><div class="empty-state-icon">🎫</div><p>No tiers yet. Add one on the right.</p></div>
    <?php endif; ?>
</div>

<!-- Add New Tier -->
<div class="card">
    <div class="card-title">Add New Tier</div>
    <form method="POST" action="index.php?page=tiers&action=create">
        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
        <div class="form-group mb-2">
            <label class="form-label">Tier Name *</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. VIP, General, Early Bird" required>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="What's included?"></textarea>
        </div>
        <div class="form-grid mb-2">
            <div class="form-group">
                <label class="form-label">Price (৳) *</label>
                <input type="number" name="price" class="form-control" placeholder="0.00" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Total Seats *</label>
                <input type="number" name="total_seats" class="form-control" placeholder="100" min="1" required>
            </div>
            <div class="form-group">
                <label class="form-label">Sales Start</label>
                <input type="datetime-local" name="sales_start" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Sales End</label>
                <input type="datetime-local" name="sales_end" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add Tier</button>
    </form>
</div>
</div>

<?php require 'views/layout/footer.php'; ?>
