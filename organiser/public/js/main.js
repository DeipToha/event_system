// EventPro Organiser — Main JS

// ========== Check-in AJAX ==========
function initCheckin() {
    const form = document.getElementById('checkin-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const code     = document.getElementById('ticket_code').value.trim();
        const eventId  = document.getElementById('event_id').value;
        const btn      = document.getElementById('checkin-btn');
        const result   = document.getElementById('checkin-result');

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
        xhr.send(`ticket_code=${encodeURIComponent(code)}&event_id=${encodeURIComponent(eventId)}`);
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

// ========== Confirm dialogs =======
document.addEventListener('click', function(e) {
    if (e.target.matches('[data-confirm]')) {
        if (!confirm(e.target.dataset.confirm)) { e.preventDefault(); }
    }
});

// ========== Auto-dismiss alerts ============
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(a => { a.style.opacity = '0'; a.style.transition = 'opacity 0.5s'; setTimeout(() => a.remove(), 500); });
}, 4000);

// Init
document.addEventListener('DOMContentLoaded', function() {
    initCheckin();
    initVenueTypeToggle();
});
