<?php $pageTitle = 'Dashboard'; require 'views/organiser/layout/header.php'; ?>

<div class="stats-grid">
    <div class="stat-card">
        <span class="stat-icon">🎭</span>
        <span class="stat-value"><?= $totalEvents ?></span>
        <span class="stat-label">Published Events</span>
    </div>
    <div class="stat-card">
        <span class="stat-icon">🎫</span>
        <span class="stat-value"><?= $totalTickets ?></span>
        <span class="stat-label">Tickets Sold</span>
    </div>
    <div class="stat-card">
        <span class="stat-icon">💰</span>
        <span class="stat-value">৳<?= number_format($totalRevenue) ?></span>
        <span class="stat-label">Total Revenue</span>
    </div>
    <div class="stat-card">
        <span class="stat-icon">💸</span>
        <span class="stat-value"><?= $pendingRefunds ?></span>
        <span class="stat-label">Pending Refunds</span>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
<!-- Recent Events -->
<div class="card">
    <div class="flex justify-between items-center mb-2">
        <div class="card-title" style="margin:0;">Recent Events</div>
        <a href="index.php?page=events&action=create" class="btn btn-primary btn-sm">+ New</a>
    </div>
    <?php if ($recentEvents): ?>
    <div class="table-wrap">
    <table>
        <thead><tr><th>Title</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach($recentEvents as $ev): ?>
        <tr>
            <td><a href="index.php?page=events"><?= htmlspecialchars($ev['title']) ?></a></td>
            <td class="text-muted text-sm"><?= date('d M Y', strtotime($ev['event_datetime'])) ?></td>
            <td><span class="badge badge-<?= $ev['status'] ?>"><?= ucfirst($ev['status']) ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
    <div class="empty-state"><div class="empty-state-icon">🎭</div><p>No events yet. <a href="index.php?page=events&action=create">Create one!</a></p></div>
    <?php endif; ?>
</div>

<!-- Pending Venue Requests -->
<div class="card">
    <div class="flex justify-between items-center mb-2">
        <div class="card-title" style="margin:0;">Venue Requests</div>
        <a href="index.php?page=venues&action=myRequests" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <?php if ($pendingRequests): ?>
    <div class="table-wrap">
    <table>
        <thead><tr><th>Venue</th><th>Event Preview</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach($pendingRequests as $req): ?>
        <tr>
            <td><?= htmlspecialchars($req['venue_name']) ?></td>
            <td class="text-muted text-sm"><?= htmlspecialchars($req['event_title_preview']) ?></td>
            <td><span class="badge badge-<?= $req['status'] ?>"><?= ucfirst($req['status']) ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
    <div class="empty-state"><div class="empty-state-icon">🏟️</div><p>No pending venue requests.</p></div>
    <?php endif; ?>
</div>
</div>

<!-- Quick Links -->
<div class="card mt-2">
    <div class="card-title">Quick Actions</div>
    <div class="flex gap-2" style="flex-wrap:wrap;">
        <a href="index.php?page=events&action=create"   class="btn btn-secondary">🎭 Create Event</a>
        <a href="index.php?page=venues"                 class="btn btn-secondary">🏟️ Browse Venues</a>
        <a href="index.php?page=refunds"                class="btn btn-secondary">💸 Manage Refunds</a>
        <a href="index.php?page=checkin"                class="btn btn-secondary">✅ Check-in</a>
        <a href="index.php?page=analytics"              class="btn btn-secondary">📊 Analytics</a>
    </div>
</div>

<?php require 'views/organiser/layout/footer.php'; ?>
