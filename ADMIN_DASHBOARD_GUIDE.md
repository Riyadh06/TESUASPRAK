# 👨‍💼 Admin Dashboard - Kelola Appointments

## 🎯 Fitur

Admin Dashboard memungkinkan admin untuk melihat dan mengelola semua appointment yang sudah dibooking oleh pasien.

### ✨ Fitur Utama

1. **View All Appointments**
   - Melihat semua appointment dalam satu dashboard
   - Data lengkap: pasien, dokter, tanggal/jam, alasan, status

2. **Statistics**
   - Total appointments
   - Pending appointments
   - Confirmed appointments
   - Completed appointments

3. **Search & Filter**
   - Cari berdasarkan nama/email pasien
   - Filter berdasarkan status (pending, confirmed, completed, cancelled)
   - Filter berdasarkan dokter

4. **Status Management**
   - Update status appointment langsung dari dashboard
   - Status: pending, confirmed, completed, cancelled

5. **Delete Appointments**
   - Hapus appointment yang sudah dibooking

---

## 📱 UI Components

### Header
- App logo
- Title "Admin Dashboard"
- Logout button

### Stats Cards
Menampilkan:
- Total Appointment
- Pending
- Confirmed
- Completed

### Filter & Search
- Search box untuk cari pasien
- Dropdown filter status
- Dropdown filter dokter

### Appointments Table
Kolom:
| Kolom | Deskripsi |
|-------|-----------|
| Pasien | Nama & email pasien |
| Dokter | Nama dokter & spesialisasi |
| Tanggal & Jam | Tanggal dan waktu appointment |
| Alasan | Alasan appointment |
| Status | Status appointment |
| Action | Update status atau hapus |

---

## 🔑 Test Credentials

### Admin Account
```
Email: admin@example.com
Password: password
```

### User Account (untuk booking)
```
Email: test@example.com
Password: password
```

---

## 🚀 Cara Menggunakan

### 1. Login sebagai Admin
- Buka home page: `http://localhost:5173`
- Klik "Masuk" atau langsung ke `/login`
- Masukkan email: `admin@example.com` dan password: `password`
- Klik "Login"

### 2. Akses Admin Dashboard
Setelah login, akan muncul tombol "Admin Dashboard" di navbar
- Klik "Admin Dashboard" untuk masuk ke halaman kelola appointments

### 3. Melihat Appointments
- Dashboard menampilkan semua appointments
- Lihat statistics di bagian atas
- Scroll untuk melihat tabel lengkap

### 4. Search & Filter
- Gunakan search box untuk cari pasien
- Gunakan dropdown untuk filter berdasarkan status/dokter
- Kombinasi search + filter untuk hasil yang lebih spesifik

### 5. Update Status
- Pilih status baru dari dropdown di kolom "Action"
- Status akan terupdate otomatis

### 6. Hapus Appointment
- Klik tombol "Hapus" di kolom "Action"
- Confirm untuk menghapus
- Appointment akan dihapus dari database

---

## 📊 Status Appointments

| Status | Warna | Arti |
|--------|-------|------|
| pending | Yellow | Menunggu konfirmasi |
| confirmed | Blue | Sudah dikonfirmasi |
| completed | Green | Sudah selesai |
| cancelled | Red | Sudah dibatalkan |

---

## 🔄 API Endpoints Used

### Get All Appointments (Admin)
```
GET /api/admin/appointments
Authentication: Required (Bearer Token)
Response: Paginated list of all appointments with user & doctor data
```

### Get All Doctors (untuk filter)
```
GET /api/doctors
Authentication: Not required
Response: List of doctors
```

### Update Appointment
```
PUT /api/appointments/{id}
Authentication: Required (Bearer Token)
Body: { status: "confirmed" } or other fields
Response: Updated appointment data
```

### Delete Appointment
```
DELETE /api/appointments/{id}
Authentication: Required (Bearer Token)
Response: Success message
```

---

## 📁 Files Involved

### Frontend
- `src/views/AdminDashboard.vue` - Admin dashboard component
- `src/router/index.js` - Route definition
- `src/api/services.js` - API services
- `src/stores/authStore.js` - Auth state
- `src/views/Home.vue` - Updated with admin link

### Backend
- `routes/api.php` - Admin endpoint route
- `app/Http/Controllers/AppointmentController.php` - getAll method
- `database/seeders/DatabaseSeeder.php` - Admin user seed

---

## 🎨 Design Features

- Modern UI dengan Tailwind CSS
- Responsive design (mobile-friendly)
- Real-time status update
- Smooth transitions & animations
- Loading states
- Error handling
- Empty state message

---

## 🔐 Security

- ✅ Authentication required (Bearer token)
- ✅ Admin-only access (dapat diimplementasikan)
- ✅ CORS protection
- ✅ XSS protection via Vue.js
- ✅ CSRF protection via Laravel Sanctum

---

## 📈 Future Enhancements

1. **Advanced Filters**
   - Filter by date range
   - Filter by time
   - Export to Excel/PDF

2. **Notifications**
   - Send SMS/Email notifikasi status change ke pasien
   - In-app notifications

3. **Analytics**
   - Chart appointments per day/week/month
   - Doctor performance metrics
   - Patient satisfaction ratings

4. **Scheduling**
   - Bulk update status
   - Schedule automated confirmations
   - Set appointment reminders

5. **Audit Log**
   - Track semua perubahan appointment
   - Siapa yang update, kapan, apa yang diubah

---

## ❓ Troubleshooting

### Admin Dashboard not loading?
- Pastikan sudah login sebagai user dengan role 'admin'
- Cek browser console untuk error messages
- Pastikan backend API sedang running

### Appointments tidak muncul?
- Refresh page (F5)
- Cek backend logs: `php artisan serve`
- Pastikan database sudah di-seed dengan data

### Search/Filter tidak bekerja?
- Pastikan input sudah benar
- Coba clear filter dan reload
- Cek backend response di Network tab

### Delete failed?
- Cek apakah appointment sedang digunakan
- Cek backend error logs
- Refresh page dan coba lagi

---

**Last Updated:** June 1, 2026
