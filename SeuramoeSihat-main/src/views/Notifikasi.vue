<template>
  <div class="min-h-screen bg-gray-50 pb-24">
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
      <div class="max-w-3xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
          <button
            @click="$router.back()"
            class="w-9 h-9 border border-gray-200 rounded-xl flex items-center justify-center hover:bg-gray-50 transition-colors"
          >
            <svg
              class="w-4 h-4 text-gray-600"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
              />
            </svg>
          </button>
          <span class="text-sm font-semibold text-gray-800">Notifikasi</span>
        </div>
        <button
          @click="tandaiSemuaDibaca"
          class="text-xs text-emerald-600 font-medium hover:text-emerald-700 transition-colors"
        >
          Tandai semua dibaca
        </button>
      </div>
    </nav>

    <div class="max-w-3xl mx-auto px-6 py-6">
      <!-- Filter -->
      <div class="flex gap-2 mb-6 overflow-x-auto pb-1 scrollbar-hide">
        <button
          v-for="f in filters"
          :key="f.key"
          @click="activeFilter = f.key"
          class="flex-shrink-0 text-xs px-4 py-2 rounded-full border transition-all duration-200"
          :class="
            activeFilter === f.key
              ? 'bg-emerald-600 text-white border-emerald-600'
              : 'bg-white text-gray-600 border-gray-200 hover:border-emerald-300'
          "
        >
          {{ f.label }}
          <span
            v-if="f.count > 0"
            class="ml-1 bg-white/30 text-white px-1.5 py-0.5 rounded-full text-xs"
            :class="activeFilter !== f.key ? 'bg-emerald-100 text-emerald-700' : ''"
          >
            {{ f.count }}
          </span>
        </button>
      </div>

      <!-- Notif list -->
      <div v-if="filteredNotif.length > 0" class="space-y-2">
        <!-- Group by tanggal -->
        <div v-for="group in groupedNotif" :key="group.tanggal">
          <p
            class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3 mt-5 first:mt-0"
          >
            {{ group.tanggal }}
          </p>
          <div class="space-y-2">
            <div
              v-for="notif in group.items"
              :key="notif.id"
              @click="klikNotif(notif)"
              class="bg-white rounded-2xl p-4 border transition-all duration-200 cursor-pointer flex gap-4"
              :class="
                !notif.dibaca
                  ? 'border-emerald-100 shadow-sm'
                  : 'border-gray-100 hover:border-gray-200'
              "
            >
              <!-- Icon -->
              <div
                class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl flex-shrink-0"
                :class="notif.bgClass"
              >
                {{ notif.icon }}
              </div>

              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <p class="text-sm font-semibold text-gray-800">{{ notif.judul }}</p>
                  <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs text-gray-400">{{ notif.waktu }}</span>
                    <span
                      v-if="!notif.dibaca"
                      class="w-2 h-2 bg-emerald-500 rounded-full flex-shrink-0"
                    ></span>
                  </div>
                </div>
                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ notif.pesan }}</p>
                <div class="flex gap-2 mt-2" v-if="notif.aksi">
                  <button
                    @click.stop="notif.aksiHandler && notif.aksiHandler()"
                    class="text-xs bg-emerald-600 text-white px-3 py-1.5 rounded-lg hover:bg-emerald-700 transition-colors"
                  >
                    {{ notif.aksi }}
                  </button>
                  <button
                    @click.stop="klikNotif(notif)"
                    class="text-xs border border-gray-200 text-gray-600 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors"
                  >
                    Lihat Detail
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty state -->
      <div v-else class="text-center py-20">
        <div class="text-5xl mb-4">🔔</div>
        <p class="text-gray-500 text-sm font-medium">Tidak ada notifikasi</p>
        <p class="text-gray-400 text-xs mt-1">Notifikasi baru akan muncul di sini</p>
      </div>
    </div>

    <BottomNav active="notifikasi" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import BottomNav from '../components/BottomNav.vue'
import Badge from '../components/ui/Badge.vue'

const router = useRouter()
const activeFilter = ref('semua')

const filters = [
  { key: 'semua', label: 'Semua', count: 3 },
  { key: 'antrian', label: 'Antrian', count: 1 },
  { key: 'chat', label: 'Chat', count: 1 },
  { key: 'kesehatan', label: 'Kesehatan', count: 1 },
  { key: 'sistem', label: 'Sistem', count: 0 },
]

const notifList = ref([
  {
    id: 1,
    kategori: 'antrian',
    dibaca: false,
    icon: '📋',
    bgClass: 'bg-emerald-50',
    judul: 'Antrian Anda hampir tiba!',
    pesan:
      'Nomor antrian 13 sedang dipanggil. Anda nomor 14 di Puskesmas Sigli. Segera menuju ruang tunggu.',
    waktu: '09.30',
    tanggal: 'Hari ini',
    aksi: 'Pantau Antrian',
    aksiHandler: () => router.push('/antrian'),
  },
  {
    id: 2,
    kategori: 'chat',
    dibaca: false,
    icon: '💬',
    bgClass: 'bg-blue-50',
    judul: 'Pesan dari dr. Rahmat Hidayat',
    pesan:
      'Baik, minum obat sesuai anjuran ya. Jika demam tidak turun dalam 2 hari, segera ke puskesmas 🙏',
    waktu: '09.22',
    tanggal: 'Hari ini',
    aksi: 'Balas Pesan',
    aksiHandler: () => router.push('/konsultasi'),
  },
  {
    id: 3,
    kategori: 'kesehatan',
    dibaca: false,
    icon: '💊',
    bgClass: 'bg-amber-50',
    judul: 'Pengingat Minum Obat',
    pesan: 'Saatnya minum Paracetamol 500mg — dosis ke-2 hari ini. Jangan sampai terlewat!',
    waktu: '12.00',
    tanggal: 'Hari ini',
    aksi: null,
  },
  {
    id: 4,
    kategori: 'antrian',
    dibaca: true,
    icon: '✅',
    bgClass: 'bg-emerald-50',
    judul: 'Booking antrian berhasil',
    pesan:
      'Antrian #14 di Puskesmas Sigli dengan dr. Rahmat Hidayat pada 30 Mei 2026 pukul 08.00 telah dikonfirmasi.',
    waktu: '08.12',
    tanggal: 'Hari ini',
    aksi: null,
  },
  {
    id: 5,
    kategori: 'sistem',
    dibaca: true,
    icon: '🏥',
    bgClass: 'bg-purple-50',
    judul: 'Selamat datang di SeuramoeSihat!',
    pesan: 'Akun Anda berhasil dibuat. Mulai booking antrian dokter terdekat sekarang.',
    waktu: '07.00',
    tanggal: 'Kemarin',
    aksi: 'Cari Dokter',
    aksiHandler: () => router.push('/cari-dokter'),
  },
  {
    id: 6,
    kategori: 'kesehatan',
    dibaca: true,
    icon: '📄',
    bgClass: 'bg-blue-50',
    judul: 'Rekam medis diperbarui',
    pesan: 'dr. Rahmat Hidayat menambahkan catatan pemeriksaan terbaru ke rekam medis Anda.',
    waktu: '10.45',
    tanggal: 'Kemarin',
    aksi: 'Lihat Rekam Medis',
    aksiHandler: () => router.push('/rekam-medis'),
  },
])

const filteredNotif = computed(() => {
  if (activeFilter.value === 'semua') return notifList.value
  return notifList.value.filter((n) => n.kategori === activeFilter.value)
})

const groupedNotif = computed(() => {
  const groups = {}
  filteredNotif.value.forEach((n) => {
    if (!groups[n.tanggal]) groups[n.tanggal] = []
    groups[n.tanggal].push(n)
  })
  return Object.entries(groups).map(([tanggal, items]) => ({ tanggal, items }))
})

function klikNotif(notif) {
  notif.dibaca = true
}

function tandaiSemuaDibaca() {
  notifList.value.forEach((n) => (n.dibaca = true))
  filters[0].count = 0
}
</script>
