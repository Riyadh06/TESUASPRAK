<template>
  <div
    class="min-h-screen w-full bg-black relative overflow-hidden flex items-center justify-center py-8"
  >
    <div
      class="absolute inset-0 bg-gradient-to-b from-emerald-500/40 via-emerald-700/50 to-black"
    />
    <div
      class="absolute top-0 left-1/2 -translate-x-1/2 w-[120vh] h-[60vh] rounded-b-[50%] bg-emerald-400/20 blur-[80px]"
    />
    <div
      class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[90vh] h-[90vh] rounded-t-full bg-emerald-400/20 blur-[60px]"
    />

    <div class="w-full max-w-sm relative z-10 px-4">
      <div class="relative group">
        <div class="absolute -inset-[1px] rounded-2xl overflow-hidden pointer-events-none">
          <div class="light-beam-top" />
          <div class="light-beam-right" />
          <div class="light-beam-bottom" />
          <div class="light-beam-left" />
        </div>

        <div
          class="relative bg-black/40 backdrop-blur-xl rounded-2xl p-6 border border-white/[0.05] shadow-2xl"
        >
          <div class="text-center mb-6">
            <div
              class="w-12 h-12 rounded-full border border-white/10 bg-white/5 flex items-center justify-center mx-auto mb-3"
            >
              <span class="text-xl">🏥</span>
            </div>
            <h1 class="text-xl font-bold text-white">Buat Akun Baru</h1>
            <p class="text-white/60 text-xs mt-1">Daftar dan mulai gunakan SeuramoeSihat</p>
          </div>

          <div
            v-if="error"
            class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-lg px-4 py-3 mb-4"
          >
            {{ error }}
          </div>
          <div
            v-if="sukses"
            class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs rounded-lg px-4 py-3 mb-4"
          >
            ✅ Akun berhasil dibuat! Mengarahkan ke login...
          </div>

          <form @submit.prevent="handleRegister" class="space-y-3">
            <div class="relative flex items-center">
              <User class="absolute left-3 w-4 h-4 text-white/40" />
              <input
                v-model="nama"
                type="text"
                placeholder="Nama lengkap"
                class="w-full bg-white/5 border border-white/10 focus:border-white/20 focus:bg-white/10 text-white placeholder:text-white/30 text-sm h-10 pl-10 pr-3 rounded-lg outline-none transition-all duration-300"
              />
            </div>

            <div class="relative flex items-center">
              <Phone class="absolute left-3 w-4 h-4 text-white/40" />
              <input
                v-model="noHp"
                type="tel"
                placeholder="Nomor HP (WhatsApp)"
                class="w-full bg-white/5 border border-white/10 focus:border-white/20 focus:bg-white/10 text-white placeholder:text-white/30 text-sm h-10 pl-10 pr-3 rounded-lg outline-none transition-all duration-300"
              />
            </div>

            <div class="relative flex items-center">
              <Mail class="absolute left-3 w-4 h-4 text-white/40" />
              <input
                v-model="email"
                type="email"
                placeholder="Alamat email"
                class="w-full bg-white/5 border border-white/10 focus:border-white/20 focus:bg-white/10 text-white placeholder:text-white/30 text-sm h-10 pl-10 pr-3 rounded-lg outline-none transition-all duration-300"
              />
            </div>

            <div class="relative flex items-center">
              <Lock class="absolute left-3 w-4 h-4 text-white/40" />
              <input
                v-model="password"
                :type="showPass ? 'text' : 'password'"
                placeholder="Password (min. 8 karakter)"
                class="w-full bg-white/5 border border-white/10 focus:border-white/20 focus:bg-white/10 text-white placeholder:text-white/30 text-sm h-10 pl-10 pr-10 rounded-lg outline-none transition-all duration-300"
              />
              <button
                type="button"
                @click="showPass = !showPass"
                class="absolute right-3 text-white/40 hover:text-white transition-colors"
              >
                <Eye v-if="showPass" class="w-4 h-4" />
                <EyeOff v-else class="w-4 h-4" />
              </button>
            </div>

            <div class="relative flex items-center">
              <Lock class="absolute left-3 w-4 h-4 text-white/40" />
              <input
                v-model="konfirmasi"
                :type="showPass ? 'text' : 'password'"
                placeholder="Konfirmasi password"
                class="w-full bg-white/5 border border-white/10 focus:border-white/20 focus:bg-white/10 text-white placeholder:text-white/30 text-sm h-10 pl-10 pr-3 rounded-lg outline-none transition-all duration-300"
              />
            </div>

            <button
              type="submit"
              :disabled="loading"
              class="w-full h-10 rounded-lg bg-white text-black font-medium text-sm flex items-center justify-center gap-1 hover:bg-white/90 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed mt-2 group/btn"
            >
              <span
                v-if="loading"
                class="w-4 h-4 border-2 border-black/50 border-t-transparent rounded-full animate-spin"
              />
              <template v-else>
                Daftar Sekarang
                <ArrowRight
                  class="w-3 h-3 group-hover/btn:translate-x-1 transition-transform duration-300"
                />
              </template>
            </button>
          </form>

          <p class="text-center text-xs text-white/60 mt-5">
            Sudah punya akun?
            <RouterLink
              to="/login"
              class="text-white font-medium hover:text-white/70 transition-colors hover:underline underline-offset-2"
            >
              Masuk di sini
            </RouterLink>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Mail, Lock, Eye, EyeOff, ArrowRight, User, Phone } from 'lucide-vue-next'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const auth = useAuthStore()

const nama = ref('')
const noHp = ref('')
const email = ref('')
const password = ref('')
const konfirmasi = ref('')
const showPass = ref(false)
const loading = ref(false)
const error = ref('')
const sukses = ref(false)

async function handleRegister() {
  error.value = ''
  if (!nama.value || !email.value || !password.value || !konfirmasi.value) {
    error.value = 'Semua field wajib diisi.'
    return
  }
  if (password.value.length < 8) {
    error.value = 'Password minimal 8 karakter.'
    return
  }
  if (password.value !== konfirmasi.value) {
    error.value = 'Password dan konfirmasi tidak cocok.'
    return
  }
  loading.value = true
  const berhasil = await auth.register(nama.value, email.value, password.value)
  loading.value = false
  if (berhasil) {
    sukses.value = true
    setTimeout(() => router.push('/login'), 1500)
  } else {
    error.value = auth.error
  }
}
</script>

<style scoped>
@keyframes beam-top {
  0% {
    left: -50%;
  }
  100% {
    left: 100%;
  }
}
@keyframes beam-right {
  0% {
    top: -50%;
  }
  100% {
    top: 100%;
  }
}
@keyframes beam-bottom {
  0% {
    right: -50%;
  }
  100% {
    right: 100%;
  }
}
@keyframes beam-left {
  0% {
    bottom: -50%;
  }
  100% {
    bottom: 100%;
  }
}

.light-beam-top {
  position: absolute;
  top: 0;
  left: -50%;
  height: 2px;
  width: 50%;
  background: linear-gradient(to right, transparent, white, transparent);
  opacity: 0.6;
  animation: beam-top 2.5s ease-in-out infinite;
}
.light-beam-right {
  position: absolute;
  top: -50%;
  right: 0;
  width: 2px;
  height: 50%;
  background: linear-gradient(to bottom, transparent, white, transparent);
  opacity: 0.6;
  animation: beam-right 2.5s ease-in-out infinite;
  animation-delay: 0.6s;
}
.light-beam-bottom {
  position: absolute;
  bottom: 0;
  right: -50%;
  height: 2px;
  width: 50%;
  background: linear-gradient(to left, transparent, white, transparent);
  opacity: 0.6;
  animation: beam-bottom 2.5s ease-in-out infinite;
  animation-delay: 1.2s;
}
.light-beam-left {
  position: absolute;
  bottom: -50%;
  left: 0;
  width: 2px;
  height: 50%;
  background: linear-gradient(to top, transparent, white, transparent);
  opacity: 0.6;
  animation: beam-left 2.5s ease-in-out infinite;
  animation-delay: 1.8s;
}
</style>
