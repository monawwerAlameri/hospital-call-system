# 🚀 دليل النشر — Hospital Call System v3.1

هذا الدليل يشرح كيفية نشر نظام نداءات مستشفى الملك خالد على استضافة مجانية مع الحفاظ على نفس قاعدة البيانات.

---

## 📋 الخيارات المتاحة

| الخيار | الميزة | العيوب | التقييم |
|-------|--------|-------|---------|
| **Render + Aiven** | مجاني تماماً، MySQL 5GB، SSL تلقائي | يتطلب تأكيد بريد فقط | ⭐⭐⭐⭐⭐ الأفضل |
| **Render + db4free** | أسهل تسجيل، لا بريد مطلوب | 200MB فقط، بطيء | ⭐⭐⭐ |
| **Railway + Railway MySQL** | إعداد منقرة واحدة | يحتاج بطاقة للتحقق | ⭐⭐⭐⭐ |
| **InfinityFree** | PHP مجاني + MySQL | إعلانات، FTP يدوي | ⭐⭐ |

**النوصية: استخدم Render + Aiven** (الخيار الأول).

---

## 🎯 الطريقة 1: Render + Aiven (الأفضل والمجاني)

### الخطوة 1: إنشاء قاعدة بيانات MySQL مجانية على Aiven

1. اذهب إلى https://aiven.io واضغط **Sign up free**
2. أدخل بريدك وكلمة المرور (لا حاجة لبطاقة ائتمان)
3. بعد الدخول، اضغط **Create service**
4. اختر **MySQL**
5. اختر الخطة **Free-0-5GB** (مجانية بالكامل)
6. اختر **Region** قريب منك (مثلاً `google-northamerica-northeast1`)
7. اضغط **Create free service**
8. انتظر دقيقتين حتى يصبح الـ Service حالة `Running`
9. اضغط على الـ Service، ثم انسخ البيانات التالية من صفحة **Overview**:
   - **Host** (مثلاً: `mysql-xxxxx-xxxx.aivencloud.com`)
   - **Port** (عادةً `12345` أو رقم آخر)
   - **User** (عادةً `avnadmin`)
   - **Password** (اضغط على أيقونة العين لعرضه)
   - **Database Name** (عادةً `defaultdb`)

### الخطوة 2: رفع الكود إلى GitHub

```bash
cd hospital-call-system
git init
git add .
git commit -m "Hospital Call System v3.1 — ready for deployment"
git branch -M main
git remote add origin https://github.com/USERNAME/hospital-call-system.git
git push -u origin main
```

> إذا أردت، يمكنني رفع الكود إلى المستودع الموجود على GitHub الخاص بك
> (`github.com/monawwerAlameri/hospital-call-system`).

### الخطوة 3: النشر على Render

1. اذهب إلى https://render.com واضغط **Sign up** (يمكنك التسجيل بحساب GitHub)
2. اضغط **New +** ← **Web Service**
3. اختر مستودع GitHub الذي رفعت الكود إليه
4. اضغط **Apply** على شاشة الـ Blueprint (Render سيكتشف ملف `render.yaml` تلقائياً)
5. Render سيطلب منك تعبئة متغيرات البيئة التالية:

| المتغير | القيمة |
|---------|--------|
| `DB_HOST` | (الـ Host من Aiven — مثلاً `mysql-xxxxx.aivencloud.com`) |
| `DB_PORT` | (الـ Port من Aiven — مثلاً `12345`) |
| `DB_USER` | `avnadmin` (أو ما يعطيك إياه Aiven) |
| `DB_PASS` | (كلمة المرور من Aiven) |
| `DB_NAME` | `defaultdb` (أو اسم الـ database الذي أنشأته على Aiven) |

6. اضغط **Create Web Service**
7. انتظر 3-5 دقائق حتى ينتهي البناء (Build) — سترى اللون الأخضر ✅

### الخطوة 4: تهيئة قاعدة البيانات

1. بعد اكتمال النشر، اضغط على الرابط الذي يعطيك Render (مثلاً `https://hospital-call-system-xxxx.onrender.com`)
2. ستظهر صفحة الـ landing
3. اذهب إلى: `https://hospital-call-system-xxxx.onrender.com/db-check.php`
4. ستظهر نتائج فحص الاتصال — تأكد أن كل شيء ✅
5. هذا سيقوم تلقائياً بإنشاء الجداول وإضافة البيانات الأولية (الكودات، الأقسام، إعدادات الزيارة)

### الخطوة 5: استخدام النظام

- افتح: `https://hospital-call-system-xxxx.onrender.com/`
- اضغط **Launch System** → ستنتقل مباشرة للوحة التحكم بدون تسجيل دخول
- جرب الأكواد (Code Blue, Code Red...) — ستسمع التنسيق الجديد: "Code Blue in Emergency Room"
- جرب صفحة **Visiting Hours** في السايدبار ← قسم **Smart Features**

---

## 🎯 الطريقة 2: Railway (الأسهل إذا كان لديك بطاقة)

1. اذهب إلى https://railway.app واضغط **Sign in with GitHub**
2. اضغط **New Project** ← **Deploy from GitHub repo**
3. اختر مستودعك
4. Railway سيكتشف `Dockerfile` تلقائياً
5. اضغط **Add Variable** لكل من:
   - `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`
6. لإنشاء قاعدة البيانات: اضغط **New** ← **Database** ← **Add MySQL**
7. Railway سيعطيك متغيرات الـ DB تلقائياً — انسخها إلى متغيرات الـ Web Service
8. النشر سيبدأ تلقائياً

---

## 🎯 الطريقة 3: InfinityFree (لمن لا يريد Docker)

1. اذهب إلى https://infinityfree.com وسجل حساباً مجانياً
2. في لوحة التحكم، اضغط **MySQL Databases** ← **Create New Database**
   - اسم القاعدة: `hospital_call_system`
   - أنشئ مستخدم وامنحه جميع الصلاحيات على هذه القاعدة
3. اضغط **File Manager** أو استخدم FTP (معلومات FTP في لوحة التحكم)
4. ارفع كل ملفات المشروع إلى مجلد `htdocs` أو `public_html`
5. اذهب إلى phpMyAdmin على InfinityFree وافتح قاعدة `hospital_call_system`
6. اذهب إلى **Import** وارفع ملف `hospital_call_system (2).sql`
7. عدّل ملف `api/config.php` يدوياً إذا لزم الأمر (لكن الـ env vars الافتراضية ستعمل لأن InfinityFree يضبطها تلقائياً)

> ملاحظة: على InfinityFree، استبدل الأسطر الأولى في `api/config.php`:
> ```php
> define('DB_HOST', 'sqlXXX.infinityfree.com');  // من لوحة التحكم
> define('DB_USER', 'if0_XXXXXXXXXX');
> define('DB_PASS', 'your_password');
> define('DB_NAME', 'if0_XXXXXXXXXX_hospital_call_system');
> ```

---

## 🔧 الاختبار المحلي قبل النشر (Docker)

إذا أردت اختبار النشر محلياً قبل رفعه:

```bash
cd hospital-call-system
docker-compose up --build
```

ثم افتح http://localhost:8080 في المتصفح.

- التطبيق: http://localhost:8080
- فحص الـ DB: http://localhost:8080/db-check.php
- MySQL سيكون على localhost:3307 (root:rootpass)

---

## ⚠️ ملاحظات مهمة

1. **Render Free Tier**:
   - الخدمة "تنام" بعد 15 دقيقة من عدم النشاط
   - أول طلب بعد النوم يستغرق ~30 ثانية (cold start)
   - 750 ساعة شهرياً (تكفي لتطبيق واحد يعمل 24/7)
   - للاستخدام الإنتاجي الحقيقي، انتقل إلى خطة مدفوعة ($7/شهر)

2. **قاعدة البيانات الخارجية**:
   - تأكد أن مزود الـ MySQL يسمح بالاتصالات من خارج شبكته
   - Aiven و db4free يسمحان بذلك افتراضياً
   - بعض المزودين يتطلبون إضافة IP الخاص بـ Render إلى قائمة المسموح

3. **حجم ملفات الصوت**:
   - ملفا `chime-general.mp4` و `chime-code.mp4` كبيران (~5 ميجا لكل منهما)
   - سيتم رفعهما مع التطبيق ولا يحتاجان إعداداً خاصاً

4. **النسخ الاحتياطي**:
   - على Aiven Free، يمكن أخذ نسخة احتياطية يدوياً كل أسبوع
   - على db4free، لا يوجد نسخ احتياطي تلقائي — خذ نسخة بنفسك عبر phpMyAdmin

---

## 📞 استكشاف الأخطاء

### المشكلة: `Database offline` أو `Database not found`
- تأكد أن `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME` مضبوطة في Environment على Render
- افتح `https://your-app.onrender.com/db-check.php` لرؤية تفاصيل الخطأ
- على بعض المزودين (مثل db4free)، يجب إنشاء الـ database يدوياً أولاً

### المشكلة: الـ chime (نغمة الصوت) لا تعمل
- تحقق أن ملفات `assets/audio/chime-*.mp4` موجودة في الصورة
- تحقق أن المتصفح يسمح بتشغيل الصوت (autoplay policy)
- انقر على أي مكان في الصفحة لتفعيل AudioContext

### المشكلة: النظام بطيء جداً
- هذا متوقع على Render Free بعد فترة عدم نشاط (cold start)
- للسرعة، انتقل إلى خطة مدفوعة ($7/شهر) أو استخدم Railway

---

## ✅ قائمة التحقق قبل النشر

- [ ] رفع الكود إلى GitHub
- [ ] إنشاء حساب على Render
- [ ] إنشاء قاعدة بيانات MySQL على Aiven (أو db4free)
- [ ] تعبئة متغيرات البيئة في Render (DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_NAME)
- [ ] اكتمال البناء بدون أخطاء
- [ ] زيارة `/db-check.php` للتأكد من نجاح الاتصال بالـ DB
- [ ] زيارة الصفحة الرئيسية والتأكد من عمل النظام

---

## 🌐 روابط مفيدة

- **Render Dashboard**: https://dashboard.render.com
- **Aiven Console**: https://console.aiven.io
- **db4free Signup**: https://www.db4free.net/signup.php
- **Railway**: https://railway.app
- **InfinityFree**: https://app.infinityfree.com

بعد النشر، شارك رابط موقعك مع زملائك في المستشفى!
