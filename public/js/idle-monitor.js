let idleTime = 0;
let idleTimer;
let currentLevel = 0;
let currentSessionId = null;
let settings = null;

// Function to show idle alert
function showIdleAlert(message, level) {
    const alertDiv = $('#idle-alert');
    const alertMessage = $('#idle-alert-message');

    // Set alert color based on level
    alertDiv.removeClass('alert-warning alert-danger alert-info');
    if (level === 1) {
        alertDiv.addClass('alert-info');
    } else if (level === 2) {
        alertDiv.addClass('alert-warning');
    } else if (level === 3) {
        alertDiv.addClass('alert-danger');
    }

    // Set message and show alert
    alertMessage.html(message);
    alertDiv.fadeIn().addClass('show');

    // Auto dismiss after 5 seconds for levels 1 and 2
    if (level < 3) {
        setTimeout(() => dismissIdleAlert(), 5000);
    }
}

function dismissIdleAlert() {
    $('#idle-alert').fadeOut().removeClass('show');
}

// Fetch settings from server
function fetchSettings() {
    return $.ajax({
        url: '/settings/get',
        method: 'GET'
    }).then(function (response) {
        settings = response;
        isAdmin = $('.nav-link[href*="admin.users"]').length > 0;

        console.log('Settings loaded:', settings);
        return settings;
    });
}

// Start monitoring after settings are loaded
$(document).ready(function () {
    fetchSettings().then(() => {
        if (parseInt(settings.monitoring_enabled) === 1 && !isAdmin) {
            startMonitoring();
        } else {
            console.log('User activity monitoring is disabled or user is admin');
        }
    });
});

function startMonitoring() {
    const idleInterval = 5000; // 5 seconds
    const timeoutMinutes = parseInt(settings.idle_timeout);

    console.log('Monitoring started with timeout:', timeoutMinutes, 'minute(s) between alerts');

    $(document).on('mousemove keypress click', resetIdleTime);

    let lastTriggerTime = new Date(); // وقت بداية الخمول

    idleTimer = setInterval(function () {
        // احسب الوقت الفعلي المنقضي من آخر نشاط
        let diffMs = new Date() - lastTriggerTime;
        let idleMinutes = Math.floor(diffMs / 60000);

        console.log(`Idle: ${idleMinutes} min | Level: ${currentLevel}`);

        // كل إنذار بعد عدد الدقايق اللي في الداتا بيز
        if (idleMinutes >= timeoutMinutes && currentLevel === 0) {
            currentLevel = 1;
            recordIdle(1);
            lastTriggerTime = new Date(); // نبدأ العد من جديد
        }
        else if (idleMinutes >= timeoutMinutes && currentLevel === 1) {
            currentLevel = 2;
            recordIdle(2);
            lastTriggerTime = new Date(); // نبدأ العد من جديد
        }
        else if (idleMinutes >= timeoutMinutes && currentLevel === 2) {
            currentLevel = 3;
            recordIdle(3);
            lastTriggerTime = new Date(); // نبدأ العد من جديد
        }

        $('#idle-status').html(`
            <div>Idle time since last activity: ${idleMinutes} minute(s)</div>
            <div>Current level: ${currentLevel}</div>
            <div>Next alert in: ${timeoutMinutes - idleMinutes} minute(s)</div>
        `);
    }, idleInterval);
}


function recordIdle(level) {
    $.ajax({
        url: '/idle/record',
        method: 'POST',
        data: {
            level: level,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            currentSessionId = response.session.id;

            if (level === 1) {
                showIdleAlert(`
                    <strong>Idle Warning</strong><br>
                    You have been idle for ${settings.idle_timeout} minute(s).
                `, 1);
            } else if (level === 2) {
                showIdleAlert(`
                    <strong>Extended Inactivity</strong><br>
                    Warning: You have been idle for ${settings.idle_timeout * 2} minutes.
                    Please resume activity to avoid logout.
                `, 2);
            } else if (level === 3) {
                showIdleAlert(`
                    <strong>Session Expired</strong><br>
                    Maximum idle time (${settings.idle_timeout * 4} minutes) reached.
                    Logging out for security...
                `, 3);

                // Delay logout slightly to show the message
                setTimeout(() => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/logout';

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = $('meta[name="csrf-token"]').attr('content');
                    form.appendChild(csrfInput);

                    document.body.appendChild(form);
                    form.submit();
                }, 2000);
            }
        },
        error: function (xhr) {
            console.error('Error recording idle session:', xhr);
        }
    });
}

function resetIdleTime() {
    if (currentLevel > 0) {
        endIdleSession();
        currentLevel = 0;
        currentSessionId = null;
        dismissIdleAlert();
    }
    idleTime = 0;
}

function endIdleSession() {
    if (currentSessionId) {
        $.ajax({
            url: `/idle/${currentSessionId}/end`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Idle session ended:', response);
                $('#idle-status').text('Active');
            },
            error: function (xhr) {
                console.error('Error ending idle session:', xhr);
            }
        });
    }
}
