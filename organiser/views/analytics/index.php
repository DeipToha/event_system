<?php $pageTitle = 'Analytics — ' . htmlspecialchars($event['title']); require BASE_PATH . 'views/layout/header.php'; ?>

<div class="flex gap-2 mb-2">
    <a href="index.php?page=events" class="btn btn-secondary btn-sm">← Events</a>
</div>

<!-- Summary Stats -->
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card">
        <span class="stat-icon">💰</span>
        <span class="stat-value">৳<?= number_format($summary['total_revenue'] ?? 0) ?></span>
        <span class="stat-label">Total Revenue</span>
    </div>
    <div class="stat-card">
        <span class="stat-icon">🎫</span>
        <span class="stat-value"><?= $summary['total_tickets'] ?? 0 ?></span>
        <span class="stat-label">Tickets Sold</span>
    </div>
    <div class="stat-card">
        <span class="stat-icon">🏟️</span>
        <span class="stat-value"><?= $occupancyRate ?>%</span>
        <span class="stat-label">Occupancy Rate</span>
    </div>
    <div class="stat-card">
        <span class="stat-icon">🔁</span>
        <span class="stat-value"><?= count($repeatAttendees) ?></span>
        <span class="stat-label">Repeat Attendees</span>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
<!-- Revenue per Tier -->
<div class="card">
    <div class="card-title">Revenue per Tier</div>
    <div class="table-wrap">
    <table>
        <thead><tr><th>Tier</th><th>Price</th><th>Sold</th><th>Revenue</th><th>Occupancy</th></tr></thead>
        <tbody>
        <?php foreach($tierRevenue as $t): ?>
        <?php $occ = $t['total_seats']>0 ? round(($t['sold']/$t['total_seats'])*100,1) : 0; ?>
        <tr>
            <td class="font-bold"><?= htmlspecialchars($t['name']) ?></td>
            <td>৳<?= number_format($t['price'],2) ?></td>
            <td><?= $t['sold'] ?>/<?= $t['total_seats'] ?></td>
            <td class="text-accent font-bold">৳<?= number_format($t['revenue'] ?? 0) ?></td>
            <td>
                <?= $occ ?>%
                <div class="progress mt-1"><div class="progress-bar" style="width:<?= $occ ?>%"></div></div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<!-- Sales Over Time -->
<div class="card">
    <div class="card-title">Ticket Sales Over Time</div>
    <?php if ($salesOverTime): ?>
    <?php $maxSales = max(array_column($salesOverTime, 'tickets_sold')); ?>
    <div class="chart-bar-container">
        <?php foreach($salesOverTime as $s): ?>
        <?php $h = $maxSales > 0 ? round(($s['tickets_sold']/$maxSales)*100) : 0; ?>
        <div class="chart-bar-wrap">
            <div class="chart-bar" style="height:<?= $h ?>%;" title="<?= $s['sale_date'] ?>: <?= $s['tickets_sold'] ?> tickets"></div>
            <div class="chart-label"><?= date('d/m', strtotime($s['sale_date'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="table-wrap mt-2">
    <table>
        <thead><tr><th>Date</th><th>Tickets</th><th>Revenue</th></tr></thead>
        <tbody>
        <?php foreach($salesOverTime as $s): ?>
        <tr>
            <td class="text-sm"><?= date('d M Y', strtotime($s['sale_date'])) ?></td>
            <td><?= $s['tickets_sold'] ?></td>
            <td class="text-accent">৳<?= number_format($s['revenue']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
    <div class="empty-state"><div class="empty-state-icon">📈</div><p>No sales data yet.</p></div>
    <?php endif; ?>
</div>
</div>

<!-- Repeat Attendees -->
<?php if ($repeatAttendees): ?>
<div class="card">
    <div class="card-title">🔁 Repeat Attendees (Attended Your Events More Than Once)</div>
    <div class="table-wrap">
    <table>
        <thead><tr><th>Name</th><th>Email</th><th>Events Attended</th></tr></thead>
        <tbody>
        <?php foreach($repeatAttendees as $a): ?>
        <tr>
            <td class="font-bold"><?= htmlspecialchars($a['name']) ?></td>
            <td class="text-muted"><?= htmlspecialchars($a['email']) ?></td>
            <td><span class="badge badge-approved"><?= $a['events_attended'] ?> events</span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?php endif; ?>

<?php require BASE_PATH . 'views/layout/footer.php'; ?>
