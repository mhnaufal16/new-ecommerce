# Railway Deployment Setup Guide

## 🔧 Fix 500 Server Error

### 1. Set Environment Variables di Railway

Buka Railway Dashboard → Pilih service Laravel Anda → **Variables** tab

**WAJIB diisi:**

```env
# Application
APP_NAME="Ecommerce Store"
APP_ENV=production
APP_KEY=base64:COPY_DARI_COMMAND_DIBAWAH
APP_DEBUG=false
APP_URL=https://web-production-da7ed.up.railway.app

# Database (Auto-filled dari MySQL service)
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database

# Filesystem
FILESYSTEM_DISK=public

# Logging
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

### 2. Generate APP_KEY

Di terminal lokal Anda, jalankan:
```bash
php artisan key:generate --show
```

Copy hasilnya (contoh: `base64:abcd1234...`) dan paste ke Railway variable `APP_KEY`

### 3. Tambah MySQL Database

Di Railway dashboard:
1. Klik **"New"** → **"Database"** → **"Add MySQL"**
2. Tunggu sampai status "Active"
3. Variables database akan otomatis tersedia

### 4. Update Nixpacks Config

Pastikan file `nixpacks.toml` sudah benar (sudah saya buat sebelumnya)

### 5. Redeploy

Setelah semua variables di-set:
1. Klik **"Deploy"** atau push commit baru
2. Tunggu build selesai
3. Cek logs untuk memastikan migration berhasil

### 6. Troubleshooting

Jika masih error, cek **Deploy Logs** di Railway:
- Pastikan migration berhasil
- Cek error message di logs
- Pastikan semua environment variables terisi

### 7. Seed Data (Opsional)

Setelah deploy berhasil, untuk menambah data awal:
```bash
# Di Railway CLI atau via SSH
php artisan db:seed
```

---

**Catatan:** Railway free tier memiliki limit. Pastikan Anda sudah verify account untuk mendapat resource yang cukup.
