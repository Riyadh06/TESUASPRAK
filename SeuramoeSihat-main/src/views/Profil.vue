<template>
  <div class="min-h-screen bg-gray-50 pb-24">
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
      <div class="max-w-3xl mx-auto px-6 py-4 flex items-center justify-between">
        <span class="text-lg font-semibold text-gray-800">Profil Saya</span>
        <button
          @click="editMode = !editMode"
          class="text-sm text-emerald-600 font-medium hover:text-emerald-700 transition-colors"
        >
          {{ editMode ? 'Simpan' : 'Edit Profil' }}
        </button>
      </div>
    </nav>

    <div class="max-w-3xl mx-auto px-6 py-8">
      <!-- Avatar & Name -->
      <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-5 text-center">
        <div
          class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center text-3xl mx-auto mb-4"
        >
          👤
        </div>
        <h2 class="text-lg font-bold text-gray-900">Muhammad Isra Aulia</h2>
        <p class="text-sm text-gray-500 mt-0.5">pasien@demo.com</p>
        <div class="flex items-center justify-center gap-3 mt-3">
          <span class="text-xs bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full"
            >12 Kunjungan</span
          >
          <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full">Pasien Aktif</span>
        </div>
      </div>

      <!-- Data Diri -->
      <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Data Diri</h3>
        <div class="space-y-4">
          <div v-for="field in fields" :key="field.label">
            <label class="text-xs text-gray-400 mb-1 block">{{ field.label }}</label>
            <input
              v-if="editMode"
              v-model="field.value"
              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-emerald-400 transition-colors"
            />
            <p v-else class="text-sm text-gray-700 bg-gray-50 rounded-xl px-4 py-2.5">
              {{ field.value }}
            </p>
          </div>
        </div>
      </div>

      <!-- Data Kesehatan -->
      <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-5">
        <h3 class="text-sm font-semibold text-gray-800 mb-4">Data Kesehatan</h3>
        <div class="grid grid-cols-2 gap-4">
          <div v-for="h in kesehatan" :key="h.label">
            <label class="text-xs text-gray-400 mb-1 block">{{ h.label }}</label>
            <input
              v-if="editMode"
              v-model="h.value"
              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-emerald-400 transition-colors"
            />
            <p v-else class="text-sm text-gray-700 bg-gray-50 rounded-xl px-4 py-2.5">
              {{ h.value }}
            </p>
          </div>
        </div>
      </div>

      <!-- Menu -->
      <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-5">
        <component
          :is="menu.to ? 'RouterLink' : 'button'"
          :to="menu.to"
          v-for="menu in menus"
          :key="menu.label"
          class="w-full flex items-center justify-between px-5 py-4 text-sm text-gray-700 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0"
        >
          <div class="flex items-center gap-3">
            <span class="text-lg">{{ menu.icon }}</span>
            {{ menu.label }}
          </div>
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            />
          </svg>
        </component>
      </div>

      <!-- Logout -->
      <button
        @click="logout"
        class="w-full py-3.5 text-sm font-medium bg-red-50 text-red-500 border border-red-100 rounded-2xl hover:bg-red-100 transition-colors"
      >
        🚪 Keluar dari Akun
      </button>
    </div>

    <BottomNav active="profil" />
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import BottomNav from '../components/BottomNav.vue'

const router = useRouter()
const editMode = ref(false)

const fields = reactive([
  { label: 'Nama Lengkap', value: 'Muhammad Isra Aulia' },
  { label: 'NIK', value: '1111xxxxxxxxxxxx' },
  { label: 'Nomor HP', value: '0812-xxxx-xxxx' },
  { label: 'Email', value: 'pasien@demo.com' },
  { label: 'Alamat', value: 'Jl. Banda Aceh, Sigli, Aceh' },
  { label: 'Tanggal Lahir', value: '1 Januari 2000' },
])

const kesehatan = reactive([
  { label: 'Golongan Darah', value: 'B' },
  { label: 'Berat Badan', value: '65 kg' },
  { label: 'Tinggi Badan', value: '170 cm' },
  { label: 'Alergi', value: 'Penisilin' },
])

const menus = [
  { icon: '🔔', label: 'Notifikasi', to: '/notifikasi' },
  { icon: '🔒', label: 'Ubah Password' },
  { icon: '📋', label: 'Riwayat Antrian' },
  { icon: '❓', label: 'Bantuan & FAQ' },
  { icon: '📞', label: 'Hubungi Kami' },
]

function logout() {
  localStorage.removeItem('token')
  router.push('/login')
}
</script>
