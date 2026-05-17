<?php $pageTitle = 'Edit Event'; require 'views/layout/header.php'; ?>

<div style="max-width:760px;">
<div class="card">
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST" action="index.php?page=events&action=edit&id=<?= $event['id'] ?>" enctype="multipart/form-data" id="event-form">
        <?= csrfField() ?>
        <div class="form-grid mb-2">
            <div class="form-group form-full">
                <label class="form-label">Event Title *</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($event['title']) ?>">
                <span id="err-title" style="color:var(--error,#e53e3e);font-size:12px;"></span>
            </div>
            <div class="form-group form-full">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($event['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control">
                    <option value="">— Select Category —</option>
                    <?php foreach($cats as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $event['category_id']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Banner Image</label>
                <?php if ($event['banner_image_path']): ?>
                <img src="public/uploads/banners/<?= htmlspecialchars($event['banner_image_path']) ?>" style="height:50px;border-radius:6px;margin-bottom:6px;display:block;">
                <?php endif; ?>
                <input type="file" name="banner_image" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label class="form-label">Event Start Date & Time *</label>
                <input type="datetime-local" name="event_datetime" id="event_datetime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($event['event_datetime'])) ?>">
                <span id="err-event_datetime" style="color:var(--error,#e53e3e);font-size:12px;"></span>
            </div>
            <div class="form-group">
                <label class="form-label">Event End Date & Time *</label>
                <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($event['end_datetime'])) ?>">
                <span id="err-end_datetime" style="color:var(--error,#e53e3e);font-size:12px;"></span>
            </div>
        </div>

        <hr class="section-divider">
        <div class="card-title" style="margin-bottom:12px;">Venue</div>
        <div class="radio-tabs mb-2">
            <div class="radio-tab">
                <input type="radio" name="venue_type" id="venue_platform" value="platform" <?= $event['venue_id'] ? 'checked' : '' ?>>
                <label for="venue_platform">🏟️ Platform Venue</label>
            </div>
            <div class="radio-tab">
                <input type="radio" name="venue_type" id="venue_custom" value="custom" <?= !$event['venue_id'] ? 'checked' : '' ?>>
                <label for="venue_custom">📍 Custom Address</label>
            </div>
        </div>

        <div id="venue-platform-section">
            <div class="form-group mb-2">
                <label class="form-label">Select Approved Venue Booking</label>
                <select name="venue_id" class="form-control">
                    <option value="">— Select —</option>
                    <?php foreach($approvedVenues as $av): ?>
                    <option value="<?= $av['venue_real_id'] ?>" <?= $event['venue_id']==$av['venue_real_id']?'selected':'' ?>><?= htmlspecialchars($av['venue_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div id="venue-custom-section">
            <div class="form-group mb-2">
                <label class="form-label">Venue / Address</label>
                <input type="text" name="venue_name_override" class="form-control" value="<?= htmlspecialchars($event['venue_name_override'] ?? '') ?>" placeholder="Full address">
            </div>
        </div>

        <div class="flex gap-2 mt-2">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="index.php?page=events" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</div>

<?php require 'views/layout/footer.php'; ?>
