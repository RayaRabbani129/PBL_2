# Tutorial Instalasi MatchGo dengan Laravel 12

## Prasyarat
- PHP 8.2+
- Composer
- Database (MySQL)
- Git

## Langkah Instalasi

### 1. Clone Repository
```bash
git clone 
cd matchgo
```

### 2. Install Dependencies PHP
```bash
composer install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306 (sesuaikan portmu)
DB_DATABASE=matchgo
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi Database
```bash
php artisan migrate
php artisan seed
```

### 6. Link Storage
```bash
php artisan storage:link
```

### 7. Jalankan Server
```bash
php artisan serve
```

Akses: `http://localhost:8000`

## Troubleshooting
- Pastikan folder `storage` dan `bootstrap/cache` writable
- Jalankan `php artisan cache:clear` jika ada error