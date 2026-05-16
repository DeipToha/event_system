<?php $pageTitle = 'Discount Codes — ' . htmlspecialchars($event['title']); require 'views/layout/header.php'; ?>

<div class="flex gap-2 mb-2">
    <a href="index.php?page=events" class="btn btn-secondary btn-sm">← Back to Events</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
<!-- Existing Codes -->
<div class="card">
    <div class="card-title">Promo Codes</div>
    <?php if ($codes): ?>
    <div class="table-wrap">
    <table>
        <thead><tr><th>Code</th><th>Discount</th><th>Usage</th><th>Valid Until</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach($codes as $c): ?>
        <tr>
            <td class="font-bold text-accent" style="letter-spacing:1px;"><?= htmlspecialchars($c['code']) ?></td>
            <td><?= $c['discount_pct'] ?>%</td>
            <td><?= $c['uses_count'] ?> / <?= $c['max_uses'] ?></td>
            <td class="text-sm text-muted"><?= $c['valid_until'] ? date('d M Y', strtotime($c['valid_until'])) : '—' ?></td>
            <td><span class="badge <?= $c['is_active'] ? 'badge-approved' : 'badge-draft' ?>"><?= $c['is_active'] ? 'Active' : 'Inactive' ?></span></td>
            <td>
                <form method="POST" action="index.php?page=discounts&action=toggle" style="display:inline;">
                    <input type="hidden" name="code_id" value="<?= $c['id'] ?>">
                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                    <button type="submit" class="btn btn-secondary btn-sm"><?= $c['is_active'] ? 'Disable' : 'Enable' ?></button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
    <div class="empty-state"><div class="empty-state-icon">🏷️</div><p>No discount codes yet.</p></div>
    <?php endif; ?>
</div>

<!-- Create Code -->
<div class="card">
    <div class="card-title">Create Code</div>
    <form method="POST" action="index.php?page=discounts&action=create">
        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
        <div class="form-group mb-2">
            <label class="form-label">Code *</label>
            <input type="text" name="code" class="form-control" placeholder="e.g. SAVE20" style="text-transform:uppercase;" required>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Discount % *</label>
            <input type="number" name="discount_pct" class="form-control" placeholder="20" min="1" max="100" required>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Max Uses *</label>
            <input type="number" name="max_uses" class="form-control" placeholder="50" min="1" required>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Valid Until</label>
            <input type="datetime-local" name="valid_until" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-full">Create Code</button>
    </form>
</div>
</div>

<?php require 'views/layout/footer.php'; ?>
