<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- NAVBAR -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
      <div class="max-w-3xl mx-auto px-6 py-4 flex items-center gap-4">
        <button
          @click="activeChat ? tutupChat() : $router.back()"
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

        <div v-if="activeChat" class="flex items-center gap-3 flex-1">
          <div
            class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0"
            :style="{ background: activeChat.avatarBg, color: activeChat.avatarColor }"
          >
            {{ activeChat.inisial }}
          </div>
          <div>
            <p class="text-sm font-semibold text-gray-800 leading-none">{{ activeChat.nama }}</p>
            <div class="flex items-center gap-1 mt-0.5">
              <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
              <span class="text-xs text-emerald-600">Online</span>
            </div>
          </div>
        </div>
        <span v-else class="text-sm font-semibold text-gray-800">Konsultasi Chat</span>

        <div v-if="activeChat" class="ml-auto flex gap-2">
          <button
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
                stroke-width="1.5"
                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
              />
            </svg>
          </button>
        </div>
      </div>
    </nav>

    <!-- LIST DOKTER (jika belum pilih chat) -->
    <div v-if="!activeChat" class="max-w-3xl mx-auto px-6 py-8 w-full flex-1">
      <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Konsultasi Chat</h1>
        <p class="text-sm text-gray-500">Tanya keluhan ringan langsung ke dokter</p>
      </div>

      <!-- Search -->
      <div
        class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-3 mb-6"
      >
        <svg
          class="w-4 h-4 text-gray-400 flex-shrink-0"
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
          v-model="searchDokter"
          type="text"
          placeholder="Cari dokter..."
          class="flex-1 text-sm text-gray-700 placeholder:text-gray-400 outline-none bg-transparent"
        />
      </div>

      <!-- Riwayat chat -->
      <div v-if="riwayatChat.length > 0" class="mb-6">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Riwayat Chat</p>
        <div class="space-y-2">
          <div
            v-for="chat in riwayatChat"
            :key="chat.id"
            @click="bukaChat(chat)"
            class="bg-white rounded-2xl p-4 border border-gray-100 hover:border-emerald-200 hover:shadow-sm transition-all duration-200 cursor-pointer flex items-center gap-4"
          >
            <div class="relative">
              <div
                class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0"
                :style="{ background: chat.avatarBg, color: chat.avatarColor }"
              >
                {{ chat.inisial }}
              </div>
              <span
                v-if="chat.online"
                class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full border-2 border-white"
              ></span>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between mb-0.5">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ chat.nama }}</p>
                <span class="text-xs text-gray-400 flex-shrink-0 ml-2">{{ chat.waktu }}</span>
              </div>
              <p class="text-xs text-gray-500 truncate">{{ chat.pesanTerakhir }}</p>
            </div>
            <div
              v-if="chat.unread > 0"
              class="w-5 h-5 bg-emerald-600 rounded-full flex items-center justify-center flex-shrink-0"
            >
              <span class="text-white text-xs font-bold">{{ chat.unread }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Semua dokter -->
      <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">
        Dokter Tersedia
      </p>
      <div class="space-y-2">
        <div
          v-for="dokter in filteredDokterChat"
          :key="dokter.id"
          @click="bukaChat(dokter)"
          class="bg-white rounded-2xl p-4 border border-gray-100 hover:border-emerald-200 hover:shadow-sm transition-all duration-200 cursor-pointer flex items-center gap-4"
        >
          <div class="relative">
            <div
              class="w-12 h-12 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0"
              :style="{ background: dokter.avatarBg, color: dokter.avatarColor }"
            >
              {{ dokter.inisial }}
            </div>
            <span
              v-if="dokter.online"
              class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full border-2 border-white"
            ></span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800">{{ dokter.nama }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ dokter.spesialis }}</p>
            <div class="flex gap-2 mt-1.5">
              <Badge
                :variant="dokter.online ? 'success' : 'secondary'"
                appearance="light"
                size="xs"
              >
                {{ dokter.online ? 'Online' : 'Offline' }}
              </Badge>
              <Badge variant="warning" appearance="light" size="xs">⭐ {{ dokter.rating }}</Badge>
            </div>
          </div>
          <div class="text-xs text-gray-400 text-right flex-shrink-0">
            <p>Resp.</p>
            <p class="font-medium text-gray-600">{{ dokter.responTime }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- CHAT ROOM -->
    <div v-if="activeChat" class="flex flex-col flex-1 max-w-3xl mx-auto w-full">
      <!-- Info dokter -->
      <div class="bg-white border-b border-gray-100 px-6 py-3 mx-0">
        <div class="flex items-center gap-3">
          <Badge variant="info" appearance="light" size="sm">{{ activeChat.spesialis }}</Badge>
          <Badge variant="secondary" appearance="light" size="sm">{{ activeChat.faskes }}</Badge>
          <span class="text-xs text-gray-400 ml-auto">Konsultasi ringan & cepat</span>
        </div>
      </div>

      <!-- Pesan -->
      <div
        class="flex-1 overflow-y-auto px-6 py-5 space-y-4"
        ref="chatContainer"
        style="max-height: calc(100vh - 220px)"
      >
        <!-- Tanggal -->
        <div class="text-center">
          <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Hari ini</span>
        </div>

        <!-- Bubble pesan -->
        <div
          v-for="(pesan, i) in pesanList"
          :key="i"
          class="flex"
          :class="pesan.dari === 'pasien' ? 'justify-end' : 'justify-start'"
        >
          <!-- Avatar dokter -->
          <div
            v-if="pesan.dari === 'dokter'"
            class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0 mr-2 mt-1"
            :style="{ background: activeChat.avatarBg, color: activeChat.avatarColor }"
          >
            {{ activeChat.inisial }}
          </div>

          <div class="max-w-[75%]">
            <!-- Typing indicator -->
            <div
              v-if="pesan.typing"
              class="bg-white border border-gray-100 rounded-2xl rounded-tl-sm px-4 py-3 flex gap-1 items-center"
            >
              <span
                v-for="j in 3"
                :key="j"
                class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"
                :style="{ animationDelay: j * 0.15 + 's' }"
              ></span>
            </div>

            <!-- Pesan normal -->
            <div
              v-else
              class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed"
              :class="
                pesan.dari === 'pasien'
                  ? 'bg-emerald-600 text-white rounded-tr-sm'
                  : 'bg-white border border-gray-100 text-gray-800 rounded-tl-sm'
              "
            >
              {{ pesan.teks }}
            </div>

            <p
              class="text-xs text-gray-400 mt-1"
              :class="pesan.dari === 'pasien' ? 'text-right' : 'text-left'"
            >
              {{ pesan.waktu }}
              <span v-if="pesan.dari === 'pasien'" class="ml-1">
                {{ pesan.dibaca ? '✓✓' : '✓' }}
              </span>
            </p>
          </div>
        </div>
      </div>

      <!-- Quick replies -->
      <div v-if="showQuickReply" class="px-6 pb-2 flex gap-2 overflow-x-auto scrollbar-hide">
        <button
          v-for="q in quickReplies"
          :key="q"
          @click="kirimPesan(q)"
          class="flex-shrink-0 text-xs bg-white border border-emerald-200 text-emerald-600 px-3 py-1.5 rounded-full hover:bg-emerald-50 transition-colors whitespace-nowrap"
        >
          {{ q }}
        </button>
      </div>

      <!-- Input pesan -->
      <div class="bg-white border-t border-gray-100 px-4 py-3 sticky bottom-0">
        <div class="flex items-end gap-3">
          <button
            class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-emerald-600 transition-colors flex-shrink-0"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="1.5"
                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
              />
            </svg>
          </button>
          <div
            class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl px-4 py-2.5 flex items-end gap-2 focus-within:border-emerald-400 focus-within:ring-2 focus-within:ring-emerald-100 transition-all"
          >
            <textarea
              v-model="inputPesan"
              @keydown.enter.prevent="kirimPesan()"
              placeholder="Ketik pesan..."
              rows="1"
              class="flex-1 text-sm text-gray-700 placeholder:text-gray-400 outline-none bg-transparent resize-none max-h-24"
              style="min-height: 24px"
            />
          </div>
          <button
            @click="kirimPesan()"
            :disabled="!inputPesan.trim()"
            class="w-10 h-10 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-200 rounded-xl flex items-center justify-center transition-all flex-shrink-0"
          >
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <BottomNav v-if="!activeChat" active="konsultasi" />
  </div>
</template>

<script setup>
import { ref, computed, nextTick, watch } from 'vue'
import Badge from '../components/ui/Badge.vue'
import BottomNav from '../components/BottomNav.vue'

const searchDokter = ref('')
const activeChat = ref(null)
const inputPesan = ref('')
const chatContainer = ref(null)
const showQuickReply = ref(true)

const quickReplies = [
  'Saya demam 2 hari',
  'Sakit kepala terus',
  'Minta rujukan',
  'Tanya hasil lab',
  'Konsultasi obat',
]

const dokterList = [
  {
    id: 1,
    inisial: 'RH',
    nama: 'dr. Rahmat Hidayat',
    spesialis: 'Dokter Umum',
    faskes: 'Puskesmas Sigli',
    rating: '4.9',
    online: true,
    responTime: '< 5 mnt',
    avatarBg: '#E1F5EE',
    avatarColor: '#0F6E56',
  },
  {
    id: 2,
    inisial: 'SA',
    nama: 'dr. Siti Aisyah, Sp.A',
    spesialis: 'Spesialis Anak',
    faskes: 'Klinik Sehat Bersama',
    rating: '4.8',
    online: true,
    responTime: '< 10 mnt',
    avatarBg: '#E6F1FB',
    avatarColor: '#185FA5',
  },
  {
    id: 3,
    inisial: 'NF',
    nama: 'dr. Nadia Fitri, Sp.OG',
    spesialis: 'Kandungan',
    faskes: 'RS Umum Sigli',
    rating: '4.9',
    online: false,
    responTime: '< 30 mnt',
    avatarBg: '#FBEAF0',
    avatarColor: '#993556',
  },
  {
    id: 4,
    inisial: 'AM',
    nama: 'dr. Ahmad Marzuki, Sp.PD',
    spesialis: 'Penyakit Dalam',
    faskes: 'RS Umum Sigli',
    rating: '4.8',
    online: true,
    responTime: '< 15 mnt',
    avatarBg: '#EEEDFE',
    avatarColor: '#534AB7',
  },
]

const riwayatChat = ref([
  {
    ...dokterList[0],
    pesanTerakhir: 'Baik, minum obat sesuai anjuran ya 🙏',
    waktu: '09.22',
    unread: 0,
  },
])

const filteredDokterChat = computed(() => {
  if (!searchDokter.value) return dokterList
  return dokterList.filter(
    (d) =>
      d.nama.toLowerCase().includes(searchDokter.value.toLowerCase()) ||
      d.spesialis.toLowerCase().includes(searchDokter.value.toLowerCase()),
  )
})

const pesanList = ref([])

const pesanAwal = {
  1: [
    {
      dari: 'dokter',
      teks: 'Assalamualaikum, selamat datang di SeuramoeSihat 🌿 Saya dr. Rahmat. Ada yang bisa saya bantu hari ini?',
      waktu: '09.00',
      dibaca: true,
    },
    {
      dari: 'pasien',
      teks: 'Waalaikumsalam dok, saya mau tanya soal demam yang sudah 2 hari',
      waktu: '09.01',
      dibaca: true,
    },
    {
      dari: 'dokter',
      teks: 'Baik, tolong ceritakan lebih detail. Suhu badan berapa? Ada gejala lain seperti batuk, pilek, atau sakit kepala?',
      waktu: '09.02',
      dibaca: true,
    },
  ],
  2: [
    {
      dari: 'dokter',
      teks: 'Halo, saya dr. Siti Aisyah, spesialis anak. Silakan ceritakan keluhan si kecil 😊',
      waktu: '10.00',
      dibaca: true,
    },
  ],
}

function bukaChat(dokter) {
  activeChat.value = dokter
  pesanList.value = pesanAwal[dokter.id] || [
    {
      dari: 'dokter',
      teks: `Halo, saya ${dokter.nama}. Ada yang bisa saya bantu?`,
      waktu: jamSekarang(),
      dibaca: true,
    },
  ]
  showQuickReply.value = true
  nextTick(() => scrollBawah())
}

function tutupChat() {
  activeChat.value = null
  pesanList.value = []
  inputPesan.value = ''
}

function kirimPesan(teks = null) {
  const isi = teks || inputPesan.value.trim()
  if (!isi) return

  pesanList.value.push({
    dari: 'pasien',
    teks: isi,
    waktu: jamSekarang(),
    dibaca: false,
  })
  inputPesan.value = ''
  showQuickReply.value = false
  nextTick(() => scrollBawah())

  // Simulasi dokter typing
  setTimeout(() => {
    pesanList.value.push({ dari: 'dokter', typing: true, waktu: '' })
    nextTick(() => scrollBawah())

    setTimeout(() => {
      const idx = pesanList.value.findIndex((p) => p.typing)
      if (idx !== -1) pesanList.value.splice(idx, 1)

      pesanList.value.push({
        dari: 'dokter',
        teks: balasDokter(isi),
        waktu: jamSekarang(),
        dibaca: true,
      })

      // centang dibaca
      pesanList.value.forEach((p) => {
        if (p.dari === 'pasien') p.dibaca = true
      })
      nextTick(() => scrollBawah())
    }, 2000)
  }, 800)
}

function balasDokter(pesan) {
  const p = pesan.toLowerCase()
  if (p.includes('demam'))
    return 'Untuk demam, pastikan minum air putih yang cukup minimal 2 liter/hari. Bisa konsumsi paracetamol 500mg jika suhu di atas 38°C. Jika demam lebih dari 3 hari, segera periksa ke faskes terdekat ya 🙏'
  if (p.includes('batuk'))
    return 'Batuk bisa disebabkan banyak hal. Hindari minuman dingin, madu hangat + jahe bisa membantu. Jika batuk berdahak atau lebih dari 2 minggu, perlu diperiksa langsung.'
  if (p.includes('sakit kepala') || p.includes('pusing'))
    return 'Sakit kepala bisa karena kurang tidur, dehidrasi, atau tekanan darah. Coba istirahat cukup dan minum air putih. Jika sangat mengganggu, konsumsi paracetamol sesuai dosis.'
  if (p.includes('rujukan'))
    return 'Untuk mendapatkan surat rujukan, Anda perlu datang langsung ke puskesmas dan diperiksa terlebih dahulu. Saya bisa bantu jadwalkan booking antrian.'
  if (p.includes('obat'))
    return 'Konsumsi obat harus sesuai resep dokter ya. Jangan menghentikan obat tanpa konsultasi, terutama untuk antibiotik. Ada obat spesifik yang ingin ditanyakan?'
  return 'Terima kasih atas informasinya. Untuk penanganan yang lebih tepat, saya sarankan untuk datang langsung ke puskesmas. Saya bisa bantu booking antrian sekarang jika diperlukan 🏥'
}

function jamSekarang() {
  const now = new Date()
  return `${now.getHours().toString().padStart(2, '0')}.${now.getMinutes().toString().padStart(2, '0')}`
}

function scrollBawah() {
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight
  }
}
</script>
