<template>
  <div class="min-h-screen bg-gray-50">
    <!-- NAVBAR -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 bg-emerald-600 rounded-xl flex items-center justify-center">
            <span class="text-white">👨‍💼</span>
          </div>
          <span class="text-lg font-semibold text-gray-800">Admin Dashboard</span>
        </div>
        <button @click="logout" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition">
          Logout
        </button>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Appointment</h1>
        <p class="text-gray-600">Lihat dan kelola semua appointment pasien</p>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
          <p class="text-sm text-gray-600 mb-2">Total Appointment</p>
          <p class="text-3xl font-bold text-gray-900">{{ appointments.length }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
          <p class="text-sm text-gray-600 mb-2">Pending</p>
          <p class="text-3xl font-bold text-yellow-600">{{ pendingCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
          <p class="text-sm text-gray-600 mb-2">Confirmed</p>
          <p class="text-3xl font-bold text-blue-600">{{ confirmedCount }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
          <p class="text-sm text-gray-600 mb-2">Completed</p>
          <p class="text-3xl font-bold text-emerald-600">{{ completedCount }}</p>
        </div>
      </div>

      <!-- Filter & Search -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pasien</label>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Nama pasien..."
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
            <select
              v-model="filterStatus"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
            >
              <option value="">Semua Status</option>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter Dokter</label>
            <select
              v-model="filterDoctor"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
            >
              <option value="">Semua Dokter</option>
              <option v-for="doctor in doctorsList" :key="doctor.id" :value="doctor.id">
                {{ doctor.user.name }} ({{ doctor.spesialis }})
              </option>
            </select>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="isLoading" class="text-center py-12">
        <div class="inline-flex items-center gap-2">
          <div class="w-4 h-4 bg-emerald-600 rounded-full animate-bounce" style="animation-delay: 0s"></div>
          <div class="w-4 h-4 bg-emerald-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
          <div class="w-4 h-4 bg-emerald-600 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
        </div>
        <p class="text-gray-600 mt-4">Loading appointments...</p>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-xl p-6 text-red-700">
        {{ error }}
      </div>

      <!-- Appointments Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200 bg-gray-50">
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Pasien</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Dokter</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Tanggal & Jam</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Alasan</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
              <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="apt in filteredAppointments"
              :key="apt.id"
              class="border-b border-gray-200 hover:bg-gray-50 transition"
            >
              <td class="px-6 py-4">
                <div>
                  <p class="font-medium text-gray-900">{{ apt.user.name }}</p>
                  <p class="text-sm text-gray-500">{{ apt.user.email }}</p>
                </div>
              </td>
              <td class="px-6 py-4">
                <div>
                  <p class="font-medium text-gray-900">{{ apt.doctor.user.name }}</p>
                  <p class="text-sm text-gray-500">{{ apt.doctor.spesialis }}</p>
                </div>
              </td>
              <td class="px-6 py-4">
                <div>
                  <p class="font-medium text-gray-900">{{ formatDate(apt.appointment_date) }}</p>
                  <p class="text-sm text-gray-500">{{ formatTime(apt.appointment_date) }}</p>
                </div>
              </td>
              <td class="px-6 py-4">
                <p class="text-sm text-gray-600">{{ apt.reason || '-' }}</p>
              </td>
              <td class="px-6 py-4">
                <span
                  class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                  :class="{
                    'bg-yellow-100 text-yellow-800': apt.status === 'pending',
                    'bg-blue-100 text-blue-800': apt.status === 'confirmed',
                    'bg-emerald-100 text-emerald-800': apt.status === 'completed',
                    'bg-red-100 text-red-800': apt.status === 'cancelled',
                  }"
                >
                  {{ capitalizeStatus(apt.status) }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="flex gap-2">
                  <select
                    @change="updateStatus(apt.id, $event)"
                    class="text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    :value="apt.status"
                  >
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                  <button
                    @click="deleteAppointment(apt.id)"
                    class="text-sm px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition"
                  >
                    Hapus
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- No data -->
        <div v-if="filteredAppointments.length === 0" class="text-center py-12">
          <p class="text-gray-600">Tidak ada appointment yang ditemukan</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { appointmentService, doctorService } from '@/api/services'

const router = useRouter()
const authStore = useAuthStore()

// Check if user is admin, redirect if not
if (authStore.user?.role !== 'admin') {
  router.push('/')
}

const appointments = ref([])
const doctorsList = ref([])
const isLoading = ref(false)
const error = ref(null)
const searchQuery = ref('')
const filterStatus = ref('')
const filterDoctor = ref('')

// Stats
const pendingCount = computed(() => appointments.value.filter(a => a.status === 'pending').length)
const confirmedCount = computed(() => appointments.value.filter(a => a.status === 'confirmed').length)
const completedCount = computed(() => appointments.value.filter(a => a.status === 'completed').length)

// Filtered appointments
const filteredAppointments = computed(() => {
  return appointments.value.filter(apt => {
    const matchSearch =
      apt.user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      apt.user.email.toLowerCase().includes(searchQuery.value.toLowerCase())

    const matchStatus = !filterStatus.value || apt.status === filterStatus.value
    const matchDoctor = !filterDoctor.value || apt.doctor.id == filterDoctor.value

    return matchSearch && matchStatus && matchDoctor
  })
})

// Format date
function formatDate(dateString) {
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })
}

// Format time
function formatTime(dateString) {
  const date = new Date(dateString)
  return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
}

// Capitalize status
function capitalizeStatus(status) {
  const map = {
    pending: 'Menunggu',
    confirmed: 'Dikonfirmasi',
    completed: 'Selesai',
    cancelled: 'Dibatalkan',
  }
  return map[status] || status
}

// Load data
async function loadAppointments() {
  isLoading.value = true
  error.value = null
  try {
    const response = await appointmentService.getAll()
    appointments.value = response.data
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal memuat appointments'
    console.error(err)
  } finally {
    isLoading.value = false
  }
}

async function loadDoctors() {
  try {
    const response = await doctorService.getAll()
    doctorsList.value = response.data
  } catch (err) {
    console.error('Error loading doctors:', err)
  }
}

// Update status
async function updateStatus(appointmentId, event) {
  const newStatus = event.target.value
  try {
    await appointmentService.update(appointmentId, { status: newStatus })
    const apt = appointments.value.find(a => a.id === appointmentId)
    if (apt) {
      apt.status = newStatus
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal update status'
    console.error(err)
  }
}

// Delete appointment
async function deleteAppointment(appointmentId) {
  if (confirm('Yakin ingin menghapus appointment ini?')) {
    try {
      await appointmentService.delete(appointmentId)
      appointments.value = appointments.value.filter(a => a.id !== appointmentId)
    } catch (err) {
      error.value = err.response?.data?.message || 'Gagal menghapus appointment'
      console.error(err)
    }
  }
}

// Logout
async function logout() {
  try {
    await authStore.logout()
    router.push('/login')
  } catch (err) {
    console.error('Logout error:', err)
  }
}

// Lifecycle
let refreshInterval = null

onMounted(() => {
  loadAppointments()
  loadDoctors()
  
  // Auto-refresh appointments every 5 seconds for real-time updates
  refreshInterval = setInterval(() => {
    loadAppointments()
  }, 5000)
})

onUnmounted(() => {
  // Cleanup interval
  if (refreshInterval) {
    clearInterval(refreshInterval)
  }
})
</script>

<style scoped>
table tbody tr:hover {
  background-color: rgba(0, 0, 0, 0.02);
}
</style>
