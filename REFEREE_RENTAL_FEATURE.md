# Fitur Penyewaan Wasit (Referee Rental)

## Daftar Isi
1. [Gambaran Umum](#gambaran-umum)
2. [Instalasi & Setup](#instalasi--setup)
3. [Fitur Utama](#fitur-utama)
4. [Pengguna & Role](#pengguna--role)
5. [API Endpoints](#api-endpoints)
6. [Database Structure](#database-structure)
7. [Workflow Penggunaan](#workflow-penggunaan)

---

## Gambaran Umum

Fitur Penyewaan Wasit memungkinkan tim untuk menyewa wasit profesional untuk pertandingan mereka. Sistem ini terintegrasi penuh dengan fitur Matches dan menangani:

- ✅ Manajemen data wasit (profil, sertifikasi, tarif)
- ✅ Otomatis cek ketersediaan wasit berdasarkan jadwal
- ✅ Perhitungan biaya sewa otomatis
- ✅ Tracking status penyewaan (pending, confirmed, completed, cancelled)
- ✅ Update statistik wasit (total matches, rating)
- ✅ Integrasi dengan biaya pertandingan

---

## Instalasi & Setup

### 1. Jalankan Migrations

```bash
php artisan migrate
```

Migrations yang ditambahkan:
- `2026_06_01_000001_create_referees_table.php` - Tabel wasit
- `2026_06_01_000002_create_referee_rentals_table.php` - Tabel penyewaan

### 2. Jalankan Seeder (Opsional)

Untuk menambahkan data wasit sample:

```bash
php artisan db:seed --class=RefereeSeeder
```

Atau jalankan semua seeder:

```bash
php artisan db:seed
```

Ini akan membuat 6 wasit sample dengan berbagai level sertifikasi dan rating.

### 3. Akses Admin Panel

Setelah migrations berjalan, Anda dapat mengakses:
- **Wasit Management**: `/admin/referees`
- **Penyewaan Wasit**: `/admin/referee-rentals`

---

## Fitur Utama

### A. Manajemen Wasit (Referee Management)

#### Menu: Pertandingan > Wasit

**Fitur:**
- Tambah wasit baru dengan data lengkap
- Edit profil wasit (nama, telepon, email, kota, lokasi)
- Kelola sertifikasi (Basic, Intermediate, Advanced, Professional)
- Set tarif per jam (dalam Rupiah)
- Toggle status ketersediaan (Available/Not Available)
- Lihat statistik wasit (rating, total pertandingan)
- Filter berdasarkan kota, level sertifikasi, ketersediaan

**Form Data Wasit:**
```
Informasi Dasar:
├── Nama Wasit
├── Email
├── Nomor Telepon
└── Kota

Kualifikasi:
├── Tahun Pengalaman (numeric)
└── Level Sertifikasi (basic/intermediate/advanced/professional)

Tarif & Ketersediaan:
├── Tarif Per Jam (Rp)
└── Tersedia (toggle)

Statistik (read-only):
├── Rating (0-5)
└── Total Pertandingan
```

### B. Penyewaan Wasit (Referee Rental)

#### Menu: Pertandingan > Penyewaan Wasit

**Fitur:**
- Buat penyewaan wasit untuk pertandingan
- Sistem otomatis cek ketersediaan wasit
- Perhitungan biaya otomatis (jam × tarif)
- Update status penyewaan
- Cancel penyewaan (refund otomatis)
- Filter berdasarkan status, tanggal, wasit

**Form Penyewaan:**
```
Informasi Pertandingan:
└── Pilih Pertandingan (Match Code)

Data Wasit:
├── Pilih Wasit (dropdown auto-filter available)
└── Tarif/Jam (auto-fill dari profil wasit)

Jadwal Sewa:
├── Tanggal Sewa
├── Jam Mulai
└── Jam Selesai

Biaya (auto-calculate):
├── Tarif/Jam (Rp)
├── Total Jam
└── Total Biaya (Rp)

Status & Catatan:
├── Status (pending/confirmed/completed/cancelled)
└── Catatan (optional)
```

---

## Pengguna & Role

### Akses Filament Panel

- **Super Admin**: Full access ke semua fitur
- **Admin Field**: Access ke manajemen wasit & penyewaan
- **Auditor**: View-only access
- **Player**: Via API saja (lihat API Endpoints)

---

## API Endpoints

Semua endpoint memerlukan authentication (`auth:web`) dan merupakan bagian dari prefix `/referees`.

### 1. List All Available Referees

```http
GET /referees
```

**Query Parameters:**
- `search` (optional): Cari berdasarkan nama atau kota
- `city` (optional): Filter berdasarkan kota
- `certification_level` (optional): Filter berdasarkan level (basic/intermediate/advanced/professional)

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "name": "Bambang Sutrisno",
        "email": "bambang@referee.com",
        "phone": "081234567890",
        "certification_level": "professional",
        "hourly_rate": "200000.00",
        "is_available": true,
        "city": "Jakarta",
        "rating": 4.8,
        "total_matches_refereed": 45
      }
    ],
    "pagination": {...}
  }
}
```

### 2. Get Available Referees for a Match

```http
POST /referees/matches/{match}/available
```

**Parameters:**
- `match` (route): Match ID

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Bambang Sutrisno",
      "certification_level": "professional",
      "hourly_rate": 200000,
      "rating": 4.8,
      "city": "Jakarta"
    }
  ]
}
```

### 3. Get Referee Details

```http
GET /referees/{referee}
```

**Parameters:**
- `referee` (route): Referee ID

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Bambang Sutrisno",
    "email": "bambang@referee.com",
    "phone": "081234567890",
    "experience_years": 8,
    "certification_level": "professional",
    "hourly_rate": "200000.00",
    "is_available": true,
    "city": "Jakarta",
    "rating": 4.8,
    "total_matches_refereed": 45,
    "completed_matches": 40,
    "pending_rentals": 2,
    "confirmed_rentals": 3,
    "total_earnings": 8000000,
    "average_rating": 4.8
  }
}
```

### 4. Assign Referee to Match

```http
POST /referees/matches/{match}/assign
```

**Parameters:**
- `match` (route): Match ID

**Request Body:**
```json
{
  "referee_id": 1,
  "notes": "Wasit berpengalaman untuk pertandingan final"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Wasit berhasil ditugaskan",
  "data": {
    "id": 1,
    "match_id": 5,
    "referee_id": 1,
    "rental_date": "2026-06-15",
    "start_time": "19:00:00",
    "end_time": "20:30:00",
    "hourly_rate": "200000.00",
    "total_hours": 1.5,
    "rental_cost": "300000.00",
    "status": "pending",
    "notes": "Wasit berpengalaman untuk pertandingan final"
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Wasit tidak tersedia pada waktu pertandingan"
}
```

### 5. Remove Referee from Match

```http
DELETE /referees/matches/{match}/remove
```

**Parameters:**
- `match` (route): Match ID

**Response:**
```json
{
  "success": true,
  "message": "Wasit berhasil dihapus"
}
```

---

## Database Structure

### Referees Table

```sql
CREATE TABLE referees (
  id BIGINT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  email VARCHAR(255) UNIQUE NOT NULL,
  experience_years INT DEFAULT 0,
  certification_level ENUM('basic', 'intermediate', 'advanced', 'professional') DEFAULT 'basic',
  hourly_rate DECIMAL(10,2) DEFAULT 0,
  is_available BOOLEAN DEFAULT true,
  city VARCHAR(100),
  latitude DECIMAL(10,8),
  longitude DECIMAL(11,8),
  rating FLOAT DEFAULT 0,
  total_matches_refereed INT DEFAULT 0,
  notes TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  
  INDEX idx_is_available,
  INDEX idx_city,
  INDEX idx_certification_level
);
```

### RefereeRentals Table

```sql
CREATE TABLE referee_rentals (
  id BIGINT PRIMARY KEY,
  match_id BIGINT NOT NULL FOREIGN KEY (matches.id),
  referee_id BIGINT NOT NULL FOREIGN KEY (referees.id),
  rental_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  hourly_rate DECIMAL(10,2) NOT NULL,
  total_hours FLOAT DEFAULT 0,
  rental_cost DECIMAL(12,2) DEFAULT 0,
  status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
  notes TEXT,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  
  UNIQUE KEY (match_id, referee_id),
  INDEX idx_status,
  INDEX idx_rental_date
);
```

### Model Relationships

**Matches Model:**
```php
public function refereeRental() // One-to-One
{
    return $this->hasOne(RefereeRental::class);
}

public function referee() // One-to-One Through
{
    return $this->hasOneThrough(Referee::class, RefereeRental::class);
}
```

**Referee Model:**
```php
public function rentals() // One-to-Many
{
    return $this->hasMany(RefereeRental::class);
}
```

**RefereeRental Model:**
```php
public function match() // Belongs-to
{
    return $this->belongsTo(Matches::class);
}

public function referee() // Belongs-to
{
    return $this->belongsTo(Referee::class);
}
```

---

## Workflow Penggunaan

### Skenario 1: Admin Menambah Wasit Baru

1. Akses **Admin Panel** → **Pertandingan** → **Wasit**
2. Klik **Tambah Wasit**
3. Isi semua field (Nama, Email, Telepon, Sertifikasi, Tarif/Jam, Kota)
4. Klik **Simpan**
5. Wasit sekarang tersedia untuk disewa

### Skenario 2: Admin Menyewa Wasit untuk Pertandingan

#### Cara 1: Melalui Filament Panel

1. Akses **Admin Panel** → **Pertandingan** → **Penyewaan Wasit**
2. Klik **Tambah Penyewaan**
3. Pilih Pertandingan dari dropdown
4. Sistem otomatis akan:
   - Load waktu pertandingan (tanggal & durasi)
   - Filter wasit yang tersedia pada waktu tersebut
5. Pilih wasit dari dropdown (sudah terurut berdasarkan rating)
6. Sistem otomatis akan:
   - Set tarif dari profil wasit
   - Hitung total jam dan biaya
7. Set status ke "confirmed"
8. Klik **Simpan**
9. Biaya wasit otomatis ditambahkan ke total biaya pertandingan

#### Cara 2: Melalui API

```bash
# Get available referees
curl -X POST http://matchgo.local/referees/matches/5/available \
  -H "Authorization: Bearer {token}"

# Assign referee
curl -X POST http://matchgo.local/referees/matches/5/assign \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{
    "referee_id": 1,
    "notes": "Wasit berpengalaman"
  }'
```

### Skenario 3: Update Status Penyewaan

1. Akses **Admin Panel** → **Pertandingan** → **Penyewaan Wasit**
2. Cari penyewaan yang ingin diupdate
3. Klik tombol **Edit**
4. Ubah Status:
   - `pending` → `confirmed`: Wasit sudah dikonfirmasi
   - `confirmed` → `completed`: Pertandingan selesai
   - Ke `cancelled`: Batalkan penyewaan (refund otomatis)
5. Klik **Simpan**

### Skenario 4: Cancel Penyewaan

1. Akses penyewaan di **Admin Panel** → **Pertandingan** → **Penyewaan Wasit**
2. Klik **Edit** untuk penyewaan yang ingin dibatalkan
3. Ubah status menjadi `cancelled`
4. Klik **Simpan**
5. Sistem otomatis akan:
   - Menghapus biaya dari total pertandingan
   - Membuat wasit tersedia kembali
   - Update pencatatan

### Skenario 5: Lihat Detail Wasit & Statistiknya

#### Melalui Panel:
1. Akses **Admin Panel** → **Pertandingan** → **Wasit**
2. Klik nama wasit untuk melihat profile
3. Lihat rating, total pertandingan, dan informasi lainnya

#### Melalui API:
```bash
curl http://matchgo.local/referees/1 \
  -H "Authorization: Bearer {token}"
```

---

## Service: RefereeRentalService

Semua logika bisnis referee rental tersentralisasi dalam `App\Services\RefereeRentalService`.

### Method-Method Utama

```php
// Get available referees untuk match
getAvailableReferees(Matches $match): Collection

// Check ketersediaan wasit pada time slot spesifik
isRefereeAvailable(Referee $referee, string $date, string $startTime, string $endTime): bool

// Create penyewaan baru (auto-calculate cost)
createRefereeRental(Matches $match, Referee $referee, ?string $notes): RefereeRental

// Update status penyewaan
updateRentalStatus(RefereeRental $rental, string $status): RefereeRental

// Cancel penyewaan (refund otomatis)
cancelRental(RefereeRental $rental): RefereeRental

// Get statistik wasit
getRefereeStats(Referee $referee): array
```

### Contoh Penggunaan dalam Controller:

```php
public function assignReferee(Request $request, Matches $match)
{
    $referee = Referee::findOrFail($request->referee_id);
    
    // Check availability
    if (!$this->refereeService->isRefereeAvailable($referee, ...)) {
        return error('Wasit tidak tersedia');
    }
    
    // Create rental
    $rental = $this->refereeService->createRefereeRental($match, $referee);
    
    return response()->json([
        'success' => true,
        'data' => $rental
    ]);
}
```

---

## Testing

### Automated Tests

Run tests dengan:
```bash
php artisan test
```

### Manual Testing Checklist

- [ ] Tambah wasit baru
- [ ] Edit profil wasit
- [ ] Toggle ketersediaan wasit
- [ ] Cari wasit berdasarkan kota
- [ ] Buat penyewaan wasit
- [ ] Verify biaya auto-calculate
- [ ] Update status ke confirmed
- [ ] Complete penyewaan (check statistik update)
- [ ] Cancel penyewaan (check refund)
- [ ] Get available referees API
- [ ] Assign via API
- [ ] Check total cost di Match

---

## Troubleshooting

### Problem: Wasit tidak muncul di dropdown
**Solusi:**
- Pastikan wasit memiliki `is_available = true`
- Pastikan tidak ada penyewaan lain pada waktu yang sama
- Refresh halaman

### Problem: Biaya tidak ter-update di Match
**Solusi:**
- Pastikan RefereeRental `status = confirmed`
- Check di tabel `referee_rentals` bahwa `rental_cost` sudah ter-set
- Manual update Match.total_cost jika diperlukan

### Problem: Ketersediaan wasit tidak tepat
**Solusi:**
- Check timezone setting di `.env`
- Verify waktu pertandingan (`match_datetime` & `duration_minutes`)
- Check existing penyewaan di tabel `referee_rentals`

---

## Pengembangan Lebih Lanjut

Fitur yang bisa ditambahkan di masa depan:
- [ ] Rating system untuk wasit (dari team)
- [ ] Review & feedback dari team
- [ ] Penalty system (no-show, late arrival)
- [ ] Insurance/guarantee untuk wasit
- [ ] Multiple referees per match (main referee + reserve)
- [ ] Referee performance analytics
- [ ] Export laporan earning wasit
- [ ] Payment integration untuk wasit

---

## Kontribusi

Untuk mengupdate fitur ini, pastikan untuk:
1. Update migrations jika ada schema change
2. Update models dan relationships
3. Update Filament resources jika ada UI change
4. Update API endpoints jika ada behavior change
5. Run tests untuk verify changes

---

**Last Updated:** June 1, 2026
**Version:** 1.0
