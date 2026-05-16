<?php $pageTitle = 'Check-in Stats — ' . htmlspecialchars($event['title']); require BASE_PATH . 'views/layout/header.php'; ?>

<div class="flex gap-2 mb-2">
    <a href="index.php?page=checkin&action=index&event_id=<?= $event['id'] ?>" class="btn btn-primary btn-sm">✅ Go to Check-in</a>
    <a href="index.php?page=events" class="btn btn-secondary btn-sm">← Events</a>
</div>

<!-- Overall Stats -->
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <span class="stat-icon">🎫</span>
        <span class="stat-value"><?= $overall['total_sold'] ?? 0 ?></span>
        <span class="stat-label">Total Tickets Sold</span>
    </div>
    <div class="stat-card">
        <span class="stat-icon">✅</span>
        <span class="stat-value"><?= $overall['total_checked'] ?? 0 ?></span>
        <span class="stat-label">Checked In</span>
    </div>
    <div class="stat-card">
        <span class="stat-icon">📈</span>
        <?php $rate = ($overall['total_sold'] > 0) ? round(($overall['total_checked'] / $overall['total_sold']) * 100, 1) : 0; ?>
        <span class="stat-value"><?= $rate ?>%</span>
        <span class="stat-label">Check-in Rate</span>
    </div>
</div>

<!-- By Tier -->
<div class="card">
    <div class="card-title">By Ticket Tier</div>
    <div class="table-wrap">
    <table>
        <thead><tr><th>Tier</th><th>Sold</th><th>Checked In</th><th>Rate</th><th>Progress</th></tr></thead>
        <tbody>
        <?php foreach($tierStats as $t): ?>
        <?php $tr = $t['sold'] > 0 ? round(($t['checked_in'] / $t['sold']) * 100, 1) : 0; ?>
        <tr>
            <td class="font-bold"><?= htmlspecialchars($t['name']) ?></td>
            <td><?= $t['sold'] ?></td>
            <td><?= $t['checked_in'] ?? 0 ?></td>
            <td class="text-accent"><?= $tr ?>%</td>
            <td style="min-width:120px;">
                <div class="progress"><div class="progress-bar" style="width:<?= $tr ?>%"></div></div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<?php require BASE_PATH . 'views/layout/footer.php'; ?>
