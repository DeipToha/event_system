<?php $pageTitle = 'Create Event'; require 'views/layout/header.php'; ?>

<div style="max-width:760px;">
<div class="card">
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST" action="index.php?page=events&action=create" enctype="multipart/form-data" id="event-form">
        <?= csrfField() ?>
        <div class="form-grid mb-2">
            <div class="form-group form-full">
                <label class="form-label">Event Title *</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Dhaka Tech Summit 2025">
                <span id="err-title" style="color:var(--error,#e53e3e);font-size:12px;"></span>
            </div>
            <div class="form-group form-full">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Describe your event..."></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control">
                    <option value="">— Select Category —</option>
                    <?php foreach($cats as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Banner Image</label>
                <input type="file" name="banner_image" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label class="form-label">Event Start Date & Time *</label>
                <input type="datetime-local" name="event_datetime" id="event_datetime" class="form-control">
                <span id="err-event_datetime" style="color:var(--error,#e53e3e);font-size:12px;"></span>
            </div>
            <div class="form-group">
                <label class="form-label">Event End Date & Time *</label>
                <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control">
                <span id="err-end_datetime" style="color:var(--error,#e53e3e);font-size:12px;"></span>
            </div>
        </div>

        <hr class="section-divider">
        <div class="card-title" style="margin-bottom:12px;">Venue</div>
        <div class="radio-tabs mb-2">
            <div class="radio-tab">
                <input type="radio" name="venue_type" id="venue_platform" value="platform">
                <label for="venue_platform">🏟️ Platform Venue (from approved booking)</label>
            </div>
            <div class="radio-tab">
                <input type="radio" name="venue_type" id="venue_custom" value="custom" checked>
                <label for="venue_custom">📍 Custom Address</label>
            </div>
        </div>

        <div id="venue-platform-section" style="display:none;">
            <div class="form-group mb-2">
                <label class="form-label">Select Approved Venue Booking</label>
                <select name="venue_id" class="form-control">
                    <option value="">— Select —</option>
                    <?php foreach($approvedVenues as $av): ?>
                    <option value="<?= $av['venue_real_id'] ?>"><?= htmlspecialchars($av['venue_name']) ?> (<?= htmlspecialchars($av['event_title_preview']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <?php if (!$approvedVenues): ?>
                <span class="form-hint text-error">No approved venue bookings. <a href="index.php?page=venues">Request a venue first.</a></span>
                <?php endif; ?>
            </div>
        </div>

        <div id="venue-custom-section">
            <div class="form-group mb-2">
                <label class="form-label">Venue / Address *</label>
                <input type="text" name="venue_name_override" class="form-control" placeholder="e.g. Sky Lounge, Level 12, Gulshan-2, Dhaka">
            </div>
        </div>

        <hr class="section-divider">
        <div class="form-group mb-2">
            <label class="form-label">Save As</label>
            <div class="radio-tabs">
                <div class="radio-tab">
                    <input type="radio" name="status" id="status_draft" value="draft" checked>
                    <label for="status_draft">💾 Save as Draft</label>
                </div>
                <div class="radio-tab">
                    <input type="radio" name="status" id="status_publish" value="published">
                    <label for="status_publish">▶ Publish Now</label>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn btn-primary">Create Event</button>
            <a href="index.php?page=events" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</div>

<?php require 'views/layout/footer.php'; ?>
