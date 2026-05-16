<?php $pageTitle = 'My Venue Requests'; require 'views/layout/header.php'; ?>

<div class="flex justify-between items-center mb-2">
    <a href="index.php?page=venues" class="btn btn-secondary btn-sm">← Browse Venues</a>
</div>

<div class="card">
<?php if ($requests): ?>
<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Venue</th>
            <th>City</th>
            <th>Event Preview</th>
            <th>Requested Dates</th>
            <th>Status</th>
            <th>Manager Note</th>
            <th>Submitted</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($requests as $r): ?>
    <tr>
        <td class="font-bold"><?= htmlspecialchars($r['venue_name']) ?></td>
        <td class="text-muted"><?= htmlspecialchars($r['city']) ?></td>
        <td><?= htmlspecialchars($r['event_title_preview']) ?></td>
        <td class="text-sm text-muted">
            <?php $dates = json_decode($r['requested_dates'], true) ?? []; echo implode(', ', $dates); ?>
        </td>
        <td><span class="badge badge-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
        <td class="text-sm text-muted"><?= $r['manager_note'] ? htmlspecialchars($r['manager_note']) : '—' ?></td>
        <td class="text-sm text-muted"><?= date('d M Y', strtotime($r['submitted_at'])) ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php else: ?>
<div class="empty-state"><div class="empty-state-icon">📋</div><p>No venue booking requests yet. <a href="index.php?page=venues">Browse venues</a></p></div>
<?php endif; ?>
</div>

<?php require 'views/layout/footer.php'; ?>
