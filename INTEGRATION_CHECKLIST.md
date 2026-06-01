# 📋 Backend-Frontend Integration Checklist

## ✅ Pre-flight Checklist

### Backend Prerequisites
- [ ] PHP 8.2+ installed
- [ ] Composer installed
- [ ] SQLite database configured
- [ ] `.env` file configured with `APP_URL=http://localhost:8000`

### Frontend Prerequisites
- [ ] Node.js 18+ installed
- [ ] npm or yarn installed
- [ ] `.env` file has `VITE_API_URL=http://localhost:8000/api`

---

## 🚀 Installation Steps

### Step 1: Setup Backend
```bash
cd Backend
composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

**Expected Output:**
```
INFO  Server running on [http://127.0.0.1:8000]
```

### Step 2: Setup Frontend
```bash
cd ../SeuramoeSihat-main
npm install
npm run dev
```

**Expected Output:**
```
  VITE v5.x.x  ready in xxx ms

  ➜  Local:   http://localhost:5173/
  ➜  press h to show help
```

---

## 🧪 Test Connectivity

### Test 1: Check CORS Headers
```bash
curl -i -X OPTIONS http://localhost:8000/api/doctors \
  -H "Origin: http://localhost:5173" \
  -H "Access-Control-Request-Method: GET"
```

**Expected Response:**
```
HTTP/1.1 200 OK
Access-Control-Allow-Origin: http://localhost:5173
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: *
```

### Test 2: Test Register API
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Expected Response:**
```json
{
  "user": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    ...
  },
  "token": "1|xxxxx..."
}
```

### Test 3: Test Login API
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### Test 4: Test Protected Route (Doctors)
```bash
curl -X GET http://localhost:8000/api/doctors \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🔍 Integration Points

### 1. Authentication Flow
```
Frontend (Login) 
  ↓
→ POST /api/login 
  ↓
Backend (Validate + Generate Token)
  ↓
← Response with Token
  ↓
Frontend (Store Token in localStorage)
  ↓
All subsequent requests include: Authorization: Bearer {token}
```

### 2. Data Fetching Flow
```
Frontend (Component Mount)
  ↓
→ GET /api/doctors (with token)
  ↓
Backend (Query Database)
  ↓
← JSON Response
  ↓
Frontend (Update UI)
```

### 3. Error Handling Flow
```
Frontend Request
  ↓
Response with 401 Unauthorized
  ↓
Interceptor detects 401
  ↓
Clear localStorage token
  ↓
Redirect to /login
```

---

## 🛠️ Using API Services in Components

### Example: Login
```vue
<script setup>
import { ref } from 'vue'
import { authService } from '@/api/services'

const email = ref('')
const password = ref('')

async function login() {
  try {
    const response = await authService.login(email.value, password.value)
    console.log('Success:', response)
  } catch (error) {
    console.error('Failed:', error.response?.data)
  }
}
</script>
```

### Example: Fetch Doctors
```vue
<script setup>
import { ref, onMounted } from 'vue'
import { doctorService } from '@/api/services'

const doctors = ref([])

onMounted(async () => {
  try {
    const response = await doctorService.getAll()
    doctors.value = response.data
  } catch (error) {
    console.error('Failed to fetch doctors:', error)
  }
})
</script>
```

### Example: Book Appointment
```vue
<script setup>
import { appointmentService } from '@/api/services'

async function bookAppointment(doctorId, date, reason) {
  try {
    const response = await appointmentService.create({
      doctor_id: doctorId,
      appointment_date: date,
      reason: reason
    })
    console.log('Appointment booked:', response)
  } catch (error) {
    console.error('Booking failed:', error.response?.data)
  }
}
</script>
```

---

## ⚠️ Common Issues & Solutions

### Issue: CORS Error
**Error Message:** `Access to XMLHttpRequest at 'http://localhost:8000/api/...' has been blocked`

**Solution:**
1. Check Backend is running on port 8000
2. Verify `config/cors.php` includes `http://localhost:5173`
3. Restart backend server
4. Clear browser cache

### Issue: 401 Unauthorized
**Error Message:** `401 Unauthorized`

**Solution:**
1. Token may be expired - login again
2. Token may be invalid - check localStorage
3. Ensure token is being sent in Authorization header
4. Check API route requires auth middleware

### Issue: 404 Not Found
**Error Message:** `404 Not Found`

**Solution:**
1. Check endpoint URL matches route definition
2. Verify HTTP method (GET, POST, PUT, DELETE)
3. Check route is not behind auth middleware if not authenticated
4. Restart backend

### Issue: Connection Refused
**Error Message:** `Failed to fetch` or `Connection refused`

**Solution:**
1. Check backend is running: `php artisan serve`
2. Check frontend is running: `npm run dev`
3. Verify ports (Backend: 8000, Frontend: 5173)
4. Check firewall settings

### Issue: Token Not Being Sent
**Problem:** Requests fail even though token exists

**Solution:**
1. Check localStorage has 'token' key
2. Verify axios interceptor is loaded
3. Check axios config in `/api/axios.js`
4. Browser DevTools → Network → Check Authorization header

---

## 📊 Project Structure After Setup

```
Backend/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── DoctorController.php
│   │   ├── AppointmentController.php
│   │   ├── ConsultationController.php
│   │   ├── MedicalRecordController.php
│   │   └── NotificationController.php
│   └── Models/
│       ├── User.php
│       ├── Doctor.php
│       ├── Appointment.php
│       └── ...
├── routes/
│   └── api.php (All endpoints defined here)
├── config/
│   └── cors.php (CORS settings)
└── database/
    ├── migrations/ (Schema)
    └── seeders/ (Test data)

SeuramoeSihat-main/
├── src/
│   ├── api/
│   │   ├── axios.js (HTTP client)
│   │   └── services.js (API wrappers)
│   ├── stores/
│   │   └── authStore.js (Auth state)
│   ├── views/
│   │   ├── ExampleIntegration.vue (Demo)
│   │   └── ... (Other pages)
│   ├── components/
│   │   └── ... (Reusable components)
│   └── router/
│       └── index.js (Route definitions)
```

---

## 🎓 Next Steps

1. **Create Login Page** - Use `ExampleIntegration.vue` as reference
2. **Create Doctor Listing** - Implement doctor selection UI
3. **Create Appointment Booking** - Form with date/time picker
4. **Add Navigation** - Protected routes for authenticated users
5. **Implement Loading States** - Show spinners during API calls
6. **Add Error Handling** - Display user-friendly error messages
7. **Setup Dark Mode** - Enhance UI/UX

---

## 📞 API Reference

### Authentication
- **Register**: `POST /api/register`
- **Login**: `POST /api/login`
- **Logout**: `POST /api/logout` (protected)

### Doctors
- **List**: `GET /api/doctors` (protected)
- **Show**: `GET /api/doctors/{id}` (protected)
- **Create**: `POST /api/doctors` (protected)
- **Update**: `PUT /api/doctors/{id}` (protected)
- **Delete**: `DELETE /api/doctors/{id}` (protected)

### Appointments
- **List**: `GET /api/appointments` (protected)
- **Show**: `GET /api/appointments/{id}` (protected)
- **Create**: `POST /api/appointments` (protected)
- **Update**: `PUT /api/appointments/{id}` (protected)
- **Cancel**: `DELETE /api/appointments/{id}` (protected)

### Medical Records
- **List**: `GET /api/medical-records` (protected)
- **Create**: `POST /api/medical-records` (protected)
- **Show**: `GET /api/medical-records/{id}` (protected)
- **Update**: `PUT /api/medical-records/{id}` (protected)
- **Delete**: `DELETE /api/medical-records/{id}` (protected)

### Consultations
- **List**: `GET /api/consultations` (protected)
- **Create**: `POST /api/consultations` (protected)
- **Show**: `GET /api/consultations/{id}` (protected)
- **Update**: `PUT /api/consultations/{id}` (protected)
- **End**: `DELETE /api/consultations/{id}` (protected)

---

Last Updated: June 1, 2026
