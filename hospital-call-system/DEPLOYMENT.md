# 🚀 دليل النشر على Render — Hospital Call System v3.1

> ✅ المستودع الآن **عام** (public) على GitHub  
> 🔗 https://github.com/monawwerAlameri/hospital-call-system  
> ✅ بيانات Aiven MySQL مُعدّة مسبقاً في `render.yaml`  
> ✅ شهادة SSL (ca.pem) مُضمّنة في `api/ca.pem`

---

## 🎯 الخطوات (5 دقائق فقط)

### 1️⃣ ادخل إلى Render وسجّل حساب

- افتح https://render.com
- اضغط **Sign up** أو **Get Started**
- سجّل باستخدام حساب GitHub (الأسرع)

---

### 2️⃣ أنشئ Web Service جديد

- من لوحة التحكم، اضغط **New +** (أعلى اليمين)
- اختر **Web Service**
- اضغط **Build and deploy from a Git repository**
- اضغط **Next**

---

### 3️⃣ اربط المستودع

- في خانة **Public Git repository**، الصق:
  ```
  https://github.com/monawwerAlameri/hospital-call-system
  ```
- اضغط **Continue**

> 💡 إذا ظهر خيار **"Connect account"** أو طلب صلاحيات GitHub، اضغط عليه وأعطه صلاحية الوصول للمستودع.

---

### 4️⃣ املأ الإعدادات (Render سيكتشف `render.yaml` تلقائياً)

| الحقل | القيمة |
|------|--------|
| **Name** | `hospital-call-system` |
| **Region** | أقرب منطقة (مثلاً Frankfurt أو Oregon) |
| **Branch** | `main` |
| **Runtime** | Docker (تلقائي) |
| **Instance Type** | Free |

ثم في قسم **Environment Variables**، Render سيطلب منك فقط إدخال:

| المتغير | القيمة |
|---------|--------|
| `DB_PASS` | (كلمة مرور Aiven — `AVNS_...` التي أعطاك إياها Aiven) |

> باقي المتغيرات (`DB_HOST`, `DB_PORT`, `DB_USER`, `DB_NAME`, `DB_SSL`) موجودة مسبقاً في `render.yaml`.

---

### 5️⃣ اضغط Create Web Service

- Render سيبدأ بـ:
  1. سحب الكود من GitHub
  2. بناء صورة Docker (Apache + PHP 8.3 + mysqli)
  3. رفعها على Render
- المدة المتوقعة: 3-5 دقائق
- سترى **Logs** مباشرة أثناء البناء

---

### 6️⃣ بعد اكتمال النشر

سيظهر لك رابط بصيغة:
```
https://hospital-call-system-xxxx.onrender.com
```

#### 🔍 تحقق من الاتصال بقاعدة البيانات:

افتح الرابط التالي في المتصفح:
```
https://hospital-call-system-xxxx.onrender.com/db-check.php
```

ستظهر صفحة نصية تحتوي على:
- إعدادات الـ DB
- محاولة الاتصال بـ Aiven MySQL
- إنشاء الجداول تلقائياً (16 جدول)
- إضافة البيانات الأولية (الأكواد، الأقسام، إعدادات الزيارة)

إذا رأيت ✅ في النهاية، فكل شيء جاهز.

#### 🚀 افتح النظام:

```
https://hospital-call-system-xxxx.onrender.com/
```

- اضغط **Launch System** — ستنتقل مباشرة للوحة التحكم
- جرّب Code Blue → ستسمع "Code Blue in Emergency Room"
- جرّب **Visiting Hours** في السايدبر

---

## 🔧 المتغيرات الكاملة (مرجع سريع)

كل القيم المطلوبة (من بيانات Aiven التي أعطيتني):

| المتغير | القيمة |
|---------|--------|
| `DB_HOST` | `hospital-call-system-nermenaalameri-1446.a.aivencloud.com` |
| `DB_PORT` | `23366` |
| `DB_USER` | `avnadmin` |
| `DB_PASS` | (كلمة مرور Aiven — `AVNS_...` التي أعطاك إياها Aiven) |
| `DB_NAME` | `defaultdb` |
| `DB_SSL`  | `1` |

> شهادة SSL (`ca.pem`) مُضمّنة في `api/ca.pem` ولا تحتاج لإضافتها يدوياً.

---

## ⚠️ استكشاف الأخطاء

### المشكلة: "Database offline" بعد النشر

1. افتح `/db-check.php` لرؤية التفاصيل
2. تأكد أن جميع متغيرات البيئة الست مُدخلة في Render → Environment
3. تأكد أن `DB_SSL=1` (مطلوب لـ Aiven)
4. انتظر دقيقة بعد التعديل ثم أعد فتح الصفحة

### المشكلة: لم يجد قاعدة البيانات `defaultdb`

- على Aiven، الـ database الافتراضي اسمه `defaultdb`
- لا تحاول إنشاء database جديد — Aiven Free لا يسمح بـ CREATE DATABASE
- إذا أعطاك خطأ، اضبط `DB_NAME=defaultdb`

### المشكلة: Cold Start (التطبيق نائم)

- Render Free tier ينام بعد 15 دقيقة بدون نشاط
- أول طلب بعد النوم يستغرق ~30 ثانية
- لتفادي هذا، ارفع لخطة Starter ($7/شهر)

### المشكلة: صوت الأكواد لا يعمل

- اضغط على أي مكان في الصفحة أولاً (سياسة autoplay في المتصفح)
- الصفحة تستخدم Web Audio API وتحتاج تفاعل المستخدم أولاً
- على Chrome: انقر على أيقونة الصوت في شريط العنوان واسمح بالصوت

### المشكلة: شهادة الـ SSL غير صحيحة

- تأكد أن ملف `api/ca.pem` موجود في المستودع (موجود بالفعل)
- إذا أردت التحقق، افتح:
  ```
  https://github.com/monawwerAlameri/hospital-call-system/blob/main/api/ca.pem
  ```

---

## ✅ قائمة تحقق نهائية

- [ ] مستودع GitHub public (مفعّل ✅)
- [ ] Aiven MySQL service running على Aiven
- [ ] كود محدّث في GitHub مع SSL support (مرفوع ✅)
- [ ] Render Web Service تم إنشاؤه
- [ ] DB_PASS مُدخل في Environment Variables
- [ ] `/db-check.php` يُظهر ✅
- [ ] الصفحة الرئيسية تعمل بدون أخطاء

---

## 🆘 إذا واجهت مشاكل

أرسل لي:
1. رابط Render الخاص بك
2. محتوى صفحة `/db-check.php`
3. أي رسائل خطأ من Render Logs

وسأساعدك فوراً.

---

## 📦 البدائل إذا فشل Render

### الخيار البديل 1: Railway.app
1. اذهب إلى https://railway.app
2. سجّل بحساب GitHub
3. **New Project → Deploy from GitHub repo → اختر `monawwerAlameri/hospital-call-system`**
4. اضغط **Variables** وأضف نفس المتغيرات الستة
5. Railway سيكتشف `Dockerfile` تلقائياً
6. بعد النشر، أضف `/db-check.php` للتحقق

### الخيار البديل 2: Koyeb
1. اذهب إلى https://koyeb.com
2. سجّل بحساب GitHub
3. **Create Service → GitHub → اختر المستودع**
4. اضبط متغيرات البيئة نفسها
5. النشر سيتم تلقائياً

### الخيار البديل 3: Self-hosting على VPS
1. استأجر VPS مجاني (مثلاً Oracle Cloud Free Tier)
2. انسخ المستودع: `git clone https://github.com/monawwerAlameri/hospital-call-system`
3. شغّل: `docker-compose up -d`
4. افتح المنفذ 8080

---

## 🎉 جاهز!

الآن لديك نظام نداءات المستشفى يعمل على الإنترنت بدون تكلفة. شارك رابط Render مع زملائك في المستشفى.
