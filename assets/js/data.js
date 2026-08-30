// ============================================================
//  HOSPITAL CALL SYSTEM — DATA DEFINITIONS
//  King Khalid Hospital, Hail
// ============================================================

const LOCS = [
  { c: 'ER',  n: 'Emergency Room',         ar: 'قسم الطوارئ' },
  { c: 'ICU', n: 'Intensive Care Unit',    ar: 'وحدة العناية المركزة' },
  { c: 'CCU', n: 'Coronary Care Unit',     ar: 'وحدة عناية القلب' },
  { c: 'NICU',n: 'Neonatal ICU',           ar: 'وحدة عناية حديثي الولادة' },
  { c: 'MMW', n: 'Male Medical Ward',      ar: 'الجناح الطبي الرجالي' },
  { c: 'FMW', n: 'Female Medical Ward',    ar: 'الجناح الطبي النسائي' },
  { c: 'OR',  n: 'Operating Room',         ar: 'غرفة العمليات' },
  { c: 'RAD', n: 'Radiology Department',   ar: 'قسم الأشعة' },
  { c: 'LAB', n: 'Laboratory',             ar: 'المختبر' },
  { c: 'DLY', n: 'Dialysis Unit',          ar: 'وحدة الغسيل الكلوي' },
  { c: 'OPC', n: 'Outpatient Clinics',     ar: 'العيادات الخارجية' },
  { c: 'ADM', n: 'Administration',         ar: 'الإدارة' },
  { c: 'LOB', n: 'Main Lobby',             ar: 'البهو الرئيسي' },
];

const LOCS_MAP = Object.fromEntries(LOCS.map(l => [l.c, l]));

const SPECS = [
  { en: 'Internal Medicine',        ar: 'الطب الباطني',         gender: null },
  { en: 'Cardiology',               ar: 'أمراض القلب',          gender: null },
  { en: 'Neurology',                ar: 'طب الأعصاب',           gender: null },
  { en: 'Neurosurgery',             ar: 'جراحة الأعصاب',        gender: null },
  { en: 'Gastroenterology',         ar: 'طب الجهاز الهضمي',     gender: null },
  { en: 'Endocrinology',            ar: 'الغدد الصماء',         gender: null },
  { en: 'General Surgery',          ar: 'الجراحة العامة',       gender: null },
  { en: 'Orthopedic Surgery',       ar: 'جراحة العظام',         gender: null },
  { en: 'Urology',                  ar: 'طب المسالك البولية',   gender: null },
  { en: 'Pediatrics',               ar: 'طب الأطفال',           gender: null },
  { en: 'Obstetrics and Gynecology',ar: 'النساء والولادة',      gender: 'female' },
  { en: 'Anesthesia',               ar: 'التخدير',              gender: null },
  { en: 'Psychiatry',               ar: 'الطب النفسي',          gender: null },
  { en: 'Dermatology',              ar: 'الجلدية',              gender: null },
  { en: 'Ophthalmology',            ar: 'طب العيون',            gender: null },
  { en: 'ENT',                      ar: 'الأنف والأذن والحنجرة',gender: null },
  { en: 'Oncology',                 ar: 'الأورام',              gender: null },
  { en: 'Pulmonology',              ar: 'أمراض الصدر',          gender: null },
  { en: 'Nephrology',               ar: 'أمراض الكلى',          gender: null },
  { en: 'Hematology',               ar: 'أمراض الدم',           gender: null },
];

const ROLES = [
  { en: 'Hospital Director On Call',  ar: 'مدير المستشفى المناوب',     gender: null },
  { en: 'Administrative Supervisor',  ar: 'المشرف الإداري',            gender: null },
  { en: 'Security Supervisor',        ar: 'مشرف الأمن',               gender: null },
  { en: 'Maintenance Supervisor',     ar: 'مشرف الصيانة',             gender: null },
  { en: 'IT Support',                 ar: 'دعم تقنية المعلومات',       gender: null },
  { en: 'Nursing Supervisor',         ar: 'مشرفة التمريض',            gender: 'female' },
  { en: 'Head Nurse',                 ar: 'رئيسة التمريض',            gender: 'female' },
  { en: 'Laboratory Technician',      ar: 'فني المختبر',              gender: null },
  { en: 'Radiology Technician',       ar: 'فني الأشعة',               gender: null },
  { en: 'Respiratory Therapist',      ar: 'أخصائي العلاج التنفسي',    gender: null },
  { en: 'OR Technician',              ar: 'فني غرفة العمليات',         gender: null },
  { en: 'Dialysis Technician',        ar: 'فني الغسيل الكلوي',        gender: null },
  { en: 'Pharmacist On Call',         ar: 'الصيدلاني المناوب',         gender: null },
  { en: 'Social Worker',              ar: 'الأخصائي الاجتماعي',       gender: null },
];

const CODES = [
  {
    id: 'CODE_BLUE',
    n: 'Code Blue',
    d: 'Cardiac Arrest',
    ar: 'كود أزرق — توقف القلب',
    cl: '#fff', bg: '#1549c0',
    glow: 'rgba(21,73,192,0.5)',
    ic: 'fa-heart-pulse',
    priority: 'critical',
    msg_en: 'Code Blue… Code Blue… {loc}. Medical emergency team, respond immediately.',
    msg_ar: 'كود أزرق… كود أزرق… {loc_ar}. فريق الطوارئ الطبية، الاستجابة فورًا.',
  },
  {
    id: 'CODE_RED',
    n: 'Code Red',
    d: 'Fire Emergency',
    ar: 'كود أحمر — حريق',
    cl: '#fff', bg: '#b91c1c',
    glow: 'rgba(185,28,28,0.5)',
    ic: 'fa-fire',
    priority: 'critical',
    msg_en: 'Code Red… Code Red… {loc}. All staff, follow fire emergency protocol immediately.',
    msg_ar: 'كود أحمر… كود أحمر… {loc_ar}. جميع الكوادر، اتبعوا بروتوكول الحريق فورًا.',
  },
  {
    id: 'CODE_WHITE',
    n: 'Code White',
    d: 'Violent Person',
    ar: 'كود أبيض — شخص عنيف',
    cl: '#111', bg: '#f1f5f9',
    glow: 'rgba(100,116,139,0.4)',
    ic: 'fa-shield-halved',
    priority: 'high',
    msg_en: 'Code White… Code White… {loc}. Security team, respond immediately.',
    msg_ar: 'كود أبيض… كود أبيض… {loc_ar}. فريق الأمن، الاستجابة فورًا.',
  },
  {
    id: 'CODE_PINK',
    n: 'Code Pink',
    d: 'Infant Abduction',
    ar: 'كود وردي — اختطاف رضيع',
    cl: '#fff', bg: '#be185d',
    glow: 'rgba(190,24,93,0.5)',
    ic: 'fa-baby',
    priority: 'critical',
    msg_en: 'Code Pink… Code Pink. Infant abduction alert. All exits are secured. Security, respond immediately.',
    msg_ar: 'كود وردي… تنبيه اختطاف رضيع. جميع المخارج مغلقة. فريق الأمن، الاستجابة فورًا.',
  },
  {
    id: 'CODE_BLACK',
    n: 'Code Black',
    d: 'Bomb Threat',
    ar: 'كود أسود — تهديد بقنبلة',
    cl: '#fff', bg: '#18181b',
    glow: 'rgba(24,24,27,0.6)',
    ic: 'fa-skull-crossbones',
    priority: 'critical',
    msg_en: 'Code Black… Code Black. Bomb threat received. Follow evacuation protocol immediately.',
    msg_ar: 'كود أسود… تم استلام تهديد بقنبلة. اتبعوا بروتوكول الإخلاء فورًا.',
  },
  {
    id: 'CODE_YELLOW',
    n: 'Code Yellow',
    d: 'Missing Patient',
    ar: 'كود أصفر — مريض مفقود',
    cl: '#111', bg: '#d97706',
    glow: 'rgba(217,119,6,0.5)',
    ic: 'fa-magnifying-glass',
    priority: 'high',
    msg_en: 'Code Yellow… Code Yellow. Missing patient alert at {loc}. All staff, be on alert.',
    msg_ar: 'كود أصفر… تنبيه مريض مفقود في {loc_ar}. جميع الكوادر، كونوا في حالة تأهب.',
  },
  {
    id: 'RRT_TEAM',
    n: 'RRT Team',
    d: 'Rapid Response',
    ar: 'فريق الاستجابة السريعة',
    cl: '#fff', bg: '#7c3aed',
    glow: 'rgba(124,58,237,0.5)',
    ic: 'fa-truck-medical',
    priority: 'high',
    msg_en: 'Rapid Response Team required at {loc}. R R T team, respond immediately.',
    msg_ar: 'مطلوب فريق الاستجابة السريعة في {loc_ar}. فريق الاستجابة السريعة، الاستجابة فورًا.',
  },
  {
    id: 'CODE_PURPLE',
    n: 'Code Purple',
    d: 'Hostage Situation',
    ar: 'كود بنفسجي — احتجاز رهينة',
    cl: '#fff', bg: '#4f46e5',
    glow: 'rgba(79,70,229,0.5)',
    ic: 'fa-user-lock',
    priority: 'critical',
    msg_en: 'Code Purple… Code Purple. Hostage situation reported. Security and authorities notified.',
    msg_ar: 'كود بنفسجي… تم الإبلاغ عن حالة احتجاز رهينة. تم إخطار الأمن والسلطات.',
  },
];

const CODES_MAP = Object.fromEntries(CODES.map(c => [c.id, c]));

const CODE_ACTIONS = {
  CODE_BLUE:   'Crash team respond immediately, bring crash cart and defibrillator',
  CODE_RED:    'Evacuate area, call fire department 998, use extinguishers',
  CODE_WHITE:  'Security contain situation, do not approach alone, call 911',
  CODE_PINK:   'Lock all exits, check all persons leaving, call security immediately',
  CODE_BLACK:  'Do not touch, evacuate area, notify police 999 immediately',
  CODE_YELLOW: 'Search all areas, check CCTV, notify all security personnel',
  RRT_TEAM:    'RRT team respond with equipment including crash cart to stated location',
  CODE_PURPLE: 'Do not confront, notify police, follow hostage protocol',
};

const TMPLS = [
  'Dr. [Name], [Specialty] on call, please contact the [Department], extension [EXT].',
  'Attention [Staff Role] on duty, please report to [Location] immediately, extension [EXT].',
  'Attention please: A visitor is requested at [Department]. Kindly proceed to the information desk.',
  'General announcement for all staff: [Your message here].',
  'Dr. [Name], your presence is required at [Department] urgently.',
  'Pharmacy: Please deliver medication urgently to [Department], extension [EXT].',
];

const LEVELS = ['Consultant', 'Specialist', 'Resident', 'Intern', 'On-call Physician'];

// Pronunciation overrides for TTS
const TTS_PRONUNCIATIONS = {
  'ICU':   'I C U',
  'CCU':   'C C U',
  'NICU':  'N I C U',
  'OR ':   'Operating Room ',
  'ER ':   'Emergency Room ',
  'ENT':   'E N T',
  'RRT':   'R R T',
  'IT ':   'I T ',
};

function fixPronunciation(text) {
  let result = text;
  for (const [abbr, spoken] of Object.entries(TTS_PRONUNCIATIONS)) {
    result = result.replaceAll(abbr, spoken);
  }
  // Read extension digits individually: "extension 2675" → "extension 2. 6. 7. 5."
  result = result.replace(/extension\s+(\d+)/gi, (match, digits) => {
    return 'extension ' + digits.split('').join('. ') + '.';
  });
  return result;
}

function numToWords(n) {
  const map = {
    '0':'zero','1':'one','2':'two','3':'three','4':'four',
    '5':'five','6':'six','7':'seven','8':'eight','9':'nine'
  };
  return String(n).split('').map(d => map[d] || d).join(' ');
}
