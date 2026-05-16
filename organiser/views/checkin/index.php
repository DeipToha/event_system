<?php $pageTitle = 'Check-in — ' . htmlspecialchars($event['title']); require BASE_PATH . 'views/layout/header.php'; ?>

<div class="flex gap-2 mb-2">
    <a href="index.php?page=checkin&action=stats&event_id=<?= $event['id'] ?>" class="btn btn-secondary btn-sm">📊 View Stats</a>
    <a href="index.php?page=events" class="btn btn-secondary btn-sm">← Events</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">
<div class="checkin-box">
    <h2 style="font-family:'Syne',sans-serif;text-align:center;margin-bottom:6px;">✅ Ticket Check-in</h2>
    <p class="text-muted text-sm" style="text-align:center;margin-bottom:20px;">Enter ticket code to validate entry</p>

    <form id="checkin-form">
        <input type="hidden" id="event_id" value="<?= $event['id'] ?>">
        <div class="checkin-input-row">
            <input type="text" id="ticket_code" class="form-control" placeholder="TKT-2025-001" autocomplete="off" autofocus>
            <button type="submit" id="checkin-btn" class="btn btn-primary">Check In</button>
        </div>
    </form>

    <div id="checkin-result"></div>
</div>

<!-- Live Stats -->
<div class="card" id="live-stats">
    <div class="card-title">Live Stats</div>
    <div class="stats-grid" style="grid-template-columns:1fr 1fr;">
        <div class="stat-card">
            <span class="stat-icon">🎫</span>
            <span class="stat-value" id="stat-sold">—</span>
            <span class="stat-label">Total Sold</span>
        </div>
        <div class="stat-card">
            <span class="stat-icon">✅</span>
            <span class="stat-value" id="stat-checked">—</span>
            <span class="stat-label">Checked In</span>
        </div>
    </div>
    <div class="stat-card mt-1">
        <span class="stat-icon">📈</span>
        <span class="stat-value" id="stat-rate">—</span>
        <span class="stat-label">Check-in Rate</span>
    </div>
    <a href="index.php?page=checkin&action=stats&event_id=<?= $event['id'] ?>" class="btn btn-secondary w-full mt-2" style="justify-content:center;">View Full Stats</a>
</div>
</div>

<script>
// Load stats on page load
document.addEventListener('DOMContentLoaded', function() {
    updateStats();
});
</script>

<?php require BASE_PATH .  'views/layout/footer.php'; ?>
