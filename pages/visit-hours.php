<?php if (!defined('HOSPITAL_CALL_SYSTEM')) exit; ?>
<!-- ================================================================== -->
<!--  v3.1 — Visit Hours Configuration Page                              -->
<!--  Allows operator to:                                                -->
<!--   • Enable/disable automatic visit announcements                     -->
<!--   • Set visit start time (e.g. 16:00 / 4 PM)                         -->
<!--   • Set visit end time (e.g. 20:00 / 8 PM)                          -->
<!--   • Customize announcement messages (Arabic + English)               -->
<!--   • Preview each announcement                                        -->
<!-- ================================================================== -->
<div class="tab-content" id="tab-visit-hours">
    <div class="page-head">
        <h1><i class="fas fa-door-open me-2"></i><span data-i18n="vh.title">Visiting Hours</span></h1>
        <p data-i18n="vh.sub">Automatic visit announcements — start, end, and 10-minute warning</p>
        <div class="vh-status-badge inactive" id="vhStatusBadge" style="margin-top:8px;display:inline-block;padding:6px 14px;border-radius:50px;font-weight:700;font-size:.85rem;">
            <i class="fas fa-circle-notch fa-spin me-1"></i> Loading…
        </div>
    </div>

    <div class="row g-4">
        <!-- LEFT: Configuration -->
        <div class="col-lg-7">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-icon emerald"><i class="fas fa-cog"></i></div>
                    <div>
                        <h3 data-i18n="vh.config_title">Visit Hours Configuration</h3>
                        <p data-i18n="vh.config_sub">All times are in 24-hour format. Announcements fire automatically.</p>
                    </div>
                </div>
                <div class="panel-body">

                    <div class="panel-field">
                        <label class="panel-label" style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="checkbox" id="vhEnabled" style="width:20px;height:20px;cursor:pointer;">
                            <span data-i18n="vh.enable">Enable Visit Hours Announcements</span>
                        </label>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="panel-label" for="vhStart">
                                <i class="fas fa-play me-1 text-success"></i>
                                <span data-i18n="vh.start_time">Visit Start Time</span>
                            </label>
                            <input type="time" class="panel-input" id="vhStart" value="16:00">
                        </div>
                        <div class="col-6">
                            <label class="panel-label" for="vhEnd">
                                <i class="fas fa-stop me-1 text-danger"></i>
                                <span data-i18n="vh.end_time">Visit End Time</span>
                            </label>
                            <input type="time" class="panel-input" id="vhEnd" value="20:00">
                        </div>
                    </div>

                    <div class="panel-alert" style="background:#f0f7f4;border:1px solid #d0e6f0;border-left:4px solid #38C98A;border-radius:10px;padding:12px 14px;margin-top:14px;font-size:.85rem;color:#0f5132;">
                        <i class="fas fa-info-circle me-1"></i>
                        <span data-i18n="vh.warning_note">A 10-minute warning announcement will fire automatically before visit end.</span>
                    </div>

                    <button class="btn-call emerald mt-3" onclick="saveVisitHours()">
                        <i class="fas fa-save me-1"></i>
                        <span data-i18n="vh.save">Save Visit Hours</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT: Quick info card -->
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-bullhorn me-2"></i><span data-i18n="vh.announcements">Announcements</span></h2>
                </div>
                <div class="card-body">
                    <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:14px;" data-i18n="vh.announcements_desc">
                        Three announcements fire automatically at the configured times.
                    </p>
                    <div class="vh-event-list">
                        <div class="vh-event-item">
                            <div class="vh-event-icon" style="background:#059669;color:#fff;"><i class="fas fa-door-open"></i></div>
                            <div class="vh-event-info">
                                <strong data-i18n="vh.event_start">Visit Start</strong>
                                <span data-i18n="vh.event_start_desc">Fires at the configured start time</span>
                            </div>
                        </div>
                        <div class="vh-event-item">
                            <div class="vh-event-icon" style="background:#d97706;color:#fff;"><i class="fas fa-hourglass-half"></i></div>
                            <div class="vh-event-info">
                                <strong data-i18n="vh.event_warn">10-Minute Warning</strong>
                                <span data-i18n="vh.event_warn_desc">Fires 10 minutes before visit end</span>
                            </div>
                        </div>
                        <div class="vh-event-item">
                            <div class="vh-event-icon" style="background:#dc2626;color:#fff;"><i class="fas fa-door-closed"></i></div>
                            <div class="vh-event-info">
                                <strong data-i18n="vh.event_end">Visit End</strong>
                                <span data-i18n="vh.event_end_desc">Fires at the configured end time</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcement message editor -->
    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-icon purple"><i class="fas fa-pen-fancy"></i></div>
                    <div>
                        <h3 data-i18n="vh.messages_title">Announcement Messages</h3>
                        <p data-i18n="vh.messages_sub">Customize the spoken text for each announcement. Arabic is the primary language; English is optional fallback.</p>
                    </div>
                </div>
                <div class="panel-body">

                    <!-- START announcement -->
                    <div class="vh-msg-block">
                        <div class="vh-msg-head">
                            <span class="vh-msg-badge" style="background:#059669;color:#fff;">
                                <i class="fas fa-door-open me-1"></i>
                                <span data-i18n="vh.start_label">Visit Start</span>
                            </span>
                            <button class="btn-preview" onclick="previewVisitMsg('Start')">
                                <i class="fas fa-play me-1"></i><span data-i18n="vh.preview">Preview</span>
                            </button>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="panel-label">الرسالة (عربي)</label>
                                <textarea class="panel-textarea" id="vhMsgStartAr" rows="2" dir="rtl">بدأت ساعات الزيارة. يرجى من الزوار التوجه إلى الأقسام المخصصة.</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="panel-label">Message (EN)</label>
                                <textarea class="panel-textarea" id="vhMsgStartEn" rows="2">Visiting hours have begun. Visitors may proceed to the designated wards.</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- WARNING announcement (10 min before end) -->
                    <div class="vh-msg-block">
                        <div class="vh-msg-head">
                            <span class="vh-msg-badge" style="background:#d97706;color:#fff;">
                                <i class="fas fa-hourglass-half me-1"></i>
                                <span data-i18n="vh.warn_label">10-Minute Warning</span>
                            </span>
                            <button class="btn-preview" onclick="previewVisitMsg('Warn')">
                                <i class="fas fa-play me-1"></i><span data-i18n="vh.preview">Preview</span>
                            </button>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="panel-label">الرسالة (عربي)</label>
                                <textarea class="panel-textarea" id="vhMsgWarnAr" rows="2" dir="rtl">تنتهي ساعات الزيارة خلال 10 دقائق. يرجى من الزوار الاستعداد للمغادرة.</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="panel-label">Message (EN)</label>
                                <textarea class="panel-textarea" id="vhMsgWarnEn" rows="2">Visiting hours will end in 10 minutes. Visitors are kindly requested to prepare to leave.</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- END announcement -->
                    <div class="vh-msg-block">
                        <div class="vh-msg-head">
                            <span class="vh-msg-badge" style="background:#dc2626;color:#fff;">
                                <i class="fas fa-door-closed me-1"></i>
                                <span data-i18n="vh.end_label">Visit End</span>
                            </span>
                            <button class="btn-preview" onclick="previewVisitMsg('End')">
                                <i class="fas fa-play me-1"></i><span data-i18n="vh.preview">Preview</span>
                            </button>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="panel-label">الرسالة (عربي)</label>
                                <textarea class="panel-textarea" id="vhMsgEndAr" rows="2" dir="rtl">انتهت ساعات الزيارة. يرجى من الزوار مغادرة المستشفى. شاكرين لكم تفهمكم.</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="panel-label">Message (EN)</label>
                                <textarea class="panel-textarea" id="vhMsgEndEn" rows="2">Visiting hours have ended. Visitors are kindly requested to leave the hospital. Thank you.</textarea>
                            </div>
                        </div>
                    </div>

                    <button class="btn-call purple mt-3" onclick="saveVisitHours()">
                        <i class="fas fa-save me-1"></i>
                        <span data-i18n="vh.save_all">Save All Settings</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.vh-status-badge.active    { background:#dcfce7; color:#166534; border:1px solid #86efac; }
.vh-status-badge.inactive { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }
.vh-event-list { display:flex; flex-direction:column; gap:12px; }
.vh-event-item { display:flex; align-items:center; gap:12px; padding:10px; background:var(--card-bg,#fff); border:1px solid var(--border-light,#eee); border-radius:10px; }
.vh-event-icon { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.95rem; }
.vh-event-info { display:flex; flex-direction:column; }
.vh-event-info strong { font-size:.9rem; }
.vh-event-info span   { font-size:.75rem; color:var(--text-muted,#999); }
.vh-msg-block { margin-bottom:18px; padding:14px; background:var(--card-bg,#f8f9fc); border:1px solid var(--border-light,#e5e7eb); border-radius:12px; }
.vh-msg-head  { display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; }
.vh-msg-badge { padding:4px 12px; border-radius:50px; font-size:.75rem; font-weight:700; }
</style>
