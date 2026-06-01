<template>
  <div
    class="min-h-screen w-full bg-black relative overflow-hidden flex items-center justify-center"
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
          <!-- Logo -->
          <div class="text-center mb-5">
            <div
              class="w-12 h-12 rounded-full border border-white/10 bg-white/5 flex items-center justify-center mx-auto mb-3"
            >
              <span class="text-xl">🏥</span>
            </div>
            <h1 class="text-xl font-bold text-white">SeuramoeSihat</h1>
            <p class="text-white/60 text-xs mt-1">Masuk ke akun Anda</p>

            <!-- Badge status -->
            <div class="flex justify-center mt-3">
              <Badge variant="success" appearance="light" dot> Layanan aktif 24 jam </Badge>
            </div>
          </div>

          <!-- Error -->
          <div
            v-if="error"
            class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-lg px-4 py-3 mb-4"
          >
            {{ error }}
          </div>

          <!-- Form -->
          <form @submit.prevent="handleLogin" class="space-y-3">
            <!-- Email Input -->
            <div>
              <label class="block text-xs font-medium text-white/70 mb-1.5">Email</label>
              <div class="relative flex items-center">
                <svg
                  class="absolute left-3 w-4 h-4 text-white/40 pointer-events-none"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                </svg>
                <input
                  v-model="email"
                  type="email"
                  placeholder="contoh@email.com"
                  @focus="focused = 'email'"
                  @blur="focused = ''"
                  class="w-full bg-white/5 border text-white placeholder:text-white/30 text-sm h-10 pl-10 pr-4 rounded-xl outline-none transition-all duration-300"
                  :class="
                    focused === 'email'
                      ? 'border-white/30 ring-1 ring-white/20 bg-white/10'
                      : 'border-white/10'
                  "
                />
              </div>
            </div>

            <!-- Password Input -->
            <div>
              <label class="block text-xs font-medium text-white/70 mb-1.5">Password</label>
              <div class="relative flex items-center">
                <svg
                  class="absolute left-3 w-4 h-4 text-white/40 pointer-events-none"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.5"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                  />
                </svg>
                <input
                  v-model="password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Masukkan password"
                  @focus="focused = 'password'"
                  @blur="focused = ''"
                  @keyup.enter="handleLogin"
                  class="w-full bg-white/5 border text-white placeholder:text-white/30 text-sm h-10 pl-10 pr-10 rounded-xl outline-none transition-all duration-300"
                  :class="
                    focused === 'password'
                      ? 'border-white/30 ring-1 ring-white/20 bg-white/10'
                      : 'border-white/10'
                  "
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3 text-white/40 hover:text-white transition-colors"
                >
                  <svg
                    v-if="showPassword"
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                    />
                  </svg>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"
                    />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between pt-1">
              <label class="flex items-center gap-2 text-xs text-white/60 cursor-pointer">
                <input type="checkbox" v-model="ingat" class="rounded border-white/20 bg-white/5" />
                Ingat saya
              </label>
              <RouterLink
                to="/lupa-password"
                class="text-xs text-white/60 hover:text-white transition-colors"
              >
                Lupa password?
              </RouterLink>
              >
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              :disabled="loading"
              class="w-full h-10 rounded-xl bg-white text-black font-medium text-sm flex items-center justify-center gap-2 hover:bg-white/90 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed mt-2 group/btn"
            >
              <span
                v-if="loading"
                class="w-4 h-4 border-2 border-black/50 border-t-transparent rounded-full animate-spin"
              />
              <template v-else>
                Masuk
                <svg
                  class="w-3 h-3 group-hover/btn:translate-x-1 transition-transform"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                  />
                </svg>
              </template>
            </button>

            <!-- Divider -->
            <div class="flex items-center gap-3 my-2">
              <div class="flex-1 border-t border-white/5" />
              <span class="text-xs text-white/40">atau</span>
              <div class="flex-1 border-t border-white/5" />
            </div>

            <!-- Google -->
            <button
              type="button"
              class="w-full h-10 rounded-xl bg-white/5 border border-white/10 hover:border-white/20 text-white/80 hover:text-white text-xs font-medium flex items-center justify-center gap-2 transition-all duration-300"
            >
              <span class="font-bold">G</span>
              Masuk dengan Google
            </button>
          </form>

          <p class="text-center text-xs text-white/60 mt-5">
            Belum punya akun?
            <RouterLink
              to="/register"
              class="text-white font-medium hover:text-white/70 transition-colors hover:underline underline-offset-2"
            >
              Daftar sekarang
            </RouterLink>
          </p>
        </div>
      </div>

      <div class="mt-4 bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-center">
        <p class="text-xs text-white/50">
          Demo: <strong class="text-white/80">pasien@demo.com</strong> /
          <strong class="text-white/80">password123</strong>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import Badge from '../../components/ui/Badge.vue'

const router = useRouter()
const auth = useAuthStore()

const email = ref('')
const password = ref('')
const ingat = ref(false)
const showPassword = ref(false)
const focused = ref('')
const loading = ref(false)
const error = ref('')

async function handleLogin() {
  if (!email.value || !password.value) {
    error.value = 'Email dan password wajib diisi.'
    return
  }
  loading.value = true
  error.value = ''

  if (email.value === 'pasien@demo.com' && password.value === 'password123') {
    localStorage.setItem('token', 'demo-token')
    setTimeout(() => router.push('/'), 800)
    return
  }

  const berhasil = await auth.login(email.value, password.value)
  loading.value = false
  if (berhasil) {
    // Check if admin, redirect to admin dashboard
    if (auth.user?.role === 'admin') {
      router.push('/admin/appointments')
    } else {
      router.push('/')
    }
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
