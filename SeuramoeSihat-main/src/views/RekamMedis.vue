<template>
  <div class="min-h-screen bg-gray-50 pb-24">
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
      <div class="max-w-3xl mx-auto px-6 py-4 flex items-center gap-4">
        <RouterLink to="/" class="flex items-center gap-3">
          <div class="w-9 h-9 bg-emerald-600 rounded-xl flex items-center justify-center">
            <span class="text-white">🏥</span>
          </div>
          <span class="text-lg font-semibold text-gray-800">SeuramoeSihat</span>
        </RouterLink>
      </div>
    </nav>

    <div class="max-w-3xl mx-auto px-6 py-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-2">Rekam Medis</h1>
      <p class="text-sm text-gray-500 mb-6">Riwayat kesehatan digital Anda tersimpan aman</p>

      <!-- Info Card -->
      <div class="bg-white rounded-2xl p-5 border border-gray-100 mb-6">
        <div class="flex items-center gap-4">
          <div
            class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0"
          >
            👤
          </div>
          <div class="flex-1">
            <p class="text-sm font-semibold text-gray-800">Muhammad Isra Aulia</p>
            <p class="text-xs text-gray-500 mt-0.5">NIK: 1111xxxxxxxxxxxx</p>
            <div class="flex gap-2 mt-2 flex-wrap">
              <span class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-full"
                >Gol. Darah: B</span
              >
              <span class="text-xs bg-amber-50 text-amber-700 px-2 py-1 rounded-full"
                >Alergi: Penisilin</span
              >
              <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded-full"
                >Berat: 65 kg</span
              >
            </div>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-gray-100 text-center">
          <p class="text-2xl font-bold text-emerald-600">12</p>
          <p class="text-xs text-gray-400 mt-1">Total kunjungan</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 text-center">
          <p class="text-2xl font-bold text-blue-600">4</p>
          <p class="text-xs text-gray-400 mt-1">Dokter berbeda</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 text-center">
          <p class="text-2xl font-bold text-purple-600">3</p>
          <p class="text-xs text-gray-400 mt-1">Faskes dikunjungi</p>
        </div>
      </div>

      <!-- Riwayat -->
      <h2 class="text-sm font-semibold text-gray-800 mb-4">Riwayat Kunjungan</h2>
      <div class="space-y-4">
        <div
          v-for="r in rekamMedis"
          :key="r.id"
          class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-emerald-200 hover:shadow-sm transition-all duration-200 cursor-pointer"
          @click="r.expanded = !r.expanded"
        >
          <div class="p-5">
            <div class="flex items-start justify-between mb-2">
              <div>
                <p class="text-sm font-semibold text-gray-800">{{ r.faskes }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ r.dokter }}</p>
              </div>
              <div class="text-right">
                <p class="text-xs text-gray-400">{{ r.tanggal }}</p>
                <span
                  class="text-xs bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full mt-1 inline-block"
                  >Selesai</span
                >
              </div>
            </div>
            <div class="bg-gray-50 rounded-xl px-4 py-3">
              <p class="text-xs text-gray-600">
                <span class="font-medium">Keluhan:</span> {{ r.keluhan }}
              </p>
            </div>
          </div>

          <!-- Expanded -->
          <div v-if="r.expanded" class="border-t border-gray-50 px-5 pb-5 space-y-3">
            <div class="bg-emerald-50 rounded-xl px-4 py-3">
              <p class="text-xs text-gray-600">
                <span class="font-medium text-emerald-800">Diagnosa:</span> {{ r.diagnosa }}
              </p>
            </div>
            <div class="bg-blue-50 rounded-xl px-4 py-3">
              <p class="text-xs font-medium text-blue-800 mb-1">Resep Obat</p>
              <ul class="space-y-1">
                <li
                  v-for="obat in r.resep"
                  :key="obat"
                  class="text-xs text-blue-700 flex items-center gap-1"
                >
                  <span>💊</span> {{ obat }}
                </li>
              </ul>
            </div>
            <div v-if="r.catatan" class="bg-gray-50 rounded-xl px-4 py-3">
              <p class="text-xs text-gray-600">
                <span class="font-medium">Catatan dokter:</span> {{ r.catatan }}
              </p>
            </div>
          </div>

          <div class="px-5 pb-4">
            <p class="text-xs text-emerald-600 text-center">
              {{ r.expanded ? '▲ Sembunyikan detail' : '▼ Lihat detail lengkap' }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <BottomNav active="rekam" />
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import BottomNav from '../components/BottomNav.vue'

const rekamMedis = reactive([
  {
    id: 1,
    faskes: 'Puskesmas Sigli',
    dokter: 'dr. Rahmat Hidayat',
    tanggal: '10 Mei 2026',
    keluhan: 'Demam 2 hari, batuk kering, sakit kepala',
    diagnosa: 'Infeksi Saluran Pernapasan Atas (ISPA) ringan',
    resep: ['Paracetamol 500mg 3x1', 'Ambroxol 30mg 3x1', 'Vitamin C 500mg 1x1'],
    catatan:
      'Istirahat cukup, minum air putih minimal 2 liter/hari. Kontrol jika tidak membaik dalam 3 hari.',
    expanded: false,
  },
  {
    id: 2,
    faskes: 'Klinik Sehat Bersama',
    dokter: 'dr. Siti Aisyah, Sp.A',
    tanggal: '22 April 2026',
    keluhan: 'Diare 1 hari, mual, lemas',
    diagnosa: 'Diare akut dehidrasi ringan',
    resep: ['Oralit 3x1 sachet', 'Zinc 20mg 1x1 selama 10 hari', 'Probiotik Lactobacillus 2x1'],
    catatan: 'Hindari makanan berminyak dan pedas. Perbanyak minum air dan oralit.',
    expanded: false,
  },
  {
    id: 3,
    faskes: 'Puskesmas Sigli',
    dokter: 'dr. Rahmat Hidayat',
    tanggal: '1 Maret 2026',
    keluhan: 'Sakit kepala berulang, tekanan darah tinggi',
    diagnosa: 'Hipertensi Grade 1',
    resep: ['Amlodipine 5mg 1x1', 'Aspirin 80mg 1x1'],
    catatan:
      'Kurangi konsumsi garam dan makanan berlemak. Olahraga rutin 30 menit/hari. Kontrol rutin 1 bulan sekali.',
    expanded: false,
  },
])
</script>
