<?php $pageTitle = 'Browse Venues'; require 'views/layout/header.php'; ?>

<!-- Search & Filter -->
<form method="GET" action="index.php" class="card">
    <input type="hidden" name="page" value="venues">
    <div class="filter-bar">
        <input type="text" name="search" class="form-control" placeholder="Search venue name..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <input type="text" name="city" class="form-control" placeholder="City..." value="<?= htmlspecialchars($_GET['city'] ?? '') ?>">
        <input type="text" name="facility" class="form-control" placeholder="Facility (WiFi, Parking...)" value="<?= htmlspecialchars($_GET['facility'] ?? '') ?>">
        <button type="submit" class="btn btn-primary">🔍 Search</button>
        <a href="index.php?page=venues" class="btn btn-secondary">Reset</a>
    </div>
</form>

<?php if ($venues): ?>
<div class="venue-grid">
<?php foreach($venues as $v): ?>
<div class="venue-card">
    <div class="venue-card-img">🏟️</div>
    <div class="venue-card-body">
        <div class="venue-name"><?= htmlspecialchars($v['name']) ?></div>
        <div class="venue-city">📍 <?= htmlspecialchars($v['city']) ?></div>
        <div class="venue-meta">
            <span>👥 Cap: <?= number_format($v['capacity']) ?></span>
            <?php
            $facilities = json_decode($v['facilities'] ?? '[]', true);
            if ($facilities): ?>
            <span>🔧 <?= implode(', ', array_slice($facilities, 0, 2)) ?><?= count($facilities)>2 ? '...' : '' ?></span>
            <?php endif; ?>
        </div>
        <div class="flex gap-2 mt-2">
            <a href="index.php?page=venues&action=detail&id=<?= $v['id'] ?>" class="btn btn-secondary btn-sm">View Details</a>
            <a href="index.php?page=venues&action=bookingRequest&id=<?= $v['id'] ?>" class="btn btn-primary btn-sm">Request Booking</a>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<?php else: ?>
<div class="empty-state"><div class="empty-state-icon">🏟️</div><p>No venues found. Try a different search.</p></div>
<?php endif; ?>

<?php require 'views/layout/footer.php'; ?>
