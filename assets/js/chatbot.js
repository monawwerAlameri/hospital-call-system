// ============================================================
//  HOSPITAL CALL SYSTEM — Support Chatbot v1.0
//  Smart keyword-based help assistant
//  Bilingual: English + Arabic
//  King Khalid Hospital, Hail
// ============================================================

const ChatBot = (() => {

    let isOpen = false;
    let typingTimer = null;
    const HISTORY_KEY = 'hcs_chat_history';

    // ============================================================
    //  KNOWLEDGE BASE
    // ============================================================
    const KB = [
        {
            id: 'welcome',
            keywords: [],
            trigger: 'auto',
            en: {
                title: 'Welcome! 👋',
                text: 'I\'m <strong>Khalid</strong>, the KKH Call System assistant. I can help you with:<br><br>• Emergency code activation<br>• Calling doctors & staff<br>• Custom announcements<br>• Managing departments & staff<br>• Audio settings<br>• Scheduled announcements<br><br>What would you like to know?'
            },
            ar: {
                title: 'أهلاً بك! 👋',
                text: 'أنا <strong>خالد</strong>، مساعد نظام نداءات مستشفى الملك خالد. أستطيع مساعدتك في:<br><br>• تفعيل رموز الطوارئ<br>• نداء الأطباء والكوادر<br>• الإعلانات المخصصة<br>• إدارة الأقسام والكوادر<br>• إعدادات الصوت<br>• الإعلانات المجدولة<br><br>ماذا تريد أن تعرف؟'
            },
            buttons: [
                { en: '🚨 Emergency Codes', ar: '🚨 رموز الطوارئ', target: 'emergency_codes' },
                { en: '👨‍⚕️ Call Doctor', ar: '👨‍⚕️ نداء طبيب', target: 'call_doctor' },
                { en: '📣 Announcements', ar: '📣 الإعلانات', target: 'announcements' },
                { en: '🆘 Quick SOS', ar: '🆘 استجابة سريعة', target: 'sos_wall' },
                { en: '📺 TV Board', ar: '📺 شاشة العرض', target: 'tv_board' },
                { en: '🔊 Audio Setup', ar: '🔊 إعداد الصوت', target: 'audio_setup' },
            ]
        },
        {
            id: 'emergency_codes',
            keywords: ['emergency', 'code', 'blue', 'red', 'fire', 'pink', 'black', 'طارئ', 'كود', 'حريق', 'نداء طوارئ'],
            en: {
                title: '🚨 Emergency Codes',
                text: 'To activate an emergency code:<br><br><strong>1.</strong> Go to <em>Emergency Codes</em> tab from the sidebar<br><strong>2.</strong> OR use the <em>Quick Emergency Codes</em> on the home dashboard<br><strong>3.</strong> Click the code button (Code Blue, Code Red, etc.)<br><strong>4.</strong> The system will automatically:<br>  ✅ Play emergency beeps<br>  ✅ Play the Ding-Dong chime<br>  ✅ Broadcast the announcement in English & Arabic<br>  ✅ Log the event<br><br><strong>Available Codes:</strong><br>🔵 Code Blue — Cardiac Arrest<br>🔴 Code Red — Fire<br>⚪ Code White — Violent Person<br>🩷 Code Pink — Infant Abduction<br>⚫ Code Black — Bomb Threat<br>🟡 Code Yellow — Missing Patient<br>🟣 RRT Team — Rapid Response'
            },
            ar: {
                title: '🚨 رموز الطوارئ',
                text: 'لتفعيل رمز طوارئ:<br><br><strong>١.</strong> انتقل إلى تبويب <em>رموز الطوارئ</em> من القائمة الجانبية<br><strong>٢.</strong> أو استخدم <em>رموز الطوارئ السريعة</em> في لوحة التحكم الرئيسية<br><strong>٣.</strong> اضغط على زر الرمز (Code Blue، Code Red، إلخ)<br><strong>٤.</strong> سيقوم النظام تلقائياً بـ:<br>  ✅ تشغيل نبضات الطوارئ<br>  ✅ تشغيل نغمة الدينج-دونج<br>  ✅ بث الإعلان بالإنجليزية والعربية<br>  ✅ تسجيل الحدث<br><br><strong>الرموز المتاحة:</strong><br>🔵 كود أزرق — توقف القلب<br>🔴 كود أحمر — حريق<br>⚪ كود أبيض — شخص عنيف<br>🩷 كود وردي — اختطاف رضيع<br>⚫ كود أسود — تهديد بقنبلة<br>🟡 كود أصفر — مريض مفقود<br>🟣 فريق RRT — استجابة سريعة'
            },
            buttons: [
                { en: '➕ Add Custom Code', ar: '➕ إضافة رمز مخصص', action: "showTab('manage-codes')" },
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'call_doctor',
            keywords: ['doctor', 'physician', 'specialist', 'call doctor', 'page doctor', 'طبيب', 'استدعاء طبيب', 'نداء طبيب', 'دكتور'],
            en: {
                title: '👨‍⚕️ Calling a Doctor',
                text: '<strong>Method 1 — By Specialty (On-Call):</strong><br>1. Go to <em>Call Doctor</em> tab<br>2. Select <strong>Specialty</strong> (e.g., Cardiology)<br>3. Select <strong>Level</strong> (Consultant, Resident…)<br>4. Enter the <strong>Extension</strong> number<br>5. Choose voice: Male or Female<br>6. Click <em>Call Now</em><br><br><strong>Method 2 — By Name:</strong><br>1. Go to <em>Call Doctor</em> tab<br>2. Use the <em>"Call By Name"</em> panel<br>3. Select the doctor from the dropdown<br>4. Click <em>Call Now</em><br><br>💡 <em>First add doctors in Manage → Doctors to use Method 2</em>'
            },
            ar: {
                title: '👨‍⚕️ نداء الطبيب',
                text: '<strong>الطريقة الأولى — حسب التخصص (المناوب):</strong><br>١. انتقل إلى تبويب <em>نداء طبيب</em><br>٢. اختر <strong>التخصص</strong> (مثلاً: أمراض القلب)<br>٣. اختر <strong>الدرجة</strong> (استشاري، مقيم…)<br>٤. أدخل رقم <strong>الداخلي</strong><br>٥. اختر الصوت: ذكر أو أنثى<br>٦. اضغط <em>نادِ الآن</em><br><br><strong>الطريقة الثانية — بالاسم:</strong><br>١. انتقل إلى تبويب <em>نداء طبيب</em><br>٢. استخدم لوحة <em>"نداء بالاسم"</em><br>٣. اختر الطبيب من القائمة<br>٤. اضغط <em>نادِ الآن</em><br><br>💡 <em>أضف الأطباء أولاً في الإدارة → الأطباء لاستخدام الطريقة الثانية</em>'
            },
            buttons: [
                { en: '👥 Call Staff', ar: '👥 نداء كادر', target: 'call_staff' },
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'call_staff',
            keywords: ['staff', 'nurse', 'security', 'technician', 'personnel', 'كادر', 'ممرض', 'أمن', 'فني', 'موظف'],
            en: {
                title: '👥 Calling Staff',
                text: 'To page hospital staff or admin:<br><br>1. Go to <em>Call Staff</em> tab from the sidebar<br>2. Select the <strong>Staff Role</strong> (Security Supervisor, Head Nurse…)<br>3. Enter the <strong>Extension</strong> number<br>4. Select <strong>Report To Location</strong><br>5. Choose voice: Male or Female<br>6. Click <em>Call Staff Now</em><br><br>The announcement will be:<br><em>"Attention… [Role]… please report to [Location]… extension [EXT]"</em><br><br>💡 You can also use the <strong>Call Board</strong> tab to see all paging options together.'
            },
            ar: {
                title: '👥 نداء الكادر',
                text: 'لاستدعاء كوادر المستشفى أو الإداريين:<br><br>١. انتقل إلى تبويب <em>نداء كادر</em> من القائمة الجانبية<br>٢. اختر <strong>الوظيفة</strong> (مشرف الأمن، رئيسة التمريض…)<br>٣. أدخل رقم <strong>الداخلي</strong><br>٤. اختر <strong>مكان التوجه</strong><br>٥. اختر الصوت: ذكر أو أنثى<br>٦. اضغط <em>نادِ الكادر الآن</em><br><br>سيكون الإعلان:<br><em>"انتبه… [الوظيفة]… يرجى التوجه إلى [الموقع]… الداخلي [الرقم]"</em><br><br>💡 استخدم تبويب <strong>لوحة النداءات</strong> لرؤية جميع خيارات النداء معاً.'
            },
            buttons: [
                { en: '📣 Announcements', ar: '📣 الإعلانات', target: 'announcements' },
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'announcements',
            keywords: ['announcement', 'broadcast', 'custom', 'message', 'إعلان', 'بث', 'رسالة', 'مخصص'],
            en: {
                title: '📣 Custom Announcements',
                text: '<strong>Custom Announcement:</strong><br>1. Go to <em>Custom Announcement</em> tab<br>2. Type your message in the text box<br>3. Use <em>Templates</em> for quick fills<br>4. Choose Male or Female voice<br>5. Click <em>Broadcast Now</em><br><br><strong>Scheduled Announcements:</strong><br>1. Go to <em>Scheduled</em> tab<br>2. Enter title and message<br>3. Select target role/doctor/location<br>4. Pick date & time<br>5. Choose repeat (once/daily/weekly)<br>6. Click <em>Save Announcement</em><br><br>💡 Scheduled announcements fire automatically at the set time!'
            },
            ar: {
                title: '📣 الإعلانات المخصصة',
                text: '<strong>إعلان مخصص:</strong><br>١. انتقل إلى تبويب <em>إعلان مخصص</em><br>٢. اكتب رسالتك في مربع النص<br>٣. استخدم <em>القوالب</em> للتعبئة السريعة<br>٤. اختر صوت ذكر أو أنثى<br>٥. اضغط <em>بث الآن</em><br><br><strong>الإعلانات المجدولة:</strong><br>١. انتقل إلى تبويب <em>المجدولة</em><br>٢. أدخل العنوان والرسالة<br>٣. اختر الدور المستهدف/الطبيب/الموقع<br>٤. حدد التاريخ والوقت<br>٥. اختر التكرار (مرة/يومي/أسبوعي)<br>٦. اضغط <em>حفظ الإعلان</em><br><br>💡 يتم تشغيل الإعلانات المجدولة تلقائياً في الوقت المحدد!'
            },
            buttons: [
                { en: '🔊 Audio Setup', ar: '🔊 إعداد الصوت', target: 'audio_setup' },
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'audio_setup',
            keywords: ['audio', 'voice', 'speed', 'slow', 'fast', 'chime', 'rate', 'pitch', 'صوت', 'سرعة', 'نغمة', 'إعداد الصوت', 'بطيء'],
            en: {
                title: '🔊 Audio Control',
                text: 'The <strong>Audio Control Panel</strong> lets you fine-tune the announcement voice:<br><br><strong>Key Settings:</strong><br>🎚️ <strong>Speech Rate</strong>: 0.62 = airport pace (recommended)<br>⏸️ <strong>Pause Between Phrases</strong>: 700ms default<br>🎵 <strong>Male Pitch</strong>: Lower = deeper voice<br>🎵 <strong>Female Pitch</strong>: Higher = clearer voice<br><br><strong>To configure:</strong><br>1. Go to <em>Audio Control</em> tab<br>2. Adjust the sliders<br>3. Click <em>Test Male/Female</em> to hear the result<br>4. Click <em>Save Settings to Database</em> to persist them<br><br>💡 The system also supports <strong>Arabic TTS</strong> — if Arabic text is provided in a code, it will be read after the English!'
            },
            ar: {
                title: '🔊 التحكم بالصوت',
                text: 'تتيح لك <strong>لوحة التحكم بالصوت</strong> ضبط صوت الإعلانات:<br><br><strong>الإعدادات الرئيسية:</strong><br>🎚️ <strong>سرعة الكلام</strong>: 0.62 = وتيرة المطار (مستحسن)<br>⏸️ <strong>التوقف بين الجمل</strong>: 700 ملي ثانية افتراضياً<br>🎵 <strong>نبرة الذكر</strong>: أقل = صوت أعمق<br>🎵 <strong>نبرة الأنثى</strong>: أعلى = صوت أوضح<br><br><strong>للضبط:</strong><br>١. انتقل إلى تبويب <em>التحكم بالصوت</em><br>٢. اضبط المؤشرات<br>٣. اضغط <em>اختبار ذكر/أنثى</em> للاستماع<br>٤. اضغط <em>حفظ الإعدادات</em> لحفظها<br><br>💡 يدعم النظام <strong>TTS العربي</strong> — إذا تم توفير نص عربي في الرمز، سيتم قراءته بعد الإنجليزي!'
            },
            buttons: [
                { en: '➕ Add Staff', ar: '➕ إضافة كادر', action: "showTab('manage-doctors')" },
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'manage_staff',
            keywords: ['add doctor', 'manage', 'staff management', 'إضافة طبيب', 'إدارة الكوادر', 'إضافة كادر'],
            en: {
                title: '🏥 Managing Staff',
                text: 'To add and manage hospital staff:<br><br>1. Click <em>Doctors</em> under the <em>Manage</em> section in the sidebar<br>2. Click <strong>"Add Staff Member"</strong><br>3. Fill in: Name (EN & AR), Staff Type, Gender, Specialty, Level, Department, Extension<br>4. Click <em>Save Staff</em><br><br><strong>Staff Types:</strong><br>👨‍⚕️ Doctor • 👩‍⚕️ Nurse • 🔬 Technician • 🚑 Paramedic • 🗂️ Admin<br><br><strong>Actions on each card:</strong><br>👁️ View Details &nbsp;|&nbsp; 📞 Page Now &nbsp;|&nbsp; ✏️ Edit &nbsp;|&nbsp; 🗑️ Delete'
            },
            ar: {
                title: '🏥 إدارة الكوادر',
                text: 'لإضافة وإدارة كوادر المستشفى:<br><br>١. اضغط على <em>الأطباء والكوادر</em> تحت قسم <em>الإدارة</em> في القائمة الجانبية<br>٢. اضغط <strong>"إضافة كادر"</strong><br>٣. أدخل: الاسم (عربي وإنجليزي)، نوع الكادر، الجنس، التخصص، الدرجة، القسم، الداخلي<br>٤. اضغط <em>حفظ الكادر</em><br><br><strong>أنواع الكوادر:</strong><br>👨‍⚕️ طبيب • 👩‍⚕️ ممرض • 🔬 فني • 🚑 مسعف • 🗂️ إداري<br><br><strong>الإجراءات على كل بطاقة:</strong><br>👁️ عرض &nbsp;|&nbsp; 📞 نداء &nbsp;|&nbsp; ✏️ تعديل &nbsp;|&nbsp; 🗑️ حذف'
            },
            buttons: [
                { en: '🏢 Departments', ar: '🏢 الأقسام', target: 'manage_depts' },
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'manage_depts',
            keywords: ['department', 'location', 'ward', 'icu', 'er', 'قسم', 'موقع', 'جناح', 'إضافة قسم'],
            en: {
                title: '🏢 Managing Departments',
                text: 'To add or manage hospital departments:<br><br>1. Click <em>Departments</em> under the <em>Manage</em> section<br>2. Click <strong>"Add Department"</strong><br>3. Fill in: Department Name (EN & AR), Code (e.g., ICU), Floor, Category<br>4. Click <em>Add Department</em><br><br>Departments will appear in all location dropdowns throughout the system.<br><br><strong>Tip:</strong> Use short codes (max 5 chars) like ER, ICU, NICU for the department code.'
            },
            ar: {
                title: '🏢 إدارة الأقسام',
                text: 'لإضافة أقسام المستشفى أو إدارتها:<br><br>١. اضغط على <em>الأقسام</em> تحت قسم <em>الإدارة</em><br>٢. اضغط <strong>"إضافة قسم"</strong><br>٣. أدخل: اسم القسم (عربي وإنجليزي)، الرمز (مثل ICU)، الطابق، الفئة<br>٤. اضغط <em>إضافة قسم</em><br><br>ستظهر الأقسام في جميع قوائم المواقع في النظام.<br><br><strong>نصيحة:</strong> استخدم رموزاً قصيرة (5 أحرف كحد أقصى) مثل ER, ICU, NICU.'
            },
            buttons: [
                { en: '🛡️ Custom Codes', ar: '🛡️ الرموز المخصصة', target: 'custom_codes' },
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'custom_codes',
            keywords: ['custom code', 'add code', 'رمز مخصص', 'إضافة رمز'],
            en: {
                title: '🛡️ Custom Emergency Codes',
                text: 'Create your own emergency codes:<br><br>1. Click <em>Custom Codes</em> under the <em>Manage</em> section<br>2. Click <strong>"Add Custom Code"</strong><br>3. Fill in:<br>  📛 Code Name<br>  📝 Description<br>  🎨 Background & Text Color<br>  🔷 Icon (FontAwesome class)<br>  ⚡ Priority (critical/high/normal)<br>  🔊 English Message (use {loc} for location)<br>  🔊 Arabic Message (use {loc_ar})<br>  📋 Action Note<br>4. Click <em>Save</em><br><br>Custom codes appear in all code grids.'
            },
            ar: {
                title: '🛡️ الرموز المخصصة',
                text: 'إنشاء رموز طوارئ خاصة بك:<br><br>١. اضغط على <em>الرموز المخصصة</em> تحت قسم <em>الإدارة</em><br>٢. اضغط <strong>"إضافة رمز مخصص"</strong><br>٣. أدخل:<br>  📛 اسم الرمز<br>  📝 الوصف<br>  🎨 لون الخلفية والنص<br>  🔷 الأيقونة (FontAwesome)<br>  ⚡ الأولوية (حرجة/عالية/عادية)<br>  🔊 الرسالة الإنجليزية (استخدم {loc} للموقع)<br>  🔊 الرسالة العربية (استخدم {loc_ar})<br>  📋 ملاحظة الإجراء<br>٤. اضغط <em>حفظ</em><br><br>تظهر الرموز المخصصة في جميع شبكات الرموز.'
            },
            buttons: [
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'sos_wall',
            keywords: ['sos', 'quick sos', 'one tap', 'panic', 'rapid', 'wall', 'استجابة', 'طارئ سريع', 'لوحة sos'],
            en: {
                title: '🆘 Quick SOS Wall',
                text: 'The <strong>Quick SOS Wall</strong> lets you broadcast emergency codes with a single tap — optimized for crisis situations where speed matters.<br><br><strong>How to use:</strong><br>1. Go to <em>Quick SOS</em> in the sidebar under Smart Features<br>2. Tap any large colored button:<br>  🔵 Code Blue — Cardiac Arrest<br>  🔴 Code Red — Fire<br>  🩷 Code Pink — Infant<br>  ⚫ Code Black — Bomb<br>  🟠 Code Orange — Mass Casualty<br>  🟡 Code Yellow — Missing Patient<br>  ⚪ Code Silver — Armed Person<br>  🌀 Code White — Infrastructure<br><br>Each tap immediately broadcasts the announcement + plays chimes + logs the event.'
            },
            ar: {
                title: '🆘 لوحة الاستجابة السريعة',
                text: 'تتيح <strong>لوحة الاستجابة السريعة</strong> بث رموز الطوارئ بلمسة واحدة — مُحسَّنة للأزمات التي تتطلب السرعة القصوى.<br><br><strong>طريقة الاستخدام:</strong><br>١. انتقل إلى <em>استجابة طوارئ</em> في القائمة الجانبية تحت "مزايا ذكية"<br>٢. اضغط أي زر ملون كبير:<br>  🔵 كود أزرق — توقف القلب<br>  🔴 كود أحمر — حريق<br>  🩷 كود وردي — رضيع<br>  ⚫ كود أسود — قنبلة<br>  🟠 كود برتقالي — إصابات جماعية<br>  🟡 كود أصفر — مريض مفقود<br>  ⚪ كود فضي — شخص مسلح<br>  🌀 كود أبيض — بنية تحتية<br><br>يبث كل ضغطة الإعلان فوراً + النغمات + التسجيل.'
            },
            buttons: [
                { en: '🚨 Emergency Codes', ar: '🚨 رموز الطوارئ', target: 'emergency_codes' },
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'tv_board',
            keywords: ['tv', 'television', 'board', 'screen', 'display', 'ticker', 'monitor', 'شاشة', 'تلفاز', 'عرض', 'لوحة'],
            en: {
                title: '📺 TV Display Board',
                text: 'The <strong>TV Display Board</strong> lets you send messages to screens throughout the hospital.<br><br><strong>How to use:</strong><br>1. Go to <em>TV Board</em> in the sidebar<br>2. Type your message in English and/or Arabic<br>3. Choose display duration (30s to 10 min, or until dismissed)<br>4. Set priority (Normal / High / Urgent)<br>5. Click <em>Send to Board</em><br><br>The preview panel shows exactly what will appear on screens.<br><br>💡 High priority messages appear in yellow, Urgent in red.'
            },
            ar: {
                title: '📺 لوحة العرض التلفزيوني',
                text: 'تتيح <strong>لوحة العرض التلفزيوني</strong> إرسال رسائل لشاشات المستشفى.<br><br><strong>طريقة الاستخدام:</strong><br>١. انتقل إلى <em>شاشة العرض</em> في القائمة الجانبية<br>٢. اكتب رسالتك بالإنجليزية و/أو العربية<br>٣. اختر مدة العرض (٣٠ ثانية إلى ١٠ دقائق أو حتى الإغلاق)<br>٤. حدد الأولوية (عادي/مرتفع/عاجل)<br>٥. اضغط <em>إرسال للشاشة</em><br><br>تُظهر لوحة المعاينة ما سيظهر على الشاشات بدقة.<br><br>💡 الرسائل عالية الأولوية باللون الأصفر، والعاجلة باللون الأحمر.'
            },
            buttons: [
                { en: '🌙 Quiet Hours', ar: '🌙 ساعات الهدوء', target: 'quiet_hours' },
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'handover',
            keywords: ['handover', 'hand over', 'shift', 'handoff', 'hand-off', 'incoming', 'outgoing', 'notes', 'shift change', 'تسليم', 'وردية', 'نقل مهام', 'ملاحظات'],
            en: {
                title: '📋 Shift Handover',
                text: 'The <strong>Shift Handover</strong> feature lets you document and broadcast handover notes between shifts.<br><br><strong>How to use:</strong><br>1. Go to <em>Shift Handover</em> in the sidebar<br>2. Select outgoing and incoming shift<br>3. Choose the department<br>4. Enter handover notes (patient updates, pending tasks, alerts)<br>5. Set priority: Routine / Important / Critical<br><br><strong>Then choose:</strong><br>• <em>Save Entry</em> — logs only, no broadcast<br>• <em>Broadcast & Save</em> — logs + plays a handover announcement over speakers<br><br>All entries appear in the Handover Log panel.'
            },
            ar: {
                title: '📋 تسليم الوردية',
                text: 'تتيح ميزة <strong>تسليم الوردية</strong> توثيق وبث ملاحظات التسليم بين الورديات.<br><br><strong>طريقة الاستخدام:</strong><br>١. انتقل إلى <em>تسليم الوردية</em> في القائمة الجانبية<br>٢. اختر الوردية المغادرة والقادمة<br>٣. اختر القسم<br>٤. أدخل ملاحظات التسليم (تحديثات المرضى، المهام، التنبيهات)<br>٥. حدد الأولوية: اعتيادي/مهم/حرج<br><br><strong>ثم اختر:</strong><br>• <em>حفظ الإدخال</em> — تسجيل فقط بدون بث<br>• <em>بث وحفظ</em> — تسجيل + تشغيل إعلان التسليم على مكبرات الصوت<br><br>تظهر جميع الإدخالات في لوحة سجل التسليم.'
            },
            buttons: [
                { en: '🌙 Quiet Hours', ar: '🌙 ساعات الهدوء', target: 'quiet_hours' },
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'quiet_hours',
            keywords: ['quiet', 'silent', 'night', 'restricted', 'sleep', 'hours', 'هدوء', 'صامت', 'ليل', 'مقيد', 'ساعات'],
            en: {
                title: '🌙 Quiet Hours',
                text: 'The <strong>Quiet Hours</strong> mode silences non-critical announcements during night or rest periods.<br><br><strong>Setup:</strong><br>1. Go to <em>Quiet Hours</em> in the sidebar<br>2. Toggle <em>Enable Quiet Hours</em><br>3. Set start and end times (e.g., 22:00 – 06:00)<br>4. Choose which days to repeat<br>5. Click <em>Save Quiet Hours</em><br><br><strong>During Quiet Hours:</strong><br>❌ Regular announcements — muted<br>❌ Staff paging — muted<br>✅ Code Blue — always broadcasts<br>✅ Code Red — always broadcasts<br>✅ Code Pink — always broadcasts<br>✅ Code Black — always broadcasts<br><br>The status card shows if Quiet Hours are currently active.'
            },
            ar: {
                title: '🌙 ساعات الهدوء',
                text: 'يُسكت وضع <strong>ساعات الهدوء</strong> الإعلانات غير الحرجة خلال الليل أو فترات الراحة.<br><br><strong>الإعداد:</strong><br>١. انتقل إلى <em>ساعات الهدوء</em> في القائمة الجانبية<br>٢. فعّل <em>تفعيل ساعات الهدوء</em><br>٣. حدد وقت البدء والانتهاء (مثلاً: ٢٢:٠٠ – ٠٦:٠٠)<br>٤. اختر أيام التكرار<br>٥. اضغط <em>حفظ ساعات الهدوء</em><br><br><strong>خلال ساعات الهدوء:</strong><br>❌ الإعلانات العادية — صامتة<br>❌ نداءات الكوادر — صامتة<br>✅ كود أزرق — يُبث دائماً<br>✅ كود أحمر — يُبث دائماً<br>✅ كود وردي — يُبث دائماً<br>✅ كود أسود — يُبث دائماً<br><br>يُظهر بطاق الحالة ما إذا كانت ساعات الهدوء نشطة حالياً.'
            },
            buttons: [
                { en: '📺 TV Board', ar: '📺 شاشة العرض', target: 'tv_board' },
                { en: '← Back', ar: '← رجوع', target: 'welcome' },
            ]
        },
        {
            id: 'not_found',
            keywords: [],
            en: {
                title: 'Hmm… 🤔',
                text: 'I didn\'t quite catch that. Here are some things I can help with:'
            },
            ar: {
                title: 'لم أفهم 🤔',
                text: 'لم أفهم سؤالك تماماً. إليك بعض المواضيع التي يمكنني المساعدة فيها:'
            },
            buttons: [
                { en: '🚨 Emergency Codes', ar: '🚨 رموز الطوارئ', target: 'emergency_codes' },
                { en: '🆘 Quick SOS', ar: '🆘 استجابة سريعة', target: 'sos_wall' },
                { en: '📺 TV Board', ar: '📺 شاشة العرض', target: 'tv_board' },
                { en: '📋 Shift Handover', ar: '📋 تسليم الوردية', target: 'handover' },
                { en: '🌙 Quiet Hours', ar: '🌙 ساعات الهدوء', target: 'quiet_hours' },
                { en: '🔊 Audio Setup', ar: '🔊 إعداد الصوت', target: 'audio_setup' },
            ]
        }
    ];

    // ============================================================
    //  INIT
    // ============================================================
    function init() {
        const btn = document.getElementById('chatBotBtn');
        const panel = document.getElementById('chatBotPanel');
        if (!btn || !panel) return;

        btn.addEventListener('click', toggleChat);
        document.getElementById('chatCloseBtn')?.addEventListener('click', closeChat);
        document.getElementById('chatInput')?.addEventListener('keydown', e => {
            if (e.key === 'Enter') sendMessage();
        });
        document.getElementById('chatSendBtn')?.addEventListener('click', sendMessage);

        // Welcome message after short delay
        setTimeout(() => addBotMessage('welcome'), 500);
    }

    function toggleChat() {
        isOpen ? closeChat() : openChat();
    }

    function openChat() {
        isOpen = true;
        const panel = document.getElementById('chatBotPanel');
        const btn = document.getElementById('chatBotBtn');
        panel?.classList.add('open');
        btn?.classList.add('chat-open');
        document.getElementById('chatInput')?.focus();
        // Badge remove
        const badge = document.getElementById('chatBadge');
        if (badge) badge.style.display = 'none';
    }

    function closeChat() {
        isOpen = false;
        document.getElementById('chatBotPanel')?.classList.remove('open');
        document.getElementById('chatBotBtn')?.classList.remove('chat-open');
    }

    // ============================================================
    //  MESSAGING
    // ============================================================
    function sendMessage() {
        const input = document.getElementById('chatInput');
        const text = input?.value?.trim();
        if (!text) return;
        input.value = '';
        addUserMessage(text);
        showTyping();
        setTimeout(() => {
            hideTyping();
            const entry = findResponse(text);
            addBotMessage(entry ? entry.id : 'not_found');
        }, 800 + Math.random() * 400);
    }

    function sendQuickReply(target, action) {
        if (action) {
            try { eval(action); } catch (e) { }
            closeChat();
            return;
        }
        showTyping();
        setTimeout(() => {
            hideTyping();
            addBotMessage(target);
        }, 500);
    }

    function findResponse(text) {
        const lower = text.toLowerCase().trim();
        const allKw = KB.filter(e => e.keywords && e.keywords.length > 0);
        let best = null;
        let bestScore = 0;
        for (const entry of allKw) {
            let score = 0;
            for (const kw of entry.keywords) {
                const kwL = kw.toLowerCase();
                if (lower.includes(kwL)) {
                    score += kwL.includes(' ') ? 3 : (kwL.length > 4 ? 2 : 1);
                }
            }
            if (score > bestScore) {
                bestScore = score;
                best = entry;
            }
        }
        return bestScore > 0 ? best : null;
    }

    function addUserMessage(text) {
        const msgs = document.getElementById('chatMessages');
        if (!msgs) return;
        const div = document.createElement('div');
        div.className = 'chat-msg user';
        div.innerHTML = `<div class="chat-bubble user">${escHtml(text)}</div>`;
        msgs.appendChild(div);
        scrollToBottom();
    }

    function addBotMessage(entryId) {
        const lang = (typeof LANG !== 'undefined') ? LANG : 'en';
        const entry = KB.find(e => e.id === entryId) || KB.find(e => e.id === 'not_found');
        if (!entry) return;

        const content = entry[lang] || entry['en'];
        const msgs = document.getElementById('chatMessages');
        if (!msgs) return;

        const div = document.createElement('div');
        div.className = 'chat-msg bot';

        let btnsHtml = '';
        if (entry.buttons && entry.buttons.length) {
            btnsHtml = '<div class="chat-quick-btns">' +
                entry.buttons.map(b => {
                    const label = lang === 'ar' ? b.ar : b.en;
                    if (b.action) {
                        return `<button class="chat-qbtn" onclick="ChatBot.sendQuickReply(null,'${b.action}')">${label}</button>`;
                    }
                    return `<button class="chat-qbtn" onclick="ChatBot.sendQuickReply('${b.target}',null)">${label}</button>`;
                }).join('') + '</div>';
        }

        div.innerHTML = `
            <div class="chat-avatar-bot"><i class="fas fa-robot"></i></div>
            <div class="chat-bubble bot">
                ${content.title ? `<div class="chat-bubble-title">${content.title}</div>` : ''}
                <div class="chat-bubble-text">${content.text}</div>
                ${btnsHtml}
            </div>`;

        msgs.appendChild(div);
        scrollToBottom();
    }

    function showTyping() {
        const msgs = document.getElementById('chatMessages');
        if (!msgs || document.getElementById('chatTyping')) return;
        const div = document.createElement('div');
        div.className = 'chat-msg bot';
        div.id = 'chatTyping';
        div.innerHTML = `
            <div class="chat-avatar-bot"><i class="fas fa-robot"></i></div>
            <div class="chat-bubble bot typing">
                <span></span><span></span><span></span>
            </div>`;
        msgs.appendChild(div);
        scrollToBottom();
    }

    function hideTyping() {
        document.getElementById('chatTyping')?.remove();
    }

    function scrollToBottom() {
        const msgs = document.getElementById('chatMessages');
        if (msgs) msgs.scrollTop = msgs.scrollHeight;
    }

    function escHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }

    // ============================================================
    //  PUBLIC
    // ============================================================
    return { init, toggleChat, openChat, closeChat, sendQuickReply };
})();

document.addEventListener('DOMContentLoaded', () => ChatBot.init());


