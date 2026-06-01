<template>
  <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 flex md:hidden z-50">
    <RouterLink
      v-for="item in items"
      :key="item.to"
      :to="item.to"
      class="flex-1 flex flex-col items-center gap-1 py-3 text-xs transition-colors"
      :class="active === item.key ? 'text-emerald-600' : 'text-gray-400 hover:text-gray-600'"
    >
      <span class="text-xl">{{ item.icon }}</span>
      {{ item.label }}
    </RouterLink>
  </div>
</template>

<script setup>
defineProps({ active: String })
const items = [
  { key: 'home', to: '/', icon: '🏠', label: 'Beranda' },
  { key: 'cari', to: '/cari-dokter', icon: '🔍', label: 'Cari' },
  { key: 'konsultasi', to: '/konsultasi', icon: '💬', label: 'Chat' },
  { key: 'antrian', to: '/antrian', icon: '📋', label: 'Antrian' },
  { key: 'profil', to: '/profil', icon: '👤', label: 'Profil' },
]
</script>
