// EventPro Organiser — Main JS

// ========== Check-in AJAX ==========
function initCheckin() {
    const form = document.getElementById('checkin-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const code    = document.getElementById('ticket_code').value.trim();
        const eventId = document.getElementById('event_id').value;
        const btn     = document.getElementById('checkin-btn');

        if (!code) { showCheckinResult(false, 'Please enter a ticket code.'); return; }

        btn.disabled = true;
        btn.textContent = 'Checking...';

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php?page=api_checkin', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            btn.disabled = false;
            btn.textContent = 'Check In';
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    showCheckinResult(true,
                        `✅ Check-in Successful!\n${data.attendee} — ${data.tier} (×${data.quantity})\nTime: ${data.checked_in_at}`
                    );
                    document.getElementById('ticket_code').value = '';
                    updateStats();
                } else {
                    showCheckinResult(false, '❌ ' + data.message);
                }
            } catch(err) {
                showCheckinResult(false, 'Server error. Please try again.');
            }
        };
        xhr.onerror = function() {
            btn.disabled = false;
            btn.textContent = 'Check In';
            showCheckinResult(false, 'Network error. Please check your connection.');
        };
        xhr.send(`ticket_code=${encodeURIComponent(code)}&event_id=${encodeURIComponent(eventId)}&csrf_token=${encodeURIComponent(document.querySelector('[name=csrf_token]')?.value || '')}`);
    });
}

function showCheckinResult(success, message) {
    const el = document.getElementById('checkin-result');
    el.className = success ? 'success' : 'error';
    el.style.whiteSpace = 'pre-line';
    el.textContent = message;
}

function updateStats() {
    const statsEl = document.getElementById('live-stats');
    if (!statsEl) return;
    const eventId = document.getElementById('event_id').value;
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `index.php?page=checkin&action=stats&event_id=${eventId}&ajax=1`, true);
    xhr.onload = function() {
        try {
            const d = JSON.parse(xhr.responseText);
            if (d.total_sold !== undefined) {
                document.getElementById('stat-sold').textContent    = d.total_sold;
                document.getElementById('stat-checked').textContent = d.total_checked;
                document.getElementById('stat-rate').textContent    = d.rate + '%';
            }
        } catch(e) {}
    };
    xhr.send();
}

// ========== Venue type toggle ==========
function initVenueTypeToggle() {
    const platformRadio = document.getElementById('venue_platform');
    const customRadio   = document.getElementById('venue_custom');
    const platformDiv   = document.getElementById('venue-platform-section');
    const customDiv     = document.getElementById('venue-custom-section');

    function toggle() {
        if (platformRadio && platformRadio.checked) {
            platformDiv && (platformDiv.style.display = 'block');
            customDiv   && (customDiv.style.display   = 'none');
        } else {
            platformDiv && (platformDiv.style.display = 'none');
            customDiv   && (customDiv.style.display   = 'block');
        }
    }
    platformRadio && platformRadio.addEventListener('change', toggle);
    customRadio   && customRadio.addEventListener('change', toggle);
    toggle();
}

// ========== Confirm dialogs ==========
document.addEventListener('click', function(e) {
    if (e.target.matches('[data-confirm]')) {
        if (!confirm(e.target.dataset.confirm)) { e.preventDefault(); }
    }
});

// ========== Auto-dismiss alerts ==========
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(a => {
        a.style.opacity = '0';
        a.style.transition = 'opacity 0.5s';
        setTimeout(() => a.remove(), 500);
    });
}, 4000);

// ========== JS Form Validation ==========
function showError(fieldId, message) {
    const el = document.getElementById('err-' + fieldId);
    if (el) el.textContent = message;
}

function clearError(fieldId) {
    const el = document.getElementById('err-' + fieldId);
    if (el) el.textContent = '';
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// Login form validation
function initLoginValidation() {
    const form = document.getElementById('login-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        let valid = true;

        const email    = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!email) {
            showError('email', 'Email is required.'); valid = false;
        } else if (!isValidEmail(email)) {
            showError('email', 'Enter a valid email address.'); valid = false;
        } else {
            clearError('email');
        }

        if (!password) {
            showError('password', 'Password is required.'); valid = false;
        } else if (password.length < 6) {
            showError('password', 'Password must be at least 6 characters.'); valid = false;
        } else {
            clearError('password');
        }

        if (!valid) e.preventDefault();
    });
}

// Register form validation
function initRegisterValidation() {
    const form = document.getElementById('register-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        let valid = true;

        const name     = document.getElementById('name').value.trim();
        const email    = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const org_name = document.getElementById('org_name').value.trim();

        if (!name) {
            showError('name', 'Full name is required.'); valid = false;
        } else { clearError('name'); }

        if (!email) {
            showError('email', 'Email is required.'); valid = false;
        } else if (!isValidEmail(email)) {
            showError('email', 'Enter a valid email address.'); valid = false;
        } else { clearError('email'); }

        if (!password) {
            showError('password', 'Password is required.'); valid = false;
        } else if (password.length < 6) {
            showError('password', 'Password must be at least 6 characters.'); valid = false;
        } else { clearError('password'); }

        if (!org_name) {
            showError('org_name', 'Organisation name is required.'); valid = false;
        } else { clearError('org_name'); }

        if (!valid) e.preventDefault();
    });
}

// Event create/edit form validation
function initEventValidation() {
    const form = document.getElementById('event-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        let valid = true;

        const title    = document.getElementById('title').value.trim();
        const eventDt  = document.getElementById('event_datetime').value;
        const endDt    = document.getElementById('end_datetime').value;

        if (!title) {
            showError('title', 'Event title is required.'); valid = false;
        } else { clearError('title'); }

        if (!eventDt) {
            showError('event_datetime', 'Event start date is required.'); valid = false;
        } else { clearError('event_datetime'); }

        if (!endDt) {
            showError('end_datetime', 'Event end date is required.'); valid = false;
        } else if (endDt <= eventDt) {
            showError('end_datetime', 'End date must be after start date.'); valid = false;
        } else { clearError('end_datetime'); }

        if (!valid) e.preventDefault();
    });
}

// Tier form validation
function initTierValidation() {
    const form = document.getElementById('tier-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        let valid = true;

        const name       = document.getElementById('tier_name').value.trim();
        const price      = document.getElementById('tier_price').value;
        const totalSeats = document.getElementById('tier_seats').value;

        if (!name) {
            showError('tier_name', 'Tier name is required.'); valid = false;
        } else { clearError('tier_name'); }

        if (!price || price < 0) {
            showError('tier_price', 'Valid price is required.'); valid = false;
        } else { clearError('tier_price'); }

        if (!totalSeats || totalSeats < 1) {
            showError('tier_seats', 'At least 1 seat is required.'); valid = false;
        } else { clearError('tier_seats'); }

        if (!valid) e.preventDefault();
    });
}

// Announcement form validation
function initAnnouncementValidation() {
    const form = document.getElementById('announcement-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        let valid = true;

        const title = document.getElementById('ann_title').value.trim();
        const body  = document.getElementById('ann_body').value.trim();

        if (!title) {
            showError('ann_title', 'Title is required.'); valid = false;
        } else { clearError('ann_title'); }

        if (!body) {
            showError('ann_body', 'Message body is required.'); valid = false;
        } else { clearError('ann_body'); }

        if (!valid) e.preventDefault();
    });
}

// Discount form validation
function initDiscountValidation() {
    const form = document.getElementById('discount-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        let valid = true;

        const code     = document.getElementById('disc_code').value.trim();
        const pct      = document.getElementById('disc_pct').value;
        const maxUses  = document.getElementById('disc_max_uses').value;

        if (!code) {
            showError('disc_code', 'Discount code is required.'); valid = false;
        } else { clearError('disc_code'); }

        if (!pct || pct <= 0 || pct > 100) {
            showError('disc_pct', 'Enter a valid percentage (1-100).'); valid = false;
        } else { clearError('disc_pct'); }

        if (!maxUses || maxUses < 1) {
            showError('disc_max_uses', 'Max uses must be at least 1.'); valid = false;
        } else { clearError('disc_max_uses'); }

        if (!valid) e.preventDefault();
    });
}

// ========== Init ==========
document.addEventListener('DOMContentLoaded', function() {
    initCheckin();
    initVenueTypeToggle();
    initLoginValidation();
    initRegisterValidation();
    initEventValidation();
    initTierValidation();
    initAnnouncementValidation();
    initDiscountValidation();
});