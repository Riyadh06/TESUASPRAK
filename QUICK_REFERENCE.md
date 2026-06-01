# 🚀 Quick Reference Guide - Backend-Frontend Integration

## 📱 Frontend (Vue.js) - Quick Start

### 1. Import Services
```javascript
import { authService, doctorService, appointmentService } from '@/api/services'
```

### 2. Login
```vue
<script setup>
import { authService } from '@/api/services'

async function handleLogin() {
  const response = await authService.login('email@example.com', 'password')
  console.log(response.user) // User data
  console.log(response.token) // Auth token
}
</script>
```

### 3. Fetch Data
```vue
<script setup>
import { doctorService } from '@/api/services'
import { onMounted, ref } from 'vue'

const doctors = ref([])

onMounted(async () => {
  const response = await doctorService.getAll()
  doctors.value = response.data
})
</script>
```

### 4. Create/Update
```vue
<script setup>
import { appointmentService } from '@/api/services'

async function bookAppointment() {
  const response = await appointmentService.create({
    doctor_id: 1,
    appointment_date: '2026-06-15 10:00',
    reason: 'Check-up'
  })
  console.log(response)
}

async function updateAppointment() {
  const response = await appointmentService.update(1, {
    status: 'confirmed'
  })
  console.log(response)
}
</script>
```

### 5. Delete
```javascript
await appointmentService.delete(1)
```

### 6. Use Auth Store
```vue
<script setup>
import { useAuthStore } from '@/stores/authStore'

const auth = useAuthStore()

// Login
await auth.login('email@example.com', 'password')

// Check if authenticated
if (auth.isAuthenticated) {
  console.log(auth.user.name)
}

// Logout
await auth.logout()
</script>
```

---

## 🛠️ Backend (Laravel) - API Reference

### Base URL
```
http://localhost:8000/api
```

### Authentication Header
```
Authorization: Bearer {token}
```

### Common Response Format
```json
{
  "data": { /* Resource data */ },
  "message": "Success message"
}
```

### Error Response
```json
{
  "message": "Error description",
  "errors": { /* Validation errors */ }
}
```

---

## 📊 API Endpoints Cheat Sheet

| Method | Endpoint | Auth | Purpose |
|--------|----------|------|---------|
| POST | `/register` | ✗ | Register new user |
| POST | `/login` | ✗ | Login user |
| POST | `/logout` | ✓ | Logout user |
| GET | `/doctors` | ✓ | List all doctors |
| GET | `/doctors/{id}` | ✓ | Get doctor details |
| POST | `/appointments` | ✓ | Book appointment |
| GET | `/appointments` | ✓ | List user appointments |
| DELETE | `/appointments/{id}` | ✓ | Cancel appointment |
| GET | `/medical-records` | ✓ | List medical records |
| POST | `/consultations` | ✓ | Start consultation |

---

## 💡 Code Snippets

### Handle Loading & Errors
```vue
<script setup>
import { ref } from 'vue'
import { doctorService } from '@/api/services'

const doctors = ref([])
const isLoading = ref(false)
const error = ref(null)

async function fetchDoctors() {
  isLoading.value = true
  error.value = null
  try {
    const response = await doctorService.getAll()
    doctors.value = response.data
  } catch (err) {
    error.value = err.response?.data?.message || 'An error occurred'
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div>
    <button @click="fetchDoctors" :disabled="isLoading">
      {{ isLoading ? 'Loading...' : 'Fetch Doctors' }}
    </button>
    
    <div v-if="error" class="error">{{ error }}</div>
    
    <div v-if="doctors.length">
      <div v-for="doctor in doctors" :key="doctor.id">
        {{ doctor.name }}
      </div>
    </div>
  </div>
</template>
```

### Form Validation
```vue
<script setup>
import { reactive, ref } from 'vue'
import { appointmentService } from '@/api/services'

const form = reactive({
  doctor_id: '',
  appointment_date: '',
  reason: ''
})

const errors = ref({})
const isSubmitting = ref(false)

async function submit() {
  errors.value = {}
  isSubmitting.value = true
  
  try {
    if (!form.doctor_id) errors.value.doctor_id = 'Doctor is required'
    if (!form.appointment_date) errors.value.appointment_date = 'Date is required'
    
    if (Object.keys(errors.value).length > 0) return
    
    await appointmentService.create(form)
    // Reset form
    form.doctor_id = ''
    form.appointment_date = ''
    form.reason = ''
  } catch (error) {
    errors.value = error.response?.data?.errors || {}
  } finally {
    isSubmitting.value = false
  }
}
</script>
```

### Protected Route Guard
```javascript
// router/index.js
import { useAuthStore } from '@/stores/authStore'

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else {
    next()
  }
})
```

### Interceptor Custom Header
```javascript
// api/axios.js
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  // Add custom headers if needed
  config.headers['X-Requested-With'] = 'XMLHttpRequest'
  return config
})
```

---

## 🔐 Token Management

### Save Token
```javascript
localStorage.setItem('token', response.data.token)
```

### Get Token
```javascript
const token = localStorage.getItem('token')
```

### Remove Token
```javascript
localStorage.removeItem('token')
```

### Check Authentication
```javascript
const isAuthenticated = !!localStorage.getItem('token')
```

---

## 🐛 Debugging Tips

### Check API Call
```javascript
// In browser console
const token = localStorage.getItem('token')
console.log('Token:', token)

// Inspect Network tab in DevTools
// Check request headers and response status
```

### Test Endpoint Manually
```bash
# List doctors
curl -X GET http://localhost:8000/api/doctors \
  -H "Authorization: Bearer YOUR_TOKEN"

# Create appointment
curl -X POST http://localhost:8000/api/appointments \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"doctor_id":1,"appointment_date":"2026-06-15 10:00","reason":"Check-up"}'
```

### Check Backend Logs
```bash
# Terminal running Laravel
php artisan serve
# Check output for errors
```

---

## 📋 File Locations

| File | Purpose |
|------|---------|
| `src/api/axios.js` | HTTP client config |
| `src/api/services.js` | API service wrappers |
| `src/stores/authStore.js` | Auth state management |
| `src/views/ExampleIntegration.vue` | Usage examples |
| `Backend/routes/api.php` | API routes definition |
| `Backend/config/cors.php` | CORS settings |

---

## 🎯 Common Tasks

### Task: Create Login Page
1. Import `authService` from services
2. Create reactive form with email/password
3. Call `authService.login()`
4. Store token in localStorage
5. Redirect to dashboard

### Task: Display Doctor List
1. Use `ref([])` for doctors list
2. Use `onMounted()` hook
3. Call `doctorService.getAll()`
4. Update ref with response data
5. Render with `v-for`

### Task: Book Appointment
1. Create form with doctor_id, date, reason
2. Call `appointmentService.create()`
3. Handle success/error
4. Show confirmation message

### Task: Add Protected Routes
1. Add `meta.requiresAuth: true` to routes
2. Create `beforeEach` guard in router
3. Check `authStore.isAuthenticated`
4. Redirect to login if not authenticated

---

## ✅ Pre-Launch Checklist

- [ ] Backend running on port 8000
- [ ] Frontend running on port 5173
- [ ] CORS configured in backend
- [ ] Token stored in localStorage
- [ ] API services imported correctly
- [ ] Error handling implemented
- [ ] Loading states shown
- [ ] Protected routes guarded
- [ ] Logout clears token
- [ ] Invalid token redirects to login

---

**Need Help?** Check SETUP_INTEGRATION.md or INTEGRATION_CHECKLIST.md
