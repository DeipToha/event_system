<?php $pageTitle = 'Edit Tier'; require 'views/layout/header.php'; ?>

<div style="max-width:500px;">
<div class="card">
    <div class="card-title">Edit Ticket Tier</div>
    <form method="POST" action="index.php?page=tiers&action=edit&id=<?= $tier['id'] ?>">
        <div class="form-group mb-2">
            <label class="form-label">Tier Name *</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($tier['name']) ?>" required>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($tier['description'] ?? '') ?></textarea>
        </div>
        <div class="form-grid mb-2">
            <div class="form-group">
                <label class="form-label">Price (৳) *</label>
                <input type="number" name="price" class="form-control" value="<?= $tier['price'] ?>" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Total Seats *</label>
                <input type="number" name="total_seats" class="form-control" value="<?= $tier['total_seats'] ?>" min="1" required>
            </div>
            <div class="form-group">
                <label class="form-label">Sales Start</label>
                <input type="datetime-local" name="sales_start" class="form-control" value="<?= $tier['sales_start'] ? date('Y-m-d\TH:i', strtotime($tier['sales_start'])) : '' ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Sales End</label>
                <input type="datetime-local" name="sales_end" class="form-control" value="<?= $tier['sales_end'] ? date('Y-m-d\TH:i', strtotime($tier['sales_end'])) : '' ?>">
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="index.php?page=tiers&action=manage&event_id=<?= $tier['event_id'] ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</div>

<?php require 'views/layout/footer.php'; ?>
