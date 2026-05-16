<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Organiser Login</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<div class="auth-page">
<div class="auth-card">
    <div class="auth-logo">⚡ EventPro</div>
    <div class="auth-subtitle">Organiser Portal Sign In</div>

    <?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=login">
        <div class="form-group mb-2">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="your@email.com" required autofocus>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary w-full mt-2" style="justify-content:center;">Sign In</button>
    </form>
    <div class="auth-footer">
        New organiser? <a href="index.php?page=register">Register here</a>
    </div>
</div>
</div>
</body>
</html>
