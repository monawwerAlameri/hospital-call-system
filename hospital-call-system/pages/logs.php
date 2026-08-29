<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<div class="tab-content" id="tab-logs">
    <div class="page-head">
        <h1><i class="fas fa-history me-2"></i><span data-i18n="logs.title">Call Logs</span></h1>
        <p data-i18n="logs.sub">Complete session record of all announcements</p>
    </div>
    <div class="card">
        <div class="card-header">
            <h2 data-i18n="logs.all_records">All Call Records</h2>
            <div class="ms-auto">
                <button onclick="clearLogs()" class="btn-outline-danger">
                    <i class="fas fa-trash me-1"></i><span data-i18n="logs.clear_session">Clear Session</span>
                </button>
            </div>
        </div>
        <div style="overflow-x:auto">
            <table class="data-table responsive-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th data-i18n="logs.col_time">Time</th>
                        <th data-i18n="logs.col_type">Type</th>
                        <th data-i18n="logs.col_announcement">Announcement</th>
                        <th data-i18n="logs.col_location">Location</th>
                        <th data-i18n="logs.col_voice">Voice</th>
                        <th data-i18n="logs.col_status">Status</th>
                    </tr>
                </thead>
                <tbody id="allLogs">
                    <tr><td colspan="7" class="log-empty">No call logs yet</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
