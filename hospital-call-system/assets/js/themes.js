const THEMES = [
  {
    id: 'default', name: 'Ocean Blue', name_ar: 'أزرق المحيط', icon: 'fa-water',
    colors: {
      '--primary': '#1F2A6D', '--primary-light': '#2E4A9E', '--primary-dark': '#141c4a',
      '--accent-teal': '#1FA971', '--accent-cyan': '#1F6F8B', '--accent-emerald': '#38C98A',
      '--sidebar-bg': 'linear-gradient(180deg, #1F2A6D 0%, #1F6F8B 55%, #1FA971 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #1F2A6D 0%, #2E4A9E 25%, #1F6F8B 50%, #1FA971 75%, #38C98A 100%)',
    },
    preview: ['#1F2A6D','#2E4A9E','#1F6F8B','#1FA971','#38C98A']
  },
  {
    id: 'royal-purple', name: 'Royal Purple', name_ar: 'بنفسجي ملكي', icon: 'fa-crown',
    colors: {
      '--primary': '#4A0072', '--primary-light': '#7B1FA2', '--primary-dark': '#38006b',
      '--accent-teal': '#E040FB', '--accent-cyan': '#9C27B0', '--accent-emerald': '#CE93D8',
      '--sidebar-bg': 'linear-gradient(180deg, #4A0072 0%, #7B1FA2 50%, #E040FB 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #4A0072 0%, #7B1FA2 25%, #9C27B0 50%, #E040FB 75%, #CE93D8 100%)',
    },
    preview: ['#4A0072','#7B1FA2','#9C27B0','#E040FB','#CE93D8']
  },
  {
    id: 'sunset-orange', name: 'Sunset Orange', name_ar: 'برتقالي الغروب', icon: 'fa-sun',
    colors: {
      '--primary': '#BF360C', '--primary-light': '#E64A19', '--primary-dark': '#8D2B0B',
      '--accent-teal': '#FF9800', '--accent-cyan': '#F4511E', '--accent-emerald': '#FFB74D',
      '--sidebar-bg': 'linear-gradient(180deg, #BF360C 0%, #E64A19 50%, #FF9800 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #BF360C 0%, #E64A19 25%, #F4511E 50%, #FF9800 75%, #FFB74D 100%)',
    },
    preview: ['#BF360C','#E64A19','#F4511E','#FF9800','#FFB74D']
  },
  {
    id: 'emerald-forest', name: 'Emerald Forest', name_ar: 'غابة الزمرد', icon: 'fa-tree',
    colors: {
      '--primary': '#1B5E20', '--primary-light': '#2E7D32', '--primary-dark': '#0D3B13',
      '--accent-teal': '#4CAF50', '--accent-cyan': '#388E3C', '--accent-emerald': '#81C784',
      '--sidebar-bg': 'linear-gradient(180deg, #1B5E20 0%, #2E7D32 50%, #4CAF50 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #1B5E20 0%, #2E7D32 25%, #388E3C 50%, #4CAF50 75%, #81C784 100%)',
    },
    preview: ['#1B5E20','#2E7D32','#388E3C','#4CAF50','#81C784']
  },
  {
    id: 'crimson-red', name: 'Crimson Red', name_ar: 'أحمر قرمزي', icon: 'fa-heart',
    colors: {
      '--primary': '#B71C1C', '--primary-light': '#D32F2F', '--primary-dark': '#7F0000',
      '--accent-teal': '#FF5252', '--accent-cyan': '#E53935', '--accent-emerald': '#EF9A9A',
      '--sidebar-bg': 'linear-gradient(180deg, #B71C1C 0%, #D32F2F 50%, #FF5252 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #B71C1C 0%, #D32F2F 25%, #E53935 50%, #FF5252 75%, #EF9A9A 100%)',
    },
    preview: ['#B71C1C','#D32F2F','#E53935','#FF5252','#EF9A9A']
  },
  {
    id: 'golden-amber', name: 'Golden Amber', name_ar: 'ذهبي كهرماني', icon: 'fa-gem',
    colors: {
      '--primary': '#E65100', '--primary-light': '#F57C00', '--primary-dark': '#BF360C',
      '--accent-teal': '#FFB300', '--accent-cyan': '#FF8F00', '--accent-emerald': '#FFD54F',
      '--sidebar-bg': 'linear-gradient(180deg, #E65100 0%, #F57C00 50%, #FFB300 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #E65100 0%, #F57C00 25%, #FF8F00 50%, #FFB300 75%, #FFD54F 100%)',
    },
    preview: ['#E65100','#F57C00','#FF8F00','#FFB300','#FFD54F']
  },
  {
    id: 'midnight-indigo', name: 'Midnight Indigo', name_ar: 'نيلي منتصف الليل', icon: 'fa-moon',
    colors: {
      '--primary': '#1A237E', '--primary-light': '#283593', '--primary-dark': '#0D1642',
      '--accent-teal': '#5C6BC0', '--accent-cyan': '#3949AB', '--accent-emerald': '#9FA8DA',
      '--sidebar-bg': 'linear-gradient(180deg, #1A237E 0%, #283593 50%, #5C6BC0 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #1A237E 0%, #283593 25%, #3949AB 50%, #5C6BC0 75%, #9FA8DA 100%)',
    },
    preview: ['#1A237E','#283593','#3949AB','#5C6BC0','#9FA8DA']
  },
  {
    id: 'teal-ocean', name: 'Teal Ocean', name_ar: 'بحر فيروزي', icon: 'fa-fish',
    colors: {
      '--primary': '#004D40', '--primary-light': '#00695C', '--primary-dark': '#00251A',
      '--accent-teal': '#26A69A', '--accent-cyan': '#00897B', '--accent-emerald': '#80CBC4',
      '--sidebar-bg': 'linear-gradient(180deg, #004D40 0%, #00695C 50%, #26A69A 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #004D40 0%, #00695C 25%, #00897B 50%, #26A69A 75%, #80CBC4 100%)',
    },
    preview: ['#004D40','#00695C','#00897B','#26A69A','#80CBC4']
  },
  {
    id: 'rose-pink', name: 'Rose Pink', name_ar: 'وردي زهري', icon: 'fa-spa',
    colors: {
      '--primary': '#880E4F', '--primary-light': '#AD1457', '--primary-dark': '#560027',
      '--accent-teal': '#EC407A', '--accent-cyan': '#D81B60', '--accent-emerald': '#F48FB1',
      '--sidebar-bg': 'linear-gradient(180deg, #880E4F 0%, #AD1457 50%, #EC407A 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #880E4F 0%, #AD1457 25%, #D81B60 50%, #EC407A 75%, #F48FB1 100%)',
    },
    preview: ['#880E4F','#AD1457','#D81B60','#EC407A','#F48FB1']
  },
  {
    id: 'slate-gray', name: 'Slate Professional', name_ar: 'رمادي احترافي', icon: 'fa-briefcase',
    colors: {
      '--primary': '#37474F', '--primary-light': '#455A64', '--primary-dark': '#263238',
      '--accent-teal': '#78909C', '--accent-cyan': '#607D8B', '--accent-emerald': '#B0BEC5',
      '--sidebar-bg': 'linear-gradient(180deg, #263238 0%, #37474F 50%, #607D8B 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #263238 0%, #37474F 25%, #455A64 50%, #607D8B 75%, #90A4AE 100%)',
    },
    preview: ['#263238','#37474F','#455A64','#607D8B','#90A4AE']
  },
  {
    id: 'electric-blue', name: 'Electric Blue', name_ar: 'أزرق كهربائي', icon: 'fa-bolt',
    colors: {
      '--primary': '#0D47A1', '--primary-light': '#1565C0', '--primary-dark': '#002171',
      '--accent-teal': '#42A5F5', '--accent-cyan': '#1E88E5', '--accent-emerald': '#90CAF9',
      '--sidebar-bg': 'linear-gradient(180deg, #0D47A1 0%, #1565C0 50%, #42A5F5 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #0D47A1 0%, #1565C0 25%, #1E88E5 50%, #42A5F5 75%, #90CAF9 100%)',
    },
    preview: ['#0D47A1','#1565C0','#1E88E5','#42A5F5','#90CAF9']
  },
  {
    id: 'cherry-blossom', name: 'Cherry Blossom', name_ar: 'زهر الكرز', icon: 'fa-seedling',
    colors: {
      '--primary': '#C2185B', '--primary-light': '#E91E63', '--primary-dark': '#880E4F',
      '--accent-teal': '#F06292', '--accent-cyan': '#EC407A', '--accent-emerald': '#F8BBD0',
      '--sidebar-bg': 'linear-gradient(180deg, #880E4F 0%, #C2185B 40%, #F06292 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #880E4F 0%, #C2185B 25%, #E91E63 50%, #F06292 75%, #F8BBD0 100%)',
    },
    preview: ['#880E4F','#C2185B','#E91E63','#F06292','#F8BBD0']
  },
  {
    id: 'volcano', name: 'Volcano', name_ar: 'بركاني', icon: 'fa-fire',
    colors: {
      '--primary': '#DD2C00', '--primary-light': '#FF3D00', '--primary-dark': '#BF0000',
      '--accent-teal': '#FF6E40', '--accent-cyan': '#FF5722', '--accent-emerald': '#FFAB91',
      '--sidebar-bg': 'linear-gradient(180deg, #BF0000 0%, #DD2C00 40%, #FF6E40 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #BF0000 0%, #DD2C00 25%, #FF3D00 50%, #FF6E40 75%, #FFAB91 100%)',
    },
    preview: ['#BF0000','#DD2C00','#FF3D00','#FF6E40','#FFAB91']
  },
  {
    id: 'lavender-dream', name: 'Lavender Dream', name_ar: 'حلم اللافندر', icon: 'fa-cloud',
    colors: {
      '--primary': '#5E35B1', '--primary-light': '#7E57C2', '--primary-dark': '#4527A0',
      '--accent-teal': '#B39DDB', '--accent-cyan': '#9575CD', '--accent-emerald': '#D1C4E9',
      '--sidebar-bg': 'linear-gradient(180deg, #4527A0 0%, #5E35B1 50%, #B39DDB 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #4527A0 0%, #5E35B1 25%, #7E57C2 50%, #9575CD 75%, #D1C4E9 100%)',
    },
    preview: ['#4527A0','#5E35B1','#7E57C2','#9575CD','#D1C4E9']
  },
  {
    id: 'ocean-cyan', name: 'Ocean Cyan', name_ar: 'سماوي المحيط', icon: 'fa-anchor',
    colors: {
      '--primary': '#006064', '--primary-light': '#00838F', '--primary-dark': '#003B3E',
      '--accent-teal': '#26C6DA', '--accent-cyan': '#00ACC1', '--accent-emerald': '#80DEEA',
      '--sidebar-bg': 'linear-gradient(180deg, #006064 0%, #00838F 50%, #26C6DA 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #006064 0%, #00838F 25%, #00ACC1 50%, #26C6DA 75%, #80DEEA 100%)',
    },
    preview: ['#006064','#00838F','#00ACC1','#26C6DA','#80DEEA']
  },
  {
    id: 'dark-knight', name: 'Dark Knight', name_ar: 'الفارس المظلم', icon: 'fa-mask',
    colors: {
      '--primary': '#212121', '--primary-light': '#424242', '--primary-dark': '#0a0a0a',
      '--accent-teal': '#00E676', '--accent-cyan': '#616161', '--accent-emerald': '#69F0AE',
      '--sidebar-bg': 'linear-gradient(180deg, #0a0a0a 0%, #212121 50%, #424242 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #0a0a0a 0%, #212121 25%, #424242 50%, #616161 75%, #9E9E9E 100%)',
    },
    preview: ['#0a0a0a','#212121','#424242','#616161','#00E676']
  },
  {
    id: 'turquoise-mint', name: 'Turquoise Mint', name_ar: 'تركواز نعناعي', icon: 'fa-leaf',
    colors: {
      '--primary': '#00796B', '--primary-light': '#00897B', '--primary-dark': '#004D40',
      '--accent-teal': '#4DB6AC', '--accent-cyan': '#26A69A', '--accent-emerald': '#B2DFDB',
      '--sidebar-bg': 'linear-gradient(180deg, #004D40 0%, #00796B 50%, #4DB6AC 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #004D40 0%, #00796B 25%, #00897B 50%, #26A69A 75%, #80CBC4 100%)',
    },
    preview: ['#004D40','#00796B','#00897B','#26A69A','#4DB6AC']
  },
  {
    id: 'coral-reef', name: 'Coral Reef', name_ar: 'شعاب مرجانية', icon: 'fa-dragon',
    colors: {
      '--primary': '#D84315', '--primary-light': '#F4511E', '--primary-dark': '#BF360C',
      '--accent-teal': '#FF7043', '--accent-cyan': '#FF5722', '--accent-emerald': '#FFAB91',
      '--sidebar-bg': 'linear-gradient(180deg, #BF360C 0%, #D84315 50%, #FF7043 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #BF360C 0%, #D84315 25%, #F4511E 50%, #FF7043 75%, #FFCCBC 100%)',
    },
    preview: ['#BF360C','#D84315','#F4511E','#FF7043','#FFCCBC']
  },
  {
    id: 'sapphire-night', name: 'Sapphire Night', name_ar: 'ياقوت الليل', icon: 'fa-star',
    colors: {
      '--primary': '#0D1B3E', '--primary-light': '#1A3A6E', '--primary-dark': '#060E21',
      '--accent-teal': '#4FC3F7', '--accent-cyan': '#29B6F6', '--accent-emerald': '#B3E5FC',
      '--sidebar-bg': 'linear-gradient(180deg, #060E21 0%, #0D1B3E 40%, #29B6F6 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #060E21 0%, #0D1B3E 25%, #1A3A6E 50%, #29B6F6 75%, #B3E5FC 100%)',
    },
    preview: ['#060E21','#0D1B3E','#1A3A6E','#29B6F6','#4FC3F7']
  },
  {
    id: 'hospital-green', name: 'Hospital Green', name_ar: 'أخضر طبي', icon: 'fa-hospital',
    colors: {
      '--primary': '#0E4D45', '--primary-light': '#1A7A6D', '--primary-dark': '#083832',
      '--accent-teal': '#2ECC71', '--accent-cyan': '#1ABC9C', '--accent-emerald': '#A3E4D7',
      '--sidebar-bg': 'linear-gradient(180deg, #083832 0%, #0E4D45 40%, #2ECC71 100%)',
      '--primary-gradient': 'linear-gradient(135deg, #083832 0%, #0E4D45 25%, #1A7A6D 50%, #1ABC9C 75%, #2ECC71 100%)',
    },
    preview: ['#083832','#0E4D45','#1A7A6D','#1ABC9C','#2ECC71']
  }
];

function applyTheme(themeId) {
  const theme = THEMES.find(t => t.id === themeId);
  if (!theme) return;
  const root = document.documentElement;
  Object.entries(theme.colors).forEach(([key, val]) => {
    root.style.setProperty(key, val);
  });

  const p = theme.colors['--primary'];
  const pl = theme.colors['--primary-light'];
  const pd = theme.colors['--primary-dark'];
  const at = theme.colors['--accent-teal'];
  const ac = theme.colors['--accent-cyan'];
  const ae = theme.colors['--accent-emerald'];
  const grad = `linear-gradient(135deg, ${p} 0%, ${pl} 30%, ${ac} 65%, ${at} 100%)`;
  const gradH = `linear-gradient(90deg, ${p}, ${pl}, ${at})`;

  root.style.setProperty('--sidebar-active', hexToRgba(at, 0.25));
  root.style.setProperty('--sidebar-hover', 'rgba(255,255,255,0.1)');
  root.style.setProperty('--text-secondary', p);
  root.style.setProperty('--info', pl);
  root.style.setProperty('--success', at);

  const topbar = document.querySelector('.topbar');
  if (topbar) topbar.style.background = grad;

  document.querySelectorAll('.card-header').forEach(function(ch) {
    ch.style.background = `linear-gradient(135deg, ${p} 0%, ${pl} 50%, ${ac} 100%)`;
  });

  document.querySelectorAll('.btn-call.blue, .btn-call.purple, .auth-btn').forEach(function(btn) {
    btn.style.background = grad;
  });

  document.querySelectorAll('.stat-icon.blue').forEach(function(si) {
    si.style.background = hexToRgba(p, 0.1);
    si.style.color = p;
  });

  var navbar = document.querySelector('.navbar') || document.querySelector('.auth-navbar');
  if (navbar) navbar.style.background = gradH;

  var hero = document.querySelector('.hero');
  if (hero) hero.style.background = `linear-gradient(145deg, ${pd} 0%, ${p} 50%, ${ac} 100%)`;

  document.querySelectorAll('.btn-hero-primary').forEach(function(b) {
    b.style.background = `linear-gradient(90deg, ${pl}, ${at})`;
  });

  var ctaSec = document.querySelector('.cta-section');
  if (ctaSec) ctaSec.style.background = `linear-gradient(155deg, ${p}, ${pl}, ${at})`;

  var footer = document.querySelector('footer');
  if (footer) footer.style.background = `linear-gradient(155deg, ${p}, ${pl}, ${at})`;

  var howSec = document.querySelector('.how-section');
  if (howSec) howSec.style.background = `linear-gradient(140deg, ${pd} 0%, ${p} 100%)`;

  document.querySelectorAll('.bnav-bar, .pub-bnav-bar').forEach(function(b) {
    b.style.background = `linear-gradient(135deg, ${pd} 0%, ${ac} 50%, ${pl} 100%)`;
  });
  document.querySelectorAll('.bnav-fab, .pub-bnav-fab').forEach(function(f) {
    f.style.background = `linear-gradient(135deg, ${pl}, ${at})`;
  });

  document.querySelectorAll('.chatbot-fab').forEach(function(f) {
    f.style.background = `linear-gradient(135deg, ${pl}, ${at})`;
  });
  document.querySelectorAll('.chatbot-header').forEach(function(h) {
    h.style.background = `linear-gradient(135deg, ${pl}, ${at})`;
  });

  document.querySelectorAll('.auth-left').forEach(function(al) {
    al.style.background = theme.colors['--primary-gradient'];
  });

  document.querySelectorAll('.step-num').forEach(function(sn) {
    sn.style.background = `linear-gradient(90deg, ${ac}, ${at})`;
  });
  document.querySelectorAll('.feat-icon').forEach(function(fi) {
    if (!fi.style.background || fi.style.background.indexOf('gradient') === -1) return;
    fi.style.background = `linear-gradient(135deg, ${p}, ${at})`;
  });

  localStorage.setItem('hcs_theme', themeId);

  document.querySelectorAll('.theme-card').forEach(function(tc) {
    tc.classList.remove('active');
    if (tc.dataset.theme === themeId) tc.classList.add('active');
  });
}

function hexToRgba(hex, alpha) {
  if (!hex || hex.charAt(0) !== '#') return `rgba(0,0,0,${alpha})`;
  const r = parseInt(hex.slice(1, 3), 16);
  const g = parseInt(hex.slice(3, 5), 16);
  const b = parseInt(hex.slice(5, 7), 16);
  return `rgba(${r},${g},${b},${alpha})`;
}

function renderThemeCards() {
  const grid = document.getElementById('themeGrid');
  if (!grid) return;
  const lang = localStorage.getItem('hcs_lang') || 'en';
  const current = localStorage.getItem('hcs_theme') || 'default';
  grid.innerHTML = THEMES.map(t => `
    <div class="theme-card ${t.id === current ? 'active' : ''}" data-theme="${t.id}" onclick="applyTheme('${t.id}')">
      <div class="theme-preview">
        ${t.preview.map(c => `<div class="theme-swatch" style="background:${c}"></div>`).join('')}
      </div>
      <div class="theme-card-body">
        <div class="theme-icon"><i class="fas ${t.icon}"></i></div>
        <div class="theme-name">${lang === 'ar' ? t.name_ar : t.name}</div>
        <div class="theme-name-sub">${lang === 'ar' ? t.name : t.name_ar}</div>
      </div>
      ${t.id === current ? '<div class="theme-active-badge"><i class="fas fa-check"></i></div>' : ''}
    </div>
  `).join('');
}

document.addEventListener('DOMContentLoaded', function() {
  const saved = localStorage.getItem('hcs_theme');
  if (saved && saved !== 'default') applyTheme(saved);
});
