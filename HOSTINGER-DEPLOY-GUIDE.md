# دليل رفع موقع مخيّم على Hostinger

## المتطلبات
- استضافة Hostinger (Business أو Premium أو VPS)
- PHP 8.3 أو أحدث
- قاعدة بيانات MySQL
- ملف `hostinger-deploy.zip` الموجود في مجلد المشروع

---

## الخطوة 1: فك ضغط ملف hostinger-deploy.zip

افتح الملف وستجد مجلدين:
```
hostinger-deploy/
├── mukhayyam/       ← ملفات التطبيق
└── public_html/     ← ملفات الموقع العامة
```

---

## الخطوة 2: رفع الملفات عبر hPanel

### أ) رفع مجلد `mukhayyam`
1. سجل دخولك على [hpanel.hostinger.com](https://hpanel.hostinger.com)
2. اذهب إلى **Files → File Manager**
3. انتقل إلى المجلد الرئيسي (Home Directory) — ليس `public_html`
4. ارفع مجلد `mukhayyam` كاملاً هنا

> هيكل المجلدات يجب أن يصبح:
> ```
> /home/u{رقمك}/
> ├── mukhayyam/     ← هنا ترفع مجلد التطبيق
> └── public_html/   ← هنا ترفع محتوى المجلد الثاني
> ```

### ب) رفع محتوى `public_html`
1. افتح مجلد `public_html` من ملف الضغط
2. ارفع **محتوياته** (وليس المجلد نفسه) داخل `public_html` على Hostinger
3. تأكد أن الملف `index.php` الجديد استبدل القديم

---

## الخطوة 3: إنشاء قاعدة البيانات

1. في hPanel، اذهب إلى **Databases → MySQL Databases**
2. أنشئ قاعدة بيانات جديدة — سجّل:
   - اسم قاعدة البيانات
   - اسم المستخدم
   - كلمة المرور
3. احفظ هذه المعلومات للخطوة التالية

---

## الخطوة 4: تعديل ملف `.env`

1. في File Manager، افتح `/home/u{رقمك}/mukhayyam/.env`
2. عدّل هذه القيم:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com          ← اسم نطاقك الحقيقي

DB_DATABASE=YOUR_DB_NAME                 ← اسم قاعدة البيانات
DB_USERNAME=YOUR_DB_USER                 ← اسم مستخدم قاعدة البيانات
DB_PASSWORD=YOUR_DB_PASSWORD             ← كلمة المرور
```

---

## الخطوة 5: ضبط PHP إلى 8.3

1. في hPanel، اذهب إلى **Advanced → PHP Configuration**
2. اختر **PHP 8.3**
3. احفظ التغييرات

---

## الخطوة 6: تشغيل أوامر Laravel (عبر Terminal)

> إذا كان استضافتك تدعم SSH Terminal (Business plan وما فوق):

```bash
# انتقل إلى مجلد التطبيق
cd ~/mukhayyam

# تشغيل migrations (إنشاء جداول قاعدة البيانات)
php artisan migrate --force

# تشغيل seeders إذا كان هناك بيانات أولية
php artisan db:seed --force

# مسح الكاش
php artisan config:cache
php artisan route:cache
php artisan view:cache

# إنشاء رابط التخزين
php artisan storage:link
```

> **إذا لم يكن Terminal متاحاً:** يمكنك إنشاء ملف PHP مؤقت في `public_html` لتشغيل الأوامر (راجع ملاحظة أسفل الصفحة)

---

## الخطوة 7: ضبط الصلاحيات

تأكد أن هذه المجلدات قابلة للكتابة (777 أو 775):
```
mukhayyam/storage/
mukhayyam/bootstrap/cache/
```

يمكن تغيير الصلاحيات من File Manager → انقر بزر اليمين → Permissions

---

## الخطوة 8: التحقق من الموقع

1. افتح نطاقك في المتصفح
2. يجب أن تظهر الصفحة الرئيسية لموقع مخيّم
3. إذا ظهر خطأ 500، تحقق من:
   - صحة قيم `.env`
   - صلاحيات مجلد `storage/`
   - إصدار PHP (يجب 8.3)

---

## ملاحظة: بديل Terminal (artisan via web)

إذا لم يكن SSH متاحاً، أنشئ ملف `public_html/run-artisan.php` مؤقتاً:

```php
<?php
// احذف هذا الملف فور الانتهاء!
$output = shell_exec('cd ' . dirname(__DIR__) . '/mukhayyam && php artisan migrate --force 2>&1');
echo '<pre>' . $output . '</pre>';
```

ثم افتح `https://your-domain.com/run-artisan.php` في المتصفح.
**احذف الملف فوراً بعد الانتهاء.**

---

## مشاكل شائعة وحلولها

| المشكلة | الحل |
|---------|------|
| خطأ 500 | تحقق من `.env` وصلاحيات `storage/` |
| الصور لا تظهر | شغّل `php artisan storage:link` |
| خطأ قاعدة البيانات | تحقق من DB_HOST — في Hostinger يكون `127.0.0.1` |
| الـ CSS لا يظهر | تأكد أن `APP_URL` يطابق نطاقك بالضبط |

---

*تم إعداد هذا الدليل تلقائياً لمشروع مخيّم*
