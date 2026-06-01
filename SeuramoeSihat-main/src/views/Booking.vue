<template>
  <div class="min-h-screen bg-gray-50 pb-10">
    <!-- NAVBAR -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
      <div class="max-w-3xl mx-auto px-6 py-4 flex items-center gap-4">
        <button
          @click="$router.back()"
          class="w-9 h-9 border border-gray-200 rounded-xl flex items-center justify-center hover:bg-gray-50 transition-colors"
        >
          <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 19l-7-7 7-7"
            />
          </svg>
        </button>
        <span class="text-sm font-semibold text-gray-800">Booking Antrian</span>

        <!-- Step indicator -->
        <div class="ml-auto flex items-center gap-1.5">
          <div
            v-for="i in 3"
            :key="i"
            class="h-1.5 rounded-full transition-all duration-300"
            :class="[step >= i ? 'bg-emerald-600' : 'bg-gray-200', step === i ? 'w-6' : 'w-3']"
          />
        </div>
      </div>
    </nav>

    <div class="max-w-3xl mx-auto px-6 py-8">
      <!-- ===== STEP 1: Pilih Dokter & Jadwal ===== -->
      <div v-if="step === 1">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Pilih Dokter</h1>
        <p class="text-sm text-gray-500 mb-6">Pilih dokter dan jadwal yang tersedia</p>

        <!-- Info dokter terpilih -->
        <div
          v-if="dokterDipilih"
          class="bg-white rounded-2xl p-5 border border-emerald-200 mb-6 shadow-sm"
        >
          <div class="flex items-center gap-4">
            <div
              class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0"
              :style="{ background: dokterDipilih.avatarBg, color: dokterDipilih.avatarColor }"
            >
              {{ dokterDipilih.inisial }}
            </div>
            <div class="flex-1">
              <p class="text-sm font-semibold text-gray-800">{{ dokterDipilih.nama }}</p>
              <p class="text-xs text-gray-500">
                {{ dokterDipilih.spesialis }} — {{ dokterDipilih.faskes }}
              </p>
              <div class="flex gap-2 mt-2 flex-wrap">
                <Badge variant="success" appearance="light" size="sm">Tersedia</Badge>
                <Badge variant="warning" appearance="light" size="sm"
                  >⭐ {{ dokterDipilih.rating }}</Badge
                >
                <Badge variant="info" appearance="outline" size="sm"
                  >Sisa {{ dokterDipilih.kuota }} kuota</Badge
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Pilih dokter jika belum -->
        <div v-else class="space-y-3 mb-6">
          <p class="text-xs font-medium text-gray-600 mb-2">Pilih dokter:</p>
          <div
            v-for="d in dokterList"
            :key="d.id"
            @click="pilihDokter(d)"
            class="bg-white rounded-2xl p-4 border border-gray-100 hover:border-emerald-300 hover:shadow-sm transition-all duration-200 cursor-pointer flex items-center gap-4"
            :class="!d.tersedia ? 'opacity-50 pointer-events-none' : ''"
          >
            <div
              class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0"
              :style="{ background: d.avatarBg, color: d.avatarColor }"
            >
              {{ d.inisial }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-gray-800 truncate">{{ d.nama }}</p>
              <p class="text-xs text-gray-500">{{ d.spesialis }} — {{ d.faskes }}</p>
            </div>
            <Badge :variant="d.tersedia ? 'success' : 'destructive'" appearance="light" size="sm">
              {{ d.tersedia ? 'Tersedia' : 'Penuh' }}
            </Badge>
          </div>
        </div>

        <!-- Pilih Tanggal -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 mb-5">
          <p class="text-sm font-semibold text-gray-800 mb-4">Pilih Tanggal</p>
          <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
            <button
              v-for="tgl in tanggalList"
              :key="tgl.value"
              @click="tanggalDipilih = tgl.value"
              class="flex-shrink-0 flex flex-col items-center px-4 py-3 rounded-xl border text-xs transition-all duration-200 min-w-[60px]"
              :class="
                tanggalDipilih === tgl.value
                  ? 'bg-emerald-600 text-white border-emerald-600'
                  : 'bg-white text-gray-600 border-gray-200 hover:border-emerald-300'
              "
            >
              <span class="font-medium">{{ tgl.hari }}</span>
              <span class="text-lg font-bold mt-0.5">{{ tgl.tanggal }}</span>
              <span class="opacity-70">{{ tgl.bulan }}</span>
            </button>
          </div>
        </div>

        <!-- Pilih Jam -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 mb-6">
          <p class="text-sm font-semibold text-gray-800 mb-4">Pilih Jam</p>
          <div class="grid grid-cols-4 gap-2">
            <button
              v-for="jam in jamList"
              :key="jam.value"
              @click="jam.tersedia && (jamDipilih = jam.value)"
              class="py-2.5 rounded-xl text-xs font-medium border transition-all duration-200"
              :class="[
                !jam.tersedia
                  ? 'bg-gray-50 text-gray-300 border-gray-100 cursor-not-allowed'
                  : jamDipilih === jam.value
                    ? 'bg-emerald-600 text-white border-emerald-600'
                    : 'bg-white text-gray-600 border-gray-200 hover:border-emerald-300',
              ]"
            >
              {{ jam.value }}
            </button>
          </div>
        </div>

        <button
          @click="keStep2"
          :disabled="!dokterDipilih || !tanggalDipilih || !jamDipilih"
          class="w-full py-3.5 rounded-xl text-sm font-semibold transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-200"
        >
          Lanjut → Isi Data Keluhan
        </button>
      </div>

      <!-- ===== STEP 2: Isi Keluhan ===== -->
      <div v-if="step === 2">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Isi Data Keluhan</h1>
        <p class="text-sm text-gray-500 mb-6">
          Ceritakan keluhan Anda agar dokter bisa mempersiapkan pemeriksaan
        </p>

        <!-- Ringkasan booking -->
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-6">
          <div class="flex items-center gap-3">
            <div
              class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0"
              :style="{ background: dokterDipilih.avatarBg, color: dokterDipilih.avatarColor }"
            >
              {{ dokterDipilih.inisial }}
            </div>
            <div>
              <p class="text-sm font-semibold text-emerald-900">{{ dokterDipilih.nama }}</p>
              <p class="text-xs text-emerald-700">{{ tanggalDipilih }} • {{ jamDipilih }}</p>
            </div>
          </div>
        </div>

        <!-- Form keluhan -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 mb-5 space-y-5">
          <div>
            <label class="block text-xs font-medium text-gray-700 mb-2">
              Nama Pasien <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.nama"
              type="text"
              placeholder="Nama lengkap pasien"
              class="w-full h-10 border border-gray-200 rounded-xl px-4 text-sm text-gray-700 placeholder:text-gray-400 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all"
            />
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-700 mb-2">
              Nomor HP <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.noHp"
              type="tel"
              placeholder="08xxxxxxxxxx"
              class="w-full h-10 border border-gray-200 rounded-xl px-4 text-sm text-gray-700 placeholder:text-gray-400 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all"
            />
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-700 mb-2">
              Keluhan Utama <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="form.keluhan"
              rows="3"
              placeholder="Contoh: Demam 2 hari, batuk kering, sakit kepala..."
              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 placeholder:text-gray-400 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all resize-none"
            />
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-700 mb-2">Riwayat Alergi Obat</label>
            <input
              v-model="form.alergi"
              type="text"
              placeholder="Kosongkan jika tidak ada"
              class="w-full h-10 border border-gray-200 rounded-xl px-4 text-sm text-gray-700 placeholder:text-gray-400 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all"
            />
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-700 mb-3">Tipe Pasien</label>
            <div class="flex gap-3">
              <button
                v-for="tipe in ['Pasien Baru', 'Pasien Lama']"
                :key="tipe"
                @click="form.tipePasien = tipe"
                class="flex-1 py-2.5 rounded-xl text-xs font-medium border transition-all duration-200"
                :class="
                  form.tipePasien === tipe
                    ? 'bg-emerald-600 text-white border-emerald-600'
                    : 'bg-white text-gray-600 border-gray-200 hover:border-emerald-300'
                "
              >
                {{ tipe }}
              </button>
            </div>
          </div>
        </div>

        <div class="flex gap-3">
          <button
            @click="step = 1"
            class="flex-1 py-3 rounded-xl text-sm font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors"
          >
            ← Kembali
          </button>
          <button
            @click="keStep3"
            :disabled="!form.nama || !form.noHp || !form.keluhan"
            class="flex-2 px-8 py-3 rounded-xl text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-emerald-200"
          >
            Lanjut → Konfirmasi
          </button>
        </div>
      </div>

      <!-- ===== STEP 3: Konfirmasi ===== -->
      <div v-if="step === 3">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Konfirmasi Booking</h1>
        <p class="text-sm text-gray-500 mb-6">Periksa kembali detail booking Anda</p>

        <!-- Detail dokter -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-4">
          <div class="bg-emerald-600 px-5 py-4">
            <p class="text-emerald-100 text-xs mb-1">Dokter yang dipilih</p>
            <p class="text-white font-bold">{{ dokterDipilih.nama }}</p>
            <p class="text-emerald-200 text-xs mt-0.5">
              {{ dokterDipilih.spesialis }} — {{ dokterDipilih.faskes }}
            </p>
          </div>
          <div class="p-5 grid grid-cols-2 gap-4">
            <div>
              <p class="text-xs text-gray-400 mb-1">Tanggal</p>
              <p class="text-sm font-semibold text-gray-800">{{ tanggalDipilih }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-1">Jam</p>
              <p class="text-sm font-semibold text-gray-800">{{ jamDipilih }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-1">Estimasi Nomor</p>
              <p class="text-sm font-semibold text-gray-800">#{{ nomorEstimasi }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400 mb-1">Status</p>
              <Badge variant="success" appearance="light" size="sm">Tersedia</Badge>
            </div>
          </div>
        </div>

        <!-- Detail pasien -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 mb-4">
          <p class="text-sm font-semibold text-gray-800 mb-4">Data Pasien</p>
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-400">Nama</span>
              <span class="text-sm font-medium text-gray-800">{{ form.nama }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-400">Nomor HP</span>
              <span class="text-sm font-medium text-gray-800">{{ form.noHp }}</span>
            </div>
            <div class="flex items-start justify-between gap-4">
              <span class="text-xs text-gray-400 flex-shrink-0">Keluhan</span>
              <span class="text-sm text-gray-800 text-right">{{ form.keluhan }}</span>
            </div>
            <div class="flex items-center justify-between" v-if="form.alergi">
              <span class="text-xs text-gray-400">Alergi</span>
              <span class="text-sm font-medium text-gray-800">{{ form.alergi }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-400">Tipe Pasien</span>
              <Badge variant="secondary" appearance="light" size="sm">{{ form.tipePasien }}</Badge>
            </div>
          </div>
        </div>

        <!-- Notifikasi WA -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 mb-6">
          <label class="flex items-center gap-3 cursor-pointer">
            <div
              @click="form.notifWa = !form.notifWa"
              class="w-10 h-6 rounded-full transition-colors duration-200 relative flex-shrink-0"
              :class="form.notifWa ? 'bg-emerald-600' : 'bg-gray-200'"
            >
              <div
                class="w-4 h-4 bg-white rounded-full absolute top-1 transition-all duration-200"
                :class="form.notifWa ? 'left-5' : 'left-1'"
              />
            </div>
            <div>
              <p class="text-sm font-medium text-gray-800">Notifikasi WhatsApp</p>
              <p class="text-xs text-gray-400">Dapatkan update antrian via WhatsApp</p>
            </div>
          </label>
        </div>

        <!-- Error Message -->
        <div
          v-if="bookingError"
          class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4 text-sm text-red-700"
        >
          {{ bookingError }}
        </div>

        <div class="flex gap-3">
          <button
            @click="step = 2"
            class="flex-1 py-3 rounded-xl text-sm font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors"
          >
            ← Kembali
          </button>
          <button
            @click="konfirmasiBooking"
            :disabled="loadingBooking"
            class="flex-2 px-8 py-3 rounded-xl text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors disabled:opacity-50 shadow-lg shadow-emerald-200 flex items-center gap-2"
          >
            <span
              v-if="loadingBooking"
              class="w-4 h-4 border-2 border-white/50 border-t-transparent rounded-full animate-spin"
            />
            <span v-else>✅ Konfirmasi Booking</span>
          </button>
        </div>
      </div>

      <!-- ===== STEP 4: Sukses ===== -->
      <div v-if="step === 4" class="text-center py-10">
        <div
          class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl animate-bounce-once"
        >
          ✅
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Booking Berhasil!</h1>
        <p class="text-gray-500 text-sm mb-8 max-w-xs mx-auto">
          Antrian Anda sudah terdaftar. Pantau status antrian secara real-time.
        </p>

        <!-- Tiket -->
        <div
          class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-8 text-left shadow-sm"
        >
          <div class="bg-emerald-600 px-6 py-5 flex items-center justify-between">
            <div>
              <p class="text-emerald-100 text-xs mb-1">SeuramoeSihat</p>
              <p class="text-white font-bold text-lg">{{ dokterDipilih.faskes }}</p>
              <p class="text-emerald-200 text-xs mt-0.5">{{ dokterDipilih.nama }}</p>
            </div>
            <div class="bg-white rounded-xl px-5 py-3 text-center">
              <p class="text-xs text-emerald-600">Nomor Antrian</p>
              <p class="text-4xl font-bold text-emerald-700">#{{ nomorEstimasi }}</p>
            </div>
          </div>
          <div class="p-5 grid grid-cols-2 gap-4">
            <div>
              <p class="text-xs text-gray-400">Tanggal</p>
              <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ tanggalDipilih }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400">Jam</p>
              <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ jamDipilih }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400">Pasien</p>
              <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ form.nama }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-400">Status</p>
              <Badge variant="success" appearance="light" size="sm" class="mt-0.5">Terdaftar</Badge>
            </div>
          </div>
        </div>

        <div class="flex gap-3">
          <RouterLink
            to="/antrian"
            class="flex-1 py-3 rounded-xl text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors text-center shadow-lg shadow-emerald-200"
          >
            Pantau Antrian
          </RouterLink>
          <RouterLink
            to="/"
            class="flex-1 py-3 rounded-xl text-sm font-medium border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors text-center"
          >
            Kembali ke Beranda
          </RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Badge from '../components/ui/Badge.vue'
import { appointmentService } from '@/api/services'

const route = useRoute()
const router = useRouter()
const step = ref(1)
const loadingBooking = ref(false)
const bookingError = ref(null)

const dokterList = [
  {
    id: 1,
    inisial: 'RH',
    nama: 'dr. Rahmat Hidayat',
    spesialis: 'Dokter Umum',
    faskes: 'Puskesmas Sigli',
    kuota: 6,
    rating: '4.9',
    tersedia: true,
    avatarBg: '#E1F5EE',
    avatarColor: '#0F6E56',
  },
  {
    id: 2,
    inisial: 'SA',
    nama: 'dr. Siti Aisyah, Sp.A',
    spesialis: 'Spesialis Anak',
    faskes: 'Klinik Sehat Bersama',
    kuota: 3,
    rating: '4.8',
    tersedia: true,
    avatarBg: '#E6F1FB',
    avatarColor: '#185FA5',
  },
  {
    id: 3,
    inisial: 'HN',
    nama: 'dr. Harun Nasution',
    spesialis: 'Dokter Gigi',
    faskes: 'Puskesmas Mila',
    kuota: 0,
    rating: '4.7',
    tersedia: false,
    avatarBg: '#FAEEDA',
    avatarColor: '#854F0B',
  },
  {
    id: 4,
    inisial: 'NF',
    nama: 'dr. Nadia Fitri, Sp.OG',
    spesialis: 'Kandungan',
    faskes: 'RS Umum Sigli',
    kuota: 4,
    rating: '4.9',
    tersedia: true,
    avatarBg: '#FBEAF0',
    avatarColor: '#993556',
  },
  {
    id: 5,
    inisial: 'AM',
    nama: 'dr. Ahmad Marzuki, Sp.PD',
    spesialis: 'Penyakit Dalam',
    faskes: 'RS Umum Sigli',
    kuota: 2,
    rating: '4.8',
    tersedia: true,
    avatarBg: '#EEEDFE',
    avatarColor: '#534AB7',
  },
]

// Auto pilih dokter dari route param
const dokterDipilih = ref(dokterList.find((d) => d.id === Number(route.params.id)) || null)

const tanggalDipilih = ref('')
const jamDipilih = ref('')

// Generate 7 hari ke depan
const tanggalList = computed(() => {
  const hari = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']
  const bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des']
  return Array.from({ length: 7 }, (_, i) => {
    const d = new Date()
    d.setDate(d.getDate() + i)
    return {
      hari: i === 0 ? 'Hari ini' : hari[d.getDay()],
      tanggal: d.getDate(),
      bulan: bulan[d.getMonth()],
      value: `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`,
    }
  })
})

const jamList = [
  { value: '08.00', tersedia: true },
  { value: '08.30', tersedia: true },
  { value: '09.00', tersedia: false },
  { value: '09.30', tersedia: true },
  { value: '10.00', tersedia: true },
  { value: '10.30', tersedia: false },
  { value: '11.00', tersedia: true },
  { value: '11.30', tersedia: true },
]

const form = ref({
  nama: '',
  noHp: '',
  keluhan: '',
  alergi: '',
  tipePasien: 'Pasien Baru',
  notifWa: true,
})

const nomorEstimasi = computed(() => Math.floor(Math.random() * 10) + 5)

function pilihDokter(d) {
  dokterDipilih.value = d
}

function keStep2() {
  if (!dokterDipilih.value || !tanggalDipilih.value || !jamDipilih.value) return
  step.value = 2
  window.scrollTo(0, 0)
}

function keStep3() {
  if (!form.value.nama || !form.value.noHp || !form.value.keluhan) return
  step.value = 3
  window.scrollTo(0, 0)
}

function konfirmasiBooking() {
  loadingBooking.value = true
  bookingError.value = null
  
  try {
    // Convert date format: "1 Juni 2026" + "08.00" -> "2026-06-01 08:00:00"
    const bulanMap = {
      'Januari': '01', 'Jan': '01',
      'Februari': '02', 'Feb': '02',
      'Maret': '03', 'Mar': '03',
      'April': '04', 'Apr': '04',
      'Mei': '05', 'Mei': '05',
      'Juni': '06', 'Jun': '06',
      'Juli': '07', 'Jul': '07',
      'Agustus': '08', 'Agt': '08',
      'September': '09', 'Sep': '09',
      'Oktober': '10', 'Okt': '10',
      'November': '11', 'Nov': '11',
      'Desember': '12', 'Des': '12',
    }
    
    const [tanggal, bulan, tahun] = tanggalDipilih.value.split(' ')
    const [jam, menit] = jamDipilih.value.split('.')
    const bulanNum = bulanMap[bulan]
    const appointmentDateTime = `${tahun}-${bulanNum}-${String(tanggal).padStart(2, '0')} ${jam}:${menit}:00`
    
    // Validate data
    if (!dokterDipilih.value?.id) {
      throw new Error('Silakan pilih dokter')
    }
    
    // Create appointment
    appointmentService.create({
      doctor_id: dokterDipilih.value.id,
      appointment_date: appointmentDateTime,
      reason: form.value.keluhan || 'Konsultasi',
    }).then(() => {
      loadingBooking.value = false
      step.value = 4
      window.scrollTo(0, 0)
    }).catch((err) => {
      loadingBooking.value = false
      bookingError.value = err.response?.data?.message || 'Gagal membuat appointment'
      console.error('Booking error:', err)
    })
  } catch (err) {
    loadingBooking.value = false
    bookingError.value = err.message || 'Terjadi kesalahan'
    console.error('Booking error:', err)
  }
}
</script>
