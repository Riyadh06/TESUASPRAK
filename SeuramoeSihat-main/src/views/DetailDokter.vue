<template>
  <div class="min-h-screen bg-gray-50">
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
        <span class="text-sm font-semibold text-gray-800">Detail Dokter</span>
      </div>
    </nav>

    <div class="max-w-3xl mx-auto px-6 py-8">
      <!-- Profile Card -->
      <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-5">
        <div class="flex items-start gap-5">
          <div
            class="w-20 h-20 rounded-2xl flex items-center justify-center text-2xl font-bold flex-shrink-0"
            :style="{ background: dokter.avatarBg, color: dokter.avatarColor }"
          >
            {{ dokter.inisial }}
          </div>
          <div class="flex-1">
            <h1 class="text-lg font-bold text-gray-900">{{ dokter.nama }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ dokter.spesialis }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ dokter.faskes }}</p>
            <div class="flex items-center gap-3 mt-3 flex-wrap">
              <span class="text-xs bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full"
                >⭐ {{ dokter.rating }}</span
              >
              <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full"
                >{{ dokter.pengalaman }} pengalaman</span
              >
              <span class="text-xs bg-purple-50 text-purple-700 px-3 py-1 rounded-full"
                >{{ dokter.pasien }} pasien</span
              >
            </div>
          </div>
        </div>
      </div>

      <!-- Info Grid -->
      <div class="grid grid-cols-2 gap-4 mb-5">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
          <div class="text-2xl mb-2">🕐</div>
          <div class="text-xs text-gray-400 mb-1">Jadwal Praktik</div>
          <div class="text-sm font-semibold text-gray-800">{{ dokter.jadwal }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 text-center">
          <div class="text-2xl mb-2">🎟️</div>
          <div class="text-xs text-gray-400 mb-1">Kuota Tersisa</div>
          <div
            class="text-sm font-semibold"
            :class="dokter.kuota > 0 ? 'text-emerald-600' : 'text-red-500'"
          >
            {{ dokter.kuota > 0 ? dokter.kuota + ' slot' : 'Penuh' }}
          </div>
        </div>
      </div>

      <!-- About -->
      <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-5">
        <h2 class="text-sm font-semibold text-gray-800 mb-3">Tentang Dokter</h2>
        <p class="text-sm text-gray-500 leading-relaxed">{{ dokter.tentang }}</p>
      </div>

      <!-- Spesialisasi -->
      <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-5">
        <h2 class="text-sm font-semibold text-gray-800 mb-3">Keahlian</h2>
        <div class="flex flex-wrap gap-2">
          <span
            v-for="k in dokter.keahlian"
            :key="k"
            class="text-xs bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full border border-emerald-100"
          >
            {{ k }}
          </span>
        </div>
      </div>

      <!-- Ulasan -->
      <div class="bg-white rounded-2xl p-6 border border-gray-100 mb-24">
        <h2 class="text-sm font-semibold text-gray-800 mb-4">Ulasan Pasien</h2>
        <div class="space-y-4">
          <div
            v-for="ulasan in dokter.ulasan"
            :key="ulasan.nama"
            class="pb-4 border-b border-gray-50 last:border-0 last:pb-0"
          >
            <div class="flex items-center justify-between mb-1">
              <span class="text-sm font-medium text-gray-800">{{ ulasan.nama }}</span>
              <span class="text-xs text-yellow-500">{{ '⭐'.repeat(ulasan.bintang) }}</span>
            </div>
            <p class="text-xs text-gray-500 leading-relaxed">{{ ulasan.komentar }}</p>
            <span class="text-xs text-gray-300 mt-1 block">{{ ulasan.tanggal }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Fixed Bottom Button -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 p-4">
      <div class="max-w-3xl mx-auto">
        <button
          :disabled="!dokter.tersedia"
          @click="$router.push('/booking/' + dokter.id)"
          class="w-full py-3.5 rounded-xl text-sm font-semibold transition-all duration-200"
          :class="
            dokter.tersedia
              ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-200'
              : 'bg-gray-100 text-gray-400 cursor-not-allowed'
          "
        >
          {{ dokter.tersedia ? '📋 Booking Antrian Sekarang' : 'Kuota Habis Hari Ini' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Badge from '../components/ui/Badge.vue'

const route = useRoute()
const id = Number(route.params.id)

const allDokter = [
  {
    id: 1,
    inisial: 'RH',
    nama: 'dr. Rahmat Hidayat',
    spesialis: 'Dokter Umum',
    faskes: 'Puskesmas Sigli',
    jadwal: '08.00–12.00',
    kuota: 6,
    rating: '4.9',
    pengalaman: '8 tahun',
    pasien: '1.200+',
    tersedia: true,
    avatarBg: '#E1F5EE',
    avatarColor: '#0F6E56',
    tentang:
      'dr. Rahmat Hidayat adalah dokter umum berpengalaman yang telah melayani masyarakat Sigli selama lebih dari 8 tahun. Beliau dikenal ramah dan teliti dalam setiap pemeriksaan.',
    keahlian: [
      'Pemeriksaan Umum',
      'Demam & Flu',
      'Hipertensi',
      'Diabetes',
      'Kesehatan Anak',
      'Luka & Cedera',
    ],
    ulasan: [
      {
        nama: 'Ahmad S.',
        bintang: 5,
        komentar:
          'Dokternya sangat ramah dan penjelasannya mudah dipahami. Antrian juga cepat karena pakai SeuramoeSihat.',
        tanggal: '2 hari lalu',
      },
      {
        nama: 'Sari W.',
        bintang: 5,
        komentar: 'Sudah 3x berobat ke sini, selalu puas. Rekomendasikan banget!',
        tanggal: '1 minggu lalu',
      },
      {
        nama: 'Budi R.',
        bintang: 4,
        komentar: 'Pelayanan bagus, hanya waktu tunggu sedikit lama tapi wajar.',
        tanggal: '2 minggu lalu',
      },
    ],
  },
  {
    id: 2,
    inisial: 'SA',
    nama: 'dr. Siti Aisyah, Sp.A',
    spesialis: 'Spesialis Anak',
    faskes: 'Klinik Sehat Bersama',
    jadwal: '09.00–14.00',
    kuota: 3,
    rating: '4.8',
    pengalaman: '6 tahun',
    pasien: '980+',
    tersedia: true,
    avatarBg: '#E6F1FB',
    avatarColor: '#185FA5',
    tentang:
      'dr. Siti Aisyah adalah spesialis anak yang berdedikasi tinggi. Beliau memiliki pendekatan yang lembut dan sabar dalam menangani pasien anak-anak.',
    keahlian: ['Tumbuh Kembang', 'Imunisasi', 'Gizi Anak', 'Demam Anak', 'ISPA', 'Alergi'],
    ulasan: [
      {
        nama: 'Rina M.',
        bintang: 5,
        komentar: 'Anak saya selalu tenang diperiksa dr. Siti. Sangat sabar dan profesional.',
        tanggal: '3 hari lalu',
      },
      {
        nama: 'Doni A.',
        bintang: 5,
        komentar: 'Penjelasan tentang tumbuh kembang anak sangat detail. Terima kasih dok!',
        tanggal: '1 minggu lalu',
      },
    ],
  },
]

const dokter = computed(() => allDokter.find((d) => d.id === id) || allDokter[0])
</script>
