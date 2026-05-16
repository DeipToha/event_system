<?php $pageTitle = 'My Profile'; require 'views/layout/header.php'; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
<!-- Profile Update -->
<div class="card">
    <div class="card-title">Organisation Profile</div>
    <form method="POST" action="index.php?page=profile&action=update" enctype="multipart/form-data">
        <div class="form-group mb-2">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
            <span class="form-hint">Email cannot be changed</span>
        </div>
        <hr class="section-divider">
        <div class="form-group mb-2">
            <label class="form-label">Organisation Name</label>
            <input type="text" name="org_name" class="form-control" value="<?= htmlspecialchars($profile['org_name'] ?? '') ?>" required>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Description</label>
            <textarea name="org_description" class="form-control"><?= htmlspecialchars($profile['org_description'] ?? '') ?></textarea>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Website</label>
            <input type="url" name="website" class="form-control" value="<?= htmlspecialchars($profile['website'] ?? '') ?>" placeholder="https://yoursite.com">
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Organisation Logo</label>
            <?php if (!empty($profile['org_logo_path'])): ?>
          <img src="/organiser_mvc/organiser/public/uploads/<?= htmlspecialchars($profile['org_logo_path']) ?>" style="width:100px; height:100px; object-fit:cover; border-radius:8px;">
            <?php endif; ?>
            <input type="file" name="org_logo" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<!-- Change Password -->
<div class="card">
    <div class="card-title">Change Password</div>
    <form method="POST" action="index.php?page=profile&action=changePassword">
        <div class="form-group mb-2">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Password</button>
    </form>

    <hr class="section-divider">
    <div class="card-title">Account Status</div>
    <p class="text-sm text-muted">Status: <span class="badge badge-<?= $profile['status'] ?>"><?= ucfirst($profile['status']) ?></span></p>
    <p class="text-sm text-muted mt-1">Member since: <?= date('d M Y', strtotime($user['created_at'])) ?></p>
</div>
</div>

<?php require 'views/layout/footer.php'; ?>
