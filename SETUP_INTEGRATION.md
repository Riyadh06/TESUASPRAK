# 🔗 Setup Integrasi Backend - Frontend

## Persyaratan
- PHP 8.2+
- Node.js 18+
- Composer
- npm atau yarn

## 🚀 Langkah-Langkah Setup

### 1. Setup Backend (Laravel)

```bash
cd Backend
composer install
php artisan key:generate
php artisan migrate
```

**Environment (.env):**
```
APP_NAME=Seuramoe Sihat
APP_ENV=local
APP_KEY=base64:rkf+MVE7XeoiIaB3V7+gyChnRy0+G7HzsNr9AbpgsBY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
```

**Jalankan Backend:**
```bash
cd Backend
php artisan serve
```
Server berjalan di: `http://localhost:8000`

---

### 2. Setup Frontend (Vue.js)

```bash
cd SeuramoeSihat-main
npm install
```

**Environment (.env):**
```
VITE_API_URL=http://localhost:8000/api
```

**Jalankan Frontend:**
```bash
npm run dev
```
Frontend berjalan di: `http://localhost:5173`

---

## ✅ Verifikasi Koneksi

### Cek CORS
```bash
curl -X OPTIONS http://localhost:8000/api/doctors \
  -H "Origin: http://localhost:5173" \
  -H "Access-Control-Request-Method: GET" \
  -v
```

### Test Login API
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
```

---

## 📝 API Endpoints

### Authentication
- `POST /api/register` - Register user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout (requires token)

### Doctors
- `GET /api/doctors` - List doctors
- `GET /api/doctors/{id}` - Get doctor details
- `GET /api/doctors/{id}/schedule` - Get doctor schedule
- `POST /api/doctors` - Create doctor (admin)
- `PUT /api/doctors/{id}` - Update doctor
- `DELETE /api/doctors/{id}` - Delete doctor

### Appointments
- `POST /api/appointments` - Book appointment
- `GET /api/appointments` - List user appointments
- `GET /api/appointments/{id}` - Get appointment details
- `PUT /api/appointments/{id}` - Update appointment
- `DELETE /api/appointments/{id}` - Cancel appointment

### Medical Records
- `GET /api/medical-records` - List medical records
- `POST /api/medical-records` - Create medical record
- `GET /api/medical-records/{id}` - Get medical record
- `PUT /api/medical-records/{id}` - Update medical record
- `DELETE /api/medical-records/{id}` - Delete medical record

### Consultations
- `POST /api/consultations` - Start consultation
- `GET /api/consultations` - List consultations
- `GET /api/consultations/{id}` - Get consultation details
- `PUT /api/consultations/{id}` - Update consultation
- `DELETE /api/consultations/{id}` - End consultation

---

## 🔐 Token Management

Token disimpan di `localStorage` dengan key `token`:
```javascript
// Login
localStorage.setItem('token', response.data.token)

// Logout
localStorage.removeItem('token')
```

Token otomatis ditambahkan ke header:
```
Authorization: Bearer <token>
```

---

## 🐛 Troubleshooting

### CORS Error
- Pastikan backend berjalan di `http://localhost:8000`
- Periksa `config/cors.php` - pastikan `localhost:5173` ada di allowed_origins
- Restart backend setelah mengubah config

### 401 Unauthorized
- Token mungkin expired atau invalid
- Cek token di localStorage
- Login kembali untuk mendapatkan token baru

### 404 Not Found
- Periksa URL endpoint (case-sensitive)
- Pastikan controller ada dan terdaftar di routes/api.php
- Cek migrations sudah dijalankan

---

## 📦 Struktur Project

```
Backend/
├── app/Http/Controllers/  # API Controllers
├── app/Models/            # Database Models
├── routes/api.php         # API Routes
├── config/cors.php        # CORS Config
└── database/              # Migrations & Seeders

SeuramoeSihat-main/
├── src/api/axios.js       # API Client
├── src/views/             # Vue Pages
├── src/components/        # Vue Components
└── src/stores/            # Pinia Stores
```

