# إعدادات البريد الإلكتروني لـ Password Reset

## المشكلة الحالية

إعدادات البريد في `.env` غير صحيحة:
- **MAIL_PORT=465** مع **MAIL_ENCRYPTION=tls** ❌

## الإعدادات الصحيحة

### الخيار 1: استخدام SSL مع Port 465 (موصى به)
```env
MAIL_MAILER=smtp
MAIL_HOST=mail.rc-store.ly
MAIL_PORT=465
MAIL_USERNAME=info@rc-store.ly
MAIL_PASSWORD=CZPKePTj2{[x
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@rc-store.ly
MAIL_FROM_NAME="${APP_NAME}"
```

### الخيار 2: استخدام TLS مع Port 587
```env
MAIL_MAILER=smtp
MAIL_HOST=mail.rc-store.ly
MAIL_PORT=587
MAIL_USERNAME=info@rc-store.ly
MAIL_PASSWORD=CZPKePTj2{[x
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@rc-store.ly
MAIL_FROM_NAME="${APP_NAME}"
```

## كيفية تطبيق الإصلاح على السيرفر

### 1. تعديل ملف .env على السيرفر
```bash
ssh restorec@ls52
cd public_html
nano .env
```

غير السطر:
```
MAIL_ENCRYPTION=tls
```

إلى:
```
MAIL_ENCRYPTION=ssl
```

احفظ بـ `Ctrl+O` ثم `Enter` ثم `Ctrl+X`

### 2. امسح الـ cache
```bash
php artisan config:clear
php artisan cache:clear
```

## اختبار إعدادات البريد

### 1. فحص الإعدادات
افتح في المتصفح:
```
https://rc-store.ly/test-email-config
```

### 2. إرسال بريد تجريبي
```
https://rc-store.ly/test-email-send?email=aishaaltery89@gmail.com
```

### 3. اختبار Password Reset لعميل محدد
```
https://rc-store.ly/test-password-reset/1
```
(استبدل 1 برقم ID العميل)

## مراقبة الـ Logs

جميع العمليات الآن تسجل بالتفصيل في:
```
storage/logs/laravel.log
```

لرؤية آخر الـ logs:
```bash
tail -f storage/logs/laravel.log
```

## ما تم إصلاحه

### 1. ApplicationController.php - reset_send()
- ✅ إضافة logs تفصيلية لكل خطوة
- ✅ استخدام `remember_token` بدلاً من Sanctum tokens
- ✅ معالجة أخطاء البريد بشكل منفصل
- ✅ رسائل خطأ واضحة للمستخدم

### 2. ApplicationController.php - reset_send_store()
- ✅ التحقق من الـ token بشكل صحيح (أمان!)
- ✅ إضافة logs لكل خطوة
- ✅ حذف الـ token بعد الاستخدام
- ✅ التحقق من صحة كلمة المرور (min:8)

### 3. SendResetLink.php Notification
- ✅ استخدام `remember_token` الصحيح
- ✅ إضافة log عند بناء البريد
- ✅ تحسين رسالة البريد

### 4. خطر أمني تم إصلاحه ⚠️
**المشكلة السابقة**: أي شخص يمكنه تغيير كلمة مرور أي حساب بدون token!

**الإصلاح**: الآن يتم التحقق من الـ token قبل السماح بتغيير كلمة المرور.

## الأخطاء الشائعة وحلولها

### خطأ: Connection refused
```
Check MAIL_HOST - تأكد أن السيرفر يسمح بالاتصالات الخارجية
```

### خطأ: Authentication failed
```
تحقق من MAIL_USERNAME و MAIL_PASSWORD
```

### خطأ: SSL/TLS error
```
تأكد من المطابقة:
- Port 465 → MAIL_ENCRYPTION=ssl
- Port 587 → MAIL_ENCRYPTION=tls
```

### خطأ: Could not instantiate mail function
```
تأكد من تفعيل sendmail على السيرفر
```

## Routes المؤقتة للاختبار

**تحذير**: هذه الـ routes تعمل فقط في وضع debug (`APP_DEBUG=true`)

- `/test-email-config` - عرض إعدادات البريد الحالية
- `/test-email-send?email=your@email.com` - إرسال بريد تجريبي
- `/test-password-reset/{customerId}` - اختبار عملية reset password كاملة

**يُنصح بحذف ملف `routes/test-email.php` بعد الانتهاء من الاختبار في الإنتاج.**
