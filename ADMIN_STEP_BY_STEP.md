# 🎯 STEP-BY-STEP: Melihat Bookings Sebagai Admin

## 🚀 Langkah 1: Jalankan Backend

```bash
cd Backend
php artisan serve
```

**Expected Output:**
```
INFO  Server running on [http://127.0.0.1:8000].
```

✓ Backend siap di port **8000**

---

## 🚀 Langkah 2: Jalankan Frontend

Buka terminal baru:

```bash
cd SeuramoeSihat-main
npm run dev
```

**Expected Output:**
```
VITE v5.x.x  ready in xxx ms

  ➜  Local:   http://localhost:5173/
```

✓ Frontend siap di port **5173**

---

## 🚀 Langkah 3: Buka Website

Di browser, pergi ke:
```
http://localhost:5173
```

✓ Muncul halaman Home "SeuramoeSihat"

---

## 🚀 Langkah 4: Login Sebagai Admin

### Opsi A: Click Tombol "Masuk"
1. Di navbar, click tombol **"Masuk"**
2. Akan dibawa ke halaman login

### Opsi B: Langsung ke URL
```
http://localhost:5173/login
```

---

## 🚀 Langkah 5: Masukkan Credentials Admin

**Form Login:**
```
Email:    admin@example.com
Password: password
```

Inputnya:
1. Email field → ketik `admin@example.com`
2. Password field → ketik `password`
3. Click tombol **"Masuk"** / "Login"

---

## 🚀 Langkah 6: Tunggu Loading

- Loading spinner akan muncul beberapa detik
- Akan melakukan POST request ke `/api/login`
- Backend memvalidasi credentials
- Jika benar → token tersimpan di localStorage

---

## 🚀 Langkah 7: Lihat Admin Dashboard Link

Setelah login berhasil:

### Di Navbar (atas halaman)
Akan muncul:
```
┌─────────────────────────────────────┐
│  [Logo] SeuramoeSihat  │ [Admin Dashboard] [Logout] │
└─────────────────────────────────────┘
```

**Tombol baru yang muncul:**
- `Admin Dashboard` ← Click ini!
- `Logout`

✓ Sebelumnya hanya ada "Masuk" & "Daftar", sekarang ada "Admin Dashboard"

---

## 🚀 Langkah 8: Click "Admin Dashboard"

Di navbar, click tombol **"Admin Dashboard"**

Atau langsung pergi ke:
```
http://localhost:5173/admin/appointments
```

---

## 🚀 Langkah 9: Lihat Admin Dashboard

Halaman akan loading dan menampilkan:

### 👤 Header
```
👨‍💼 Admin Dashboard                [Logout]
```

### 📊 Statistics Cards
```
┌──────────┬──────────┬───────────┬───────────┐
│ Total    │ Pending  │ Confirmed │ Completed │
│ 5        │ 5        │ 0         │ 0         │
└──────────┴──────────┴───────────┴───────────┘
```

### 🔍 Filter & Search
```
┌─────────────────┬──────────────┬─────────────┐
│ Cari Pasien     │ Filter Status│ Filter Dokter│
│ (search box)    │ (dropdown)   │ (dropdown)   │
└─────────────────┴──────────────┴─────────────┘
```

### 📋 Appointments Table
```
┌────────────┬────────────┬──────────────────┬────────┬────────┬────────┐
│ Pasien     │ Dokter     │ Tanggal & Jam    │ Alasan │ Status │ Action │
├────────────┼────────────┼──────────────────┼────────┼────────┼────────┤
│ Test User  │ Dr. Doctor │ 01 Juni 2026     │ Check- │ Pending│ [Dropdown] │
│ (test@...) │ 1 (Umum)   │ 14:30            │ up     │        │ [Hapus] │
├────────────┼────────────┼──────────────────┼────────┼────────┼────────┤
│ Test User  │ Dr. Doctor │ 02 Juni 2026     │ Check- │ Pending│ [Dropdown] │
│ (test@...) │ 2 (Gigi)   │ 09:15            │ up     │        │ [Hapus] │
├────────────┼────────────┼──────────────────┼────────┼────────┼────────┤
│ ...        │ ...        │ ...              │ ...    │ ...    │ ...     │
└────────────┴────────────┴──────────────────┴────────┴────────┴────────┘
```

✓ **Ini adalah semua appointments yang sudah di-booking!**

---

## 🔍 Langkah 10: Gunakan Filter

### A. Search by Pasien Name
1. Di "Cari Pasien" field, ketik: `Test User`
2. Tabel akan filter hanya appointments dari "Test User"

### B. Filter by Status
1. Klik dropdown "Filter Status"
2. Pilih: `Pending`, `Confirmed`, `Completed`, atau `Cancelled`
3. Tabel akan filter hanya appointment dengan status itu

### C. Filter by Dokter
1. Klik dropdown "Filter Dokter"
2. Pilih dokter yang Anda inginkan
3. Tabel akan filter hanya appointments untuk dokter itu

---

## 📝 Langkah 11: Update Status Appointment

### Cara Update:
1. Lihat kolom "Action" di baris appointment
2. Klik dropdown (saat ini menunjukkan status "Pending")
3. Pilih status baru:
   - `Pending` - Menunggu konfirmasi
   - `Confirmed` - Sudah dikonfirmasi
   - `Completed` - Sudah selesai
   - `Cancelled` - Sudah dibatalkan
4. Status akan langsung terupdate di database!

### Contoh:
```
Baris: Test User - Dr. Doctor 1 - 01 Juni 2026

Sebelum:  [Pending ▼]
Klik dropdown → pilih "Confirmed"
Sesudah:  [Confirmed ▼]
```

✓ Status sudah berubah di backend!

---

## 🗑️ Langkah 12: Hapus Appointment

### Cara Hapus:
1. Di kolom "Action", click tombol **"Hapus"** (berwarna merah)
2. Browser akan tanya konfirmasi: "Yakin ingin menghapus appointment ini?"
3. Click "OK" untuk confirm
4. Appointment akan dihapus dari database & hilang dari tabel

---

## 📊 Langkah 13: Lihat Statistics

Di bagian atas dashboard:

```
Total: 5          ← Semua appointments
Pending: 5        ← Status Pending
Confirmed: 0      ← Status Confirmed
Completed: 0      ← Status Completed
```

Statistics akan **auto-update** ketika Anda:
- Update status appointment
- Delete appointment
- Apply filter

---

## 🚪 Langkah 14: Logout

Untuk keluar sebagai admin:

1. Click tombol **"Logout"** di navbar (atas kanan)
2. Akan redirect ke halaman login
3. Token dihapus dari localStorage

---

## 📝 Contoh Workflow

### Scenario: Pasien Booking Appointment

**Step 1: User Book (sebagai test@example.com)**
1. User login dengan test@example.com
2. Browse dokter di /cari-dokter
3. Click dokter → halaman detail
4. Click "Booking" → multi-step booking
5. Pilih dokter, tanggal, alasan
6. Submit booking
7. Appointment tersimpan di database dengan status "pending"

**Step 2: Admin Melihat (sebagai admin@example.com)**
1. Admin login dengan admin@example.com
2. Click "Admin Dashboard"
3. Lihat appointment baru di tabel (status: Pending)
4. Click update status → pilih "Confirmed"
5. Appointment status berubah jadi "Confirmed"
6. Appointment siap!

---

## ✅ Checklist

Sebelum memulai, pastikan:

- [ ] Backend running di port 8000
- [ ] Frontend running di port 5173
- [ ] Database sudah di-seed (ada test data)
- [ ] Admin user ada: admin@example.com / password
- [ ] Test user ada: test@example.com / password

---

## 🆘 Troubleshooting

### Admin Dashboard tidak muncul?
- ✓ Pastikan sudah login
- ✓ Cek navbar, apakah ada "Admin Dashboard" button
- ✓ Refresh page (F5)

### Data appointments tidak muncul?
- ✓ Pastikan backend API running
- ✓ Cek browser console (F12 → Console tab)
- ✓ Cek backend logs

### Update status tidak bekerja?
- ✓ Cek network tab (F12 → Network)
- ✓ Lihat apakah PUT request ke /api/appointments/{id} berhasil
- ✓ Cek backend logs untuk error

### Search/Filter tidak bekerja?
- ✓ Pastikan query sudah benar
- ✓ Try clear filter dan reload
- ✓ Cek apakah data sesuai dengan filter

---

## 📞 Support

Jika ada masalah:
1. Check documentation files (ADMIN_DASHBOARD_GUIDE.md)
2. Check backend logs (terminal Backend)
3. Check frontend console (F12)
4. Check network requests (F12 → Network)

---

**Status:** ✅ Ready to Use
**Date:** June 1, 2026
