<template>
  <div class="min-h-screen bg-gray-50">
    <!-- NAVBAR -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
      <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <RouterLink to="/" class="flex items-center gap-3">
          <div class="w-9 h-9 bg-emerald-600 rounded-xl flex items-center justify-center">
            <span class="text-white text-lg">🏥</span>
          </div>
          <span class="text-lg font-semibold text-gray-800">SeuramoeSihat</span>
        </RouterLink>
        <div class="flex items-center gap-3">
          <RouterLink to="/login" class="text-sm text-gray-600 hover:text-emerald-600 font-medium"
            >Masuk</RouterLink
          >
          <RouterLink
            to="/register"
            class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors"
            >Daftar</RouterLink
          >
        </div>
      </div>
    </nav>

    <!-- HEADER -->
    <div class="bg-white border-b border-gray-100 px-6 py-8">
      <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Cari Dokter</h1>
        <div class="flex gap-3 flex-col md:flex-row">
          <div
            class="flex-1 flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3"
          >
            <svg
              class="w-5 h-5 text-gray-400 flex-shrink-0"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
              />
            </svg>
            <input
              v-model="search"
              type="text"
              placeholder="Nama dokter, spesialis, atau faskes..."
              class="flex-1 text-sm text-gray-700 placeholder:text-gray-400 outline-none bg-transparent"
            />
          </div>
          <select
            v-model="selectedWilayah"
            class="border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-600 outline-none bg-white"
          >
            <option value="">Semua Wilayah</option>
            <option>Sigli</option>
            <option>Mila</option>
            <option>Grong-Grong</option>
            <option>Kembang Tanjong</option>
          </select>
        </div>
      </div>
    </div>

    <div class="max-w-6xl mx-auto px-6 py-8">
      <!-- Filter Chips -->
      <div class="flex gap-2 flex-wrap mb-6">
        <button
          v-for="f in filters"
          :key="f"
          @click="activeFilter = f"
          class="text-xs px-4 py-2 rounded-full border transition-all duration-200"
          :class="
            activeFilter === f
              ? 'bg-emerald-600 text-white border-emerald-600'
              : 'bg-white text-gray-600 border-gray-200 hover:border-emerald-300'
          "
        >
          {{ f }}
        </button>
      </div>

      <!-- Result count -->
      <div class="flex items-center justify-between mb-5">
        <p class="text-sm text-gray-500">
          Menampilkan
          <span class="font-medium text-gray-800">{{ filteredDokter.length }}</span> dokter
        </p>
        <select
          v-model="sortBy"
          class="text-xs border border-gray-200 rounded-lg px-3 py-2 outline-none bg-white text-gray-600"
        >
          <option value="rating">Rating tertinggi</option>
          <option value="kuota">Kuota tersedia</option>
          <option value="nama">Nama A-Z</option>
        </select>
      </div>

      <!-- Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div
          v-for="dokter in filteredDokter"
          :key="dokter.nama"
          class="bg-white rounded-2xl p-5 border border-gray-100 hover:border-emerald-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
        >
          <div class="flex items-start gap-4 mb-4">
            <div
              class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0"
              :style="{ background: dokter.avatarBg, color: dokter.avatarColor }"
            >
              {{ dokter.inisial }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-sm font-semibold text-gray-800">{{ dokter.nama }}</div>
              <div class="text-xs text-gray-500 mt-0.5">{{ dokter.spesialis }}</div>
              <div class="text-xs text-gray-400 mt-0.5">{{ dokter.faskes }}</div>
            </div>
            <Badge
              :variant="dokter.tersedia ? 'success' : 'destructive'"
              appearance="light"
              class="flex-shrink-0"
            >
              {{ dokter.tersedia ? 'Tersedia' : 'Penuh' }}
            </Badge>
          </div>
          <div class="flex items-center gap-2 mb-4 flex-wrap">
            <Badge variant="warning" appearance="light" size="sm">⭐ {{ dokter.rating }}</Badge>
            <Badge variant="secondary" appearance="light" size="sm">{{ dokter.jadwal }}</Badge>
            <Badge variant="info" appearance="outline" size="sm"
              >Sisa {{ dokter.kuota }} kuota</Badge
            >
          </div>
          <div class="flex gap-2">
            <RouterLink
              :to="'/dokter/' + dokter.id"
              class="flex-1 py-2.5 rounded-xl text-xs font-medium border border-emerald-200 text-emerald-600 hover:bg-emerald-50 transition-colors text-center"
            >
              Lihat Profil
            </RouterLink>
            <button
              :disabled="!dokter.tersedia"
              @click="bookingDokter(dokter)"
              class="flex-2 px-4 py-2.5 rounded-xl text-xs font-medium transition-all duration-200"
              :class="
                dokter.tersedia
                  ? 'bg-emerald-600 hover:bg-emerald-700 text-white'
                  : 'bg-gray-100 text-gray-400 cursor-not-allowed'
              "
            >
              {{ dokter.tersedia ? 'Booking' : 'Penuh' }}
            </button>
          </div>
        </div>
      </div>

      <div v-if="filteredDokter.length === 0" class="text-center py-20">
        <div class="text-4xl mb-4">🔍</div>
        <p class="text-gray-500 text-sm">Dokter tidak ditemukan</p>
        <p class="text-gray-400 text-xs mt-1">Coba ubah kata kunci atau filter</p>
      </div>
    </div>

    <!-- Bottom Nav Mobile -->
    <BottomNav active="cari" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import BottomNav from '../components/BottomNav.vue'
import Badge from '../components/ui/Badge.vue'

const router = useRouter()
const search = ref('')
const selectedWilayah = ref('')
const activeFilter = ref('Semua')
const sortBy = ref('rating')

const filters = ['Semua', 'Dokter Umum', 'Spesialis Anak', 'Gigi', 'Kandungan', 'Penyakit Dalam']

const dokters = [
  {
    id: 1,
    inisial: 'RH',
    nama: 'dr. Rahmat Hidayat',
    spesialis: 'Dokter Umum',
    faskes: 'Puskesmas Sigli',
    wilayah: 'Sigli',
    jadwal: '08.00–12.00',
    kuota: 6,
    rating: 4.9,
    pengalaman: '8 tahun',
    pasien: '1.200+',
    tersedia: true,
    kategori: 'Dokter Umum',
    avatarBg: '#E1F5EE',
    avatarColor: '#0F6E56',
  },
  {
    id: 2,
    inisial: 'SA',
    nama: 'dr. Siti Aisyah, Sp.A',
    spesialis: 'Spesialis Anak',
    faskes: 'Klinik Sehat Bersama',
    wilayah: 'Sigli',
    jadwal: '09.00–14.00',
    kuota: 3,
    rating: 4.8,
    pengalaman: '6 tahun',
    pasien: '980+',
    tersedia: true,
    kategori: 'Spesialis Anak',
    avatarBg: '#E6F1FB',
    avatarColor: '#185FA5',
  },
  {
    id: 3,
    inisial: 'HN',
    nama: 'dr. Harun Nasution',
    spesialis: 'Dokter Gigi',
    faskes: 'Puskesmas Mila',
    wilayah: 'Mila',
    jadwal: '10.00–13.00',
    kuota: 0,
    rating: 4.7,
    pengalaman: '5 tahun',
    pasien: '740+',
    tersedia: false,
    kategori: 'Gigi',
    avatarBg: '#FAEEDA',
    avatarColor: '#854F0B',
  },
  {
    id: 4,
    inisial: 'NF',
    nama: 'dr. Nadia Fitri, Sp.OG',
    spesialis: 'Kandungan',
    faskes: 'RS Umum Sigli',
    wilayah: 'Sigli',
    jadwal: '13.00–17.00',
    kuota: 4,
    rating: 4.9,
    pengalaman: '12 tahun',
    pasien: '2.100+',
    tersedia: true,
    kategori: 'Kandungan',
    avatarBg: '#FBEAF0',
    avatarColor: '#993556',
  },
  {
    id: 5,
    inisial: 'AM',
    nama: 'dr. Ahmad Marzuki, Sp.PD',
    spesialis: 'Penyakit Dalam',
    faskes: 'RS Umum Sigli',
    wilayah: 'Sigli',
    jadwal: '08.00–11.00',
    kuota: 2,
    rating: 4.8,
    pengalaman: '14 tahun',
    pasien: '1.800+',
    tersedia: true,
    kategori: 'Penyakit Dalam',
    avatarBg: '#EEEDFE',
    avatarColor: '#534AB7',
  },
  {
    id: 6,
    inisial: 'YS',
    nama: 'dr. Yusra Safrina',
    spesialis: 'Dokter Umum',
    faskes: 'Puskesmas Kembang Tanjong',
    wilayah: 'Kembang Tanjong',
    jadwal: '08.00–12.00',
    kuota: 8,
    rating: 4.6,
    pengalaman: '3 tahun',
    pasien: '560+',
    tersedia: true,
    kategori: 'Dokter Umum',
    avatarBg: '#E1F5EE',
    avatarColor: '#0F6E56',
  },
]

const filteredDokter = computed(() => {
  let hasil = dokters.filter((d) => {
    const matchSearch =
      !search.value ||
      d.nama.toLowerCase().includes(search.value.toLowerCase()) ||
      d.spesialis.toLowerCase().includes(search.value.toLowerCase())
    const matchFilter = activeFilter.value === 'Semua' || d.kategori === activeFilter.value
    const matchWilayah = !selectedWilayah.value || d.wilayah === selectedWilayah.value
    return matchSearch && matchFilter && matchWilayah
  })
  if (sortBy.value === 'rating') hasil.sort((a, b) => b.rating - a.rating)
  else if (sortBy.value === 'kuota') hasil.sort((a, b) => b.kuota - a.kuota)
  else if (sortBy.value === 'nama') hasil.sort((a, b) => a.nama.localeCompare(b.nama))
  return hasil
})

function bookingDokter(dokter) {
  router.push('/booking/' + dokter.id)
}
</script>
