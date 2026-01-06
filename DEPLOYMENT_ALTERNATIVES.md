# Quick Demo Deployment Guide

## Opsi 1: Ngrok (Paling Cepat - 5 Menit) ✅

### Setup:
1. Download Ngrok: https://ngrok.com/download
2. Extract dan jalankan di folder manapun
3. Di terminal Laragon, jalankan:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
4. Di terminal lain, jalankan:
   ```bash
   ngrok http 8000
   ```
5. Copy URL yang muncul (contoh: `https://abc123.ngrok.io`)
6. Share URL tersebut untuk demo!

**Kelebihan:**
- ✅ Setup 5 menit
- ✅ Gratis
- ✅ Tidak perlu konfigurasi rumit
- ✅ Langsung bisa demo

**Kekurangan:**
- ❌ URL berubah setiap restart (kecuali pakai akun berbayar)
- ❌ Hanya untuk demo, bukan production

---

## Opsi 2: Heroku (Production-Ready)

### Setup:
1. Install Heroku CLI: https://devcenter.heroku.com/articles/heroku-cli
2. Login: `heroku login`
3. Create app: `heroku create nama-app-anda`
4. Add buildpack:
   ```bash
   heroku buildpacks:add heroku/php
   heroku buildpacks:add heroku/nodejs
   ```
5. Add MySQL: `heroku addons:create jawsdb:kitefin`
6. Set env vars:
   ```bash
   heroku config:set APP_KEY=base64/6i+IVnAz/kC2uPOKu6usOTHwZPnhiW2+VcbGwWQUMk=
   heroku config:set APP_ENV=production
   heroku config:set APP_DEBUG=false
   ```
7. Deploy: `git push heroku main`

---

## Opsi 3: Laravel Forge + DigitalOcean ($12/bulan)

Paling professional, 1-click deployment, tapi berbayar.

---

## Kesimpulan:

**Untuk demo cepat:** Pakai **Ngrok**
**Untuk production:** Pakai **Heroku** atau **Forge**

Railway terlalu banyak masalah untuk Laravel. Tidak worth it untuk troubleshoot lebih lanjut.
