<?php $pageTitle = 'Request Venue: ' . htmlspecialchars($venue['name']); require 'views/layout/header.php'; ?>

<div style="max-width:600px;">
<div class="card">
    <div class="card-title">Venue Booking Request</div>
    <p class="text-muted mb-2">Venue: <strong class="text-accent"><?= htmlspecialchars($venue['name']) ?></strong> — <?= htmlspecialchars($venue['city']) ?></p>

    <?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=venues&action=bookingRequest&id=<?= $venue['id'] ?>">
        <?= csrfField() ?>
        <div class="form-group mb-2">
            <label class="form-label">Event Title Preview *</label>
            <input type="text" name="event_title_preview" class="form-control" placeholder="e.g. Dhaka Tech Summit 2025">
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Requested Dates * <span class="form-hint">(comma-separated, e.g. 2025-07-10, 2025-07-11)</span></label>
            <input type="text" name="requested_dates" class="form-control" placeholder="YYYY-MM-DD, YYYY-MM-DD">
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Message to Venue Manager *</label>
            <textarea name="message" class="form-control" rows="4" placeholder="Describe your event requirements, setup needs, etc."></textarea>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">Submit Request</button>
            <a href="index.php?page=venues&action=detail&id=<?= $venue['id'] ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</div>

<?php require 'views/layout/footer.php'; ?>
