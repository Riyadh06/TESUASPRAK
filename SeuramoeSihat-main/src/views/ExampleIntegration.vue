<template>
  <div class="example-page">
    <h1>Contoh Integrasi Backend-Frontend</h1>
    
    <!-- Auth Example -->
    <section class="example-section">
      <h2>1. Login Example</h2>
      <div class="form-group">
        <input v-model="loginForm.email" type="email" placeholder="Email">
        <input v-model="loginForm.password" type="password" placeholder="Password">
        <button @click="handleLogin" :disabled="isLoading">
          {{ isLoading ? 'Loading...' : 'Login' }}
        </button>
      </div>
      <div v-if="authError" class="error">{{ authError }}</div>
      <div v-if="authUser" class="success">
        Login berhasil! User: {{ authUser.name }}
      </div>
    </section>

    <!-- Doctors Example -->
    <section class="example-section">
      <h2>2. Daftar Dokter</h2>
      <button @click="fetchDoctors" :disabled="loadingDoctors">
        {{ loadingDoctors ? 'Loading...' : 'Muat Dokter' }}
      </button>
      <div v-if="doctorsError" class="error">{{ doctorsError }}</div>
      <div v-if="doctors.length > 0">
        <div v-for="doctor in doctors" :key="doctor.id" class="card">
          <h3>{{ doctor.name }}</h3>
          <p>Spesialisasi: {{ doctor.specialization }}</p>
          <p>Email: {{ doctor.email }}</p>
        </div>
      </div>
    </section>

    <!-- Appointments Example -->
    <section class="example-section">
      <h2>3. Buat Janji Temu</h2>
      <div class="form-group">
        <input v-model="appointmentForm.doctor_id" type="number" placeholder="Doctor ID">
        <input v-model="appointmentForm.appointment_date" type="datetime-local" placeholder="Tanggal & Waktu">
        <textarea v-model="appointmentForm.reason" placeholder="Alasan"></textarea>
        <button @click="bookAppointment" :disabled="creatingAppointment">
          {{ creatingAppointment ? 'Loading...' : 'Buat Janji' }}
        </button>
      </div>
      <div v-if="appointmentError" class="error">{{ appointmentError }}</div>
      <div v-if="appointmentSuccess" class="success">
        Janji temu berhasil dibuat!
      </div>
    </section>

    <!-- User Appointments Example -->
    <section class="example-section">
      <h2>4. Daftar Janji Temu Saya</h2>
      <button @click="fetchMyAppointments" :disabled="loadingAppointments">
        {{ loadingAppointments ? 'Loading...' : 'Muat Janji Temu' }}
      </button>
      <div v-if="appointmentsError" class="error">{{ appointmentsError }}</div>
      <div v-if="myAppointments.length > 0">
        <div v-for="apt in myAppointments" :key="apt.id" class="card">
          <h3>Janji Temu #{{ apt.id }}</h3>
          <p>Tanggal: {{ formatDate(apt.appointment_date) }}</p>
          <p>Alasan: {{ apt.reason }}</p>
          <p>Status: {{ apt.status }}</p>
          <button @click="cancelAppointment(apt.id)">Batalkan</button>
        </div>
      </div>
    </section>

    <!-- Logout -->
    <section class="example-section" v-if="authUser">
      <button @click="handleLogout" class="logout-btn">Logout</button>
    </section>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import {
  authService,
  doctorService,
  appointmentService,
} from '@/api/services'

const authStore = useAuthStore()

// Auth
const loginForm = reactive({ email: '', password: '' })
const authError = ref(null)
const authUser = ref(null)
const isLoading = ref(false)

// Doctors
const doctors = ref([])
const loadingDoctors = ref(false)
const doctorsError = ref(null)

// Appointments
const appointmentForm = reactive({
  doctor_id: '',
  appointment_date: '',
  reason: '',
})
const creatingAppointment = ref(false)
const appointmentError = ref(null)
const appointmentSuccess = ref(false)

const myAppointments = ref([])
const loadingAppointments = ref(false)
const appointmentsError = ref(null)

// Login Handler
async function handleLogin() {
  isLoading.value = true
  authError.value = null
  try {
    const response = await authService.login(loginForm.email, loginForm.password)
    authUser.value = response.user
    authStore.setUser(response.user)
  } catch (error) {
    authError.value = error.response?.data?.message || 'Login gagal'
  } finally {
    isLoading.value = false
  }
}

// Logout Handler
async function handleLogout() {
  try {
    await authStore.logout()
    authUser.value = null
    loginForm.email = ''
    loginForm.password = ''
  } catch (error) {
    console.error('Logout error:', error)
  }
}

// Fetch Doctors
async function fetchDoctors() {
  loadingDoctors.value = true
  doctorsError.value = null
  try {
    const response = await doctorService.getAll()
    doctors.value = response.data
  } catch (error) {
    doctorsError.value = error.response?.data?.message || 'Gagal memuat dokter'
  } finally {
    loadingDoctors.value = false
  }
}

// Book Appointment
async function bookAppointment() {
  creatingAppointment.value = true
  appointmentError.value = null
  appointmentSuccess.value = false
  try {
    await appointmentService.create(appointmentForm)
    appointmentSuccess.value = true
    appointmentForm.doctor_id = ''
    appointmentForm.appointment_date = ''
    appointmentForm.reason = ''
    setTimeout(() => {
      appointmentSuccess.value = false
    }, 3000)
  } catch (error) {
    appointmentError.value = error.response?.data?.message || 'Gagal membuat janji'
  } finally {
    creatingAppointment.value = false
  }
}

// Fetch My Appointments
async function fetchMyAppointments() {
  loadingAppointments.value = true
  appointmentsError.value = null
  try {
    const response = await appointmentService.getAll()
    myAppointments.value = response.data
  } catch (error) {
    appointmentsError.value = error.response?.data?.message || 'Gagal memuat janji'
  } finally {
    loadingAppointments.value = false
  }
}

// Cancel Appointment
async function cancelAppointment(id) {
  try {
    await appointmentService.cancel(id)
    myAppointments.value = myAppointments.value.filter(apt => apt.id !== id)
  } catch (error) {
    console.error('Gagal membatalkan janji:', error)
  }
}

// Helper Function
function formatDate(date) {
  return new Date(date).toLocaleString('id-ID')
}
</script>

<style scoped>
.example-page {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

.example-section {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  background-color: #f9f9f9;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 15px;
}

input,
textarea,
button {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

button {
  background-color: #007bff;
  color: white;
  cursor: pointer;
  border: none;
}

button:hover:not(:disabled) {
  background-color: #0056b3;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.logout-btn {
  background-color: #dc3545;
}

.logout-btn:hover {
  background-color: #c82333;
}

.card {
  background: white;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 15px;
  margin-bottom: 10px;
}

.card h3 {
  margin-top: 0;
}

.error {
  color: #d32f2f;
  padding: 10px;
  background-color: #ffebee;
  border-radius: 4px;
  margin-bottom: 10px;
}

.success {
  color: #388e3c;
  padding: 10px;
  background-color: #e8f5e9;
  border-radius: 4px;
  margin-bottom: 10px;
}
</style>
