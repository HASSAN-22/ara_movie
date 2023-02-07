## Laravel movie and series template
## قالب لاراول فیلم و سریال 

---

<img src="./readmeImage/1.png">

---

### How install on localhost
### روش نصب روی لوکال هاست

First, import the database `mv.sql`

<span dir="rtl">
این دیتابیس را ایمپورت کنید <code>mv.sql</code>
</span>

Then enter the `backend` folder and open the `.env` file and set the following values

<span dir="rtl">
سپس وارد پوشه <code>backend</code> شوید و فایل <code>.env</code> را باز کنید و مقادیر زیر را تنظیم کنید
</span>

```dotenv
 APP_URL=http://localhost:8000
 DB_DATABASE=Your db
 DB_USERNAME=Your user
 DB_PASSWORD=Your pass
 SESSION_DOMAIN=.localhost
 OMDB_API=OMDB API KEY
```
Then in terminal run then command

سپس در ترمینال این دستورات را اجرا کنید
```dotenv
composer install
php artisan config:cache
php artisan serv
```

After this step, enter the ``front'' folder and open the `.env' file and set the following values

<span dir="rtl">
 بعد از این مرحله وارد فولدر <code>front</code> شوید و فایل <code>.env</code> را باز کنید و مقادیر زیر را تنظیم کنید
</span>

```dotenv
VUE_APP_API=http://localhost:8000
VUE_APP_URL=http://locahost:3000
```
Then in terminal run then command

سپس در ترمینال این دستورات را اجرا کنید
```php
npm install
npm run serve
```

Now go to this address and the site will open for you

حالا به این آدرس بروید و سایت برای شما باز می شود

```php
http://localhost:3000
```