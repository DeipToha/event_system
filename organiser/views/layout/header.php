<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Organiser Panel' ?> — EventPro</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<div class="app-wrapper">
<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="brand-icon">⚡</span>
        <span class="brand-text">EventPro</span>
    </div>
    <div class="sidebar-user">
        <div class="user-avatar"><?= strtoupper(substr($_SESSION['name'] ?? 'O', 0, 1)) ?></div>
        <div>
            <div class="user-name"><?= htmlspecialchars($_SESSION['name'] ?? '') ?></div>
            <div class="user-role"><?= htmlspecialchars($_SESSION['org_name'] ?? '') ?></div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a href="index.php?page=dashboard" class="nav-item <?= ($page==='dashboard')?'active':'' ?>"><span>🏠</span> Dashboard</a>
        <div class="nav-group-label">Events</div>
        <a href="index.php?page=events" class="nav-item <?= ($page==='events')?'active':'' ?>"><span>🎭</span> My Events</a>
        <a href="index.php?page=events&action=create" class="nav-item"><span>➕</span> Create Event</a>
        <div class="nav-group-label">Venues</div>
        <a href="index.php?page=venues" class="nav-item <?= ($page==='venues')?'active':'' ?>"><span>🏟️</span> Browse Venues</a>
        <a href="index.php?page=venues&action=myRequests" class="nav-item"><span>📋</span> My Requests</a>
        <div class="nav-group-label">Operations</div>
        <a href="index.php?page=bookings" class="nav-item <?= ($page==='bookings')?'active':'' ?>"><span>🎫</span> Bookings</a>
        <a href="index.php?page=refunds" class="nav-item <?= ($page==='refunds')?'active':'' ?>"><span>💸</span> Refunds</a>
        <a href="index.php?page=checkin" class="nav-item <?= ($page==='checkin')?'active':'' ?>"><span>✅</span> Check-in</a>
        <div class="nav-group-label">Engagement</div>
        <a href="index.php?page=announcements" class="nav-item <?= ($page==='announcements')?'active':'' ?>"><span>📢</span> Announcements</a>
        <a href="index.php?page=reviews" class="nav-item <?= ($page==='reviews')?'active':'' ?>"><span>⭐</span> Reviews</a>
        <a href="index.php?page=analytics" class="nav-item <?= ($page==='analytics')?'active':'' ?>"><span>📊</span> Analytics</a>
        <div class="nav-group-label">Account</div>
        <a href="index.php?page=profile" class="nav-item <?= ($page==='profile')?'active':'' ?>"><span>👤</span> Profile</a>
        <a href="index.php?page=logout" class="nav-item logout"><span>🚪</span> Logout</a>
    </nav>
</aside>
<!-- Main content -->
<main class="main-content">
<div class="topbar">
    <h1 class="page-title"><?= $pageTitle ?? '' ?></h1>
    <div class="topbar-actions"><?= $topbarActions ?? '' ?></div>
</div>
<?php $flash = getFlash(); if ($flash): ?>
<div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['msg']) ?></div>
<?php endif; ?>
<div class="content-body">
