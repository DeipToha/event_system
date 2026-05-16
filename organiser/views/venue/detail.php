<?php $pageTitle = htmlspecialchars($venue['name']); require 'views/layout/header.php'; ?>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
<div>
    <div class="card">
        <div class="venue-card-img" style="height:180px;border-radius:8px;margin-bottom:16px;font-size:60px;">🏟️</div>
        <div class="card-title"><?= htmlspecialchars($venue['name']) ?></div>
        <p class="text-muted mb-2">📍 <?= htmlspecialchars($venue['address']) ?>, <?= htmlspecialchars($venue['city']) ?></p>
        <p class="mb-2"><?= htmlspecialchars($venue['description']) ?></p>
        <div class="flex gap-2" style="flex-wrap:wrap;margin-bottom:12px;">
            <span class="badge badge-approved">👥 Capacity: <?= number_format($venue['capacity']) ?></span>
            <?php foreach(json_decode($venue['facilities']??'[]',true) as $f): ?>
            <span class="badge badge-draft">✔ <?= htmlspecialchars($f) ?></span>
            <?php endforeach; ?>
        </div>
        <a href="index.php?page=venues&action=bookingRequest&id=<?= $venue['id'] ?>" class="btn btn-primary">📅 Request Booking</a>
    </div>

    <!-- Pricing -->
    <div class="card">
        <div class="card-title">Pricing</div>
        <div class="table-wrap">
        <table>
            <thead><tr><th>Day Type</th><th>Price Per Day</th></tr></thead>
            <tbody>
            <?php foreach($pricing as $p): ?>
            <tr>
                <td><?= ucfirst($p['day_type']) ?></td>
                <td class="text-accent font-bold">৳<?= number_format($p['price_per_day']) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<!-- Availability Calendar -->
<div class="card">
    <div class="card-title">Availability (Next 60 Days)</div>
    <div class="flex gap-2 mb-2" style="flex-wrap:wrap;font-size:11px;">
        <span><span style="color:var(--success)">●</span> Available</span>
        <span><span style="color:var(--error)">●</span> Booked</span>
        <span><span style="color:var(--text-muted)">●</span> Blocked</span>
    </div>

    <?php
    // Build a map of date => status
    $avMap = [];
    foreach($availability as $a) $avMap[$a['date']] = $a['status'];

    $start = new DateTime();
    $end   = new DateTime('+60 days');
    // Find the first day of the start month's week
    $calStart = clone $start;
    $calStart->modify('first day of this month');
    ?>

    <div class="calendar-grid">
        <?php foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $d): ?>
        <div class="cal-day header"><?= $d ?></div>
        <?php endforeach; ?>

        <?php
        $cur = clone $calStart;
        // Add empty cells for days before the 1st
        $firstDow = (int)$calStart->format('w');
        for($i=0;$i<$firstDow;$i++) echo '<div class="cal-day empty"></div>';

        $today = new DateTime();
        for($d=1; $d<=(int)$calStart->format('t'); $d++) {
            $cur->setDate((int)$calStart->format('Y'), (int)$calStart->format('m'), $d);
            $dateStr = $cur->format('Y-m-d');
            $cls = 'empty';
            $status = $avMap[$dateStr] ?? null;
            if ($cur < $today) { $cls = 'empty'; }
            elseif ($status === 'available') { $cls = 'available'; }
            elseif ($status === 'booked')    { $cls = 'booked'; }
            elseif ($status === 'blocked')   { $cls = 'blocked'; }
            echo "<div class=\"cal-day $cls\" title=\"$dateStr\">$d</div>";
        }
        ?>
    </div>

    <div class="mt-2">
        <a href="index.php?page=venues&action=bookingRequest&id=<?= $venue['id'] ?>" class="btn btn-primary w-full" style="justify-content:center;">Request This Venue</a>
    </div>
</div>
</div>

<?php require 'views/layout/footer.php'; ?>
