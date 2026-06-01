<template>
  <div class="min-h-screen bg-gray-50 pb-24">
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
      <div class="max-w-3xl mx-auto px-6 py-4 flex items-center justify-between">
        <RouterLink to="/" class="flex items-center gap-3">
          <div class="w-9 h-9 bg-emerald-600 rounded-xl flex items-center justify-center">
            <span class="text-white">🏥</span>
          </div>
          <span class="text-lg font-semibold text-gray-800">SeuramoeSihat</span>
        </RouterLink>
        <RouterLink to="/antrian" class="text-sm font-medium text-emerald-600"
          >+ Booking Baru</RouterLink
        >
      </div>
    </nav>

    <div class="max-w-3xl mx-auto px-6 py-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-6">Antrian Saya</h1>

      <!-- Tab -->
      <div class="flex bg-gray-100 rounded-xl p-1 mb-6">
        <button
          v-for="tab in tabs"
          :key="tab.key"
          @click="activeTab = tab.key"
          class="flex-1 py-2 text-xs font-medium rounded-lg transition-all duration-200"
          :class="activeTab === tab.key ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500'"
        >
          {{ tab.label }}
        </button>
      </div>

      <!-- AKTIF -->
      <div v-if="activeTab === 'aktif'">
        <!-- Live ticker -->
        <div
          class="bg-emerald-50 border border-emerald-100 rounded-2xl px-5 py-4 flex items-center gap-3 mb-5"
        >
          <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse flex-shrink-0"></span>
          <p class="text-sm text-emerald-800">
            Sedang memanggil nomor <strong>{{ nomorDipanggil }}</strong> — Anda nomor
            <strong>14</strong>, sisa <strong>{{ 14 - nomorDipanggil }}</strong> orang.
          </p>
        </div>

        <!-- Antrian Card -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-5 shadow-sm">
          <div class="bg-emerald-600 px-6 py-5 flex items-center justify-between">
            <div>
              <p class="text-emerald-100 text-xs mb-1">Antrian aktif hari ini</p>
              <p class="text-white text-lg font-bold">Puskesmas Sigli</p>
              <p class="text-emerald-200 text-xs mt-0.5">dr. Rahmat Hidayat — Dokter Umum</p>
            </div>
            <div class="bg-white rounded-xl px-4 py-3 text-center">
              <p class="text-xs text-emerald-600">Nomor Anda</p>
              <p class="text-3xl font-bold text-emerald-700">14</p>
            </div>
          </div>

          <div class="p-6">
            <!-- Progress -->
            <div class="mb-5">
              <div class="flex justify-between text-xs text-gray-500 mb-2">
                <span
                  >Dipanggil: <strong class="text-gray-800">{{ nomorDipanggil }}</strong></span
                >
                <span>Sisa {{ 14 - nomorDipanggil }} orang</span>
              </div>
              <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div
                  class="h-full bg-emerald-500 rounded-full transition-all duration-700"
                  :style="{ width: (nomorDipanggil / 14) * 100 + '%' }"
                ></div>
              </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-3 mb-5">
              <div class="bg-gray-50 rounded-xl p-3 text-center">
                <p class="text-sm font-bold text-gray-800">~{{ (14 - nomorDipanggil) * 7 }} mnt</p>
                <p class="text-xs text-gray-400 mt-0.5">Estimasi</p>
              </div>
              <div class="bg-gray-50 rounded-xl p-3 text-center">
                <p class="text-sm font-bold text-gray-800">09.45</p>
                <p class="text-xs text-gray-400 mt-0.5">Est. dipanggil</p>
              </div>
              <div class="bg-gray-50 rounded-xl p-3 text-center">
                <p class="text-sm font-bold text-gray-800">08.12</p>
                <p class="text-xs text-gray-400 mt-0.5">Jam daftar</p>
              </div>
            </div>

            <!-- Timeline -->
            <div class="space-y-3 mb-5">
              <div v-for="(tl, i) in timeline" :key="i" class="flex gap-3 items-start">
                <div class="flex flex-col items-center">
                  <div
                    class="w-3 h-3 rounded-full mt-0.5 flex-shrink-0"
                    :class="
                      tl.status === 'done'
                        ? 'bg-emerald-500'
                        : tl.status === 'current'
                          ? 'bg-emerald-500 ring-4 ring-emerald-100'
                          : 'bg-gray-200'
                    "
                  ></div>
                  <div v-if="i < timeline.length - 1" class="w-px h-6 bg-gray-100 mt-1"></div>
                </div>
                <div class="pb-1">
                  <p
                    class="text-xs font-medium"
                    :class="
                      tl.status === 'current'
                        ? 'text-emerald-600'
                        : tl.status === 'done'
                          ? 'text-gray-400'
                          : 'text-gray-300'
                    "
                  >
                    {{ tl.step }}
                  </p>
                  <p class="text-xs text-gray-300 mt-0.5">{{ tl.time }}</p>
                </div>
              </div>
            </div>

            <!-- Keluhan -->
            <div class="bg-gray-50 rounded-xl p-4 mb-5">
              <p class="text-xs font-medium text-gray-700 mb-1">Keluhan yang dilaporkan</p>
              <p class="text-xs text-gray-500 leading-relaxed">
                Demam 2 hari, batuk kering, dan sakit kepala.
              </p>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
              <button
                class="flex-1 py-2.5 text-xs font-medium border border-red-200 text-red-500 rounded-xl hover:bg-red-50 transition-colors"
              >
                Batalkan
              </button>
              <button
                class="flex-2 px-5 py-2.5 text-xs font-medium bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors flex items-center gap-2"
              >
                📱 Notif WhatsApp
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- RIWAYAT -->
      <div v-if="activeTab === 'riwayat'" class="space-y-4">
        <div
          v-for="r in riwayat"
          :key="r.tanggal"
          class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-emerald-200 transition-colors"
        >
          <div class="flex items-start justify-between mb-3">
            <div>
              <p class="text-sm font-semibold text-gray-800">{{ r.faskes }}</p>
              <p class="text-xs text-gray-500 mt-0.5">{{ r.dokter }}</p>
            </div>
            <span
              class="text-xs px-2 py-1 rounded-full"
              :class="
                r.status === 'Selesai' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600'
              "
            >
              {{ r.status }}
            </span>
          </div>
          <div class="flex items-center gap-3 text-xs text-gray-400 mb-3">
            <span>📅 {{ r.tanggal }}</span>
            <span>•</span>
            <span>🔢 No. {{ r.nomor }}</span>
          </div>
          <div v-if="r.diagnosa" class="bg-gray-50 rounded-xl px-4 py-3 mb-3">
            <p class="text-xs text-gray-500">
              <span class="font-medium text-gray-700">Diagnosa:</span> {{ r.diagnosa }}
            </p>
          </div>
          <button
            @click="$router.push('/cari-dokter')"
            class="w-full py-2 text-xs font-medium border border-emerald-200 text-emerald-600 rounded-xl hover:bg-emerald-50 transition-colors"
          >
            🔄 Booking Ulang
          </button>
        </div>
      </div>
    </div>

    <BottomNav active="antrian" />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import BottomNav from '../components/BottomNav.vue'
import Badge from '../components/ui/Badge.vue'

const activeTab = ref('aktif')
const nomorDipanggil = ref(10)
const tabs = [
  { key: 'aktif', label: 'Aktif' },
  { key: 'riwayat', label: 'Riwayat' },
]

const timeline = [
  { step: 'Daftar antrian berhasil', time: '08.12', status: 'done' },
  { step: 'Tiba di puskesmas', time: '08.45', status: 'done' },
  { step: 'Menunggu giliran', time: 'Sedang berlangsung', status: 'current' },
  { step: 'Pemeriksaan dokter', time: 'Menunggu...', status: 'pending' },
  { step: 'Selesai', time: '—', status: 'pending' },
]

const riwayat = [
  {
    faskes: 'Puskesmas Sigli',
    dokter: 'dr. Rahmat Hidayat',
    tanggal: '10 Mei 2026',
    nomor: '08',
    status: 'Selesai',
    diagnosa: 'ISPA ringan — Paracetamol, Ambroxol, Vitamin C',
  },
  {
    faskes: 'Klinik Sehat Bersama',
    dokter: 'dr. Siti Aisyah, Sp.A',
    tanggal: '22 April 2026',
    nomor: '03',
    status: 'Selesai',
    diagnosa: 'Diare akut — Oralit, Zinc, Probiotik',
  },
  {
    faskes: 'Puskesmas Mila',
    dokter: 'dr. Harun Nasution',
    tanggal: '5 April 2026',
    nomor: '11',
    status: 'Dibatalkan',
    diagnosa: null,
  },
]

let interval = null
onMounted(() => {
  interval = setInterval(() => {
    if (nomorDipanggil.value < 14) nomorDipanggil.value++
  }, 4000)
})
onUnmounted(() => clearInterval(interval))
</script>
