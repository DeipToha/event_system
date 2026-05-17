<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Organiser Register — EventPro</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<div class="auth-page">
<div class="auth-card" style="max-width:520px;">
    <div class="auth-logo">⚡ EventPro</div>
    <div class="auth-subtitle">Create Organiser Account</div>

    <?php if (isset($error) && $error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!isset($success) || !$success): ?>
    <form method="POST" action="index.php?page=register" enctype="multipart/form-data" id="register-form">
        <?= csrfField() ?>
        <div class="form-grid mb-2">
            <div class="form-group">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Your full name">
                <span id="err-name" style="color:var(--error,#e53e3e);font-size:12px;"></span>
            </div>
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" placeholder="01XXXXXXXXX">
            </div>
            <div class="form-group form-full">
                <label class="form-label">Email *</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="your@email.com">
                <span id="err-email" style="color:var(--error,#e53e3e);font-size:12px;"></span>
            </div>
            <div class="form-group">
                <label class="form-label">Password *</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Min 6 characters">
                <span id="err-password" style="color:var(--error,#e53e3e);font-size:12px;"></span>
            </div>
            <div class="form-group">
                <label class="form-label">&nbsp;</label>
                <span class="form-hint" style="padding-top:10px;display:block;">Use a strong password</span>
            </div>
        </div>
        <hr class="section-divider">
        <div class="card-title" style="margin-bottom:12px;">Organisation Details</div>
        <div class="form-grid mb-2">
            <div class="form-group form-full">
                <label class="form-label">Organisation Name *</label>
                <input type="text" name="org_name" id="org_name" class="form-control" placeholder="e.g. Dhaka Events Pro">
                <span id="err-org_name" style="color:var(--error,#e53e3e);font-size:12px;"></span>
            </div>
            <div class="form-group form-full">
                <label class="form-label">Description</label>
                <textarea name="org_description" class="form-control" placeholder="Tell us about your organisation..."></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Website</label>
                <input type="url" name="website" class="form-control" placeholder="https://yoursite.com">
            </div>
            <div class="form-group">
                <label class="form-label">Logo</label>
                <input type="file" name="org_logo" class="form-control" accept="image/*">
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-full" style="justify-content:center;">Submit for Approval</button>
        <div class="form-hint mt-1" style="text-align:center;">Your account must be approved by admin before you can log in.</div>
    </form>
    <?php endif; ?>

    <div class="auth-footer">Already registered? <a href="index.php?page=login">Sign In</a></div>
</div>
</div>
</body>
</html>
