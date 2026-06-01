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
      <div class="relative">
        <div class="absolute -inset-[1px] rounded-2xl overflow-hidden pointer-events-none">
          <div class="light-beam-top" />
          <div class="light-beam-right" />
          <div class="light-beam-bottom" />
          <div class="light-beam-left" />
        </div>

        <div
          class="relative bg-black/40 backdrop-blur-xl rounded-2xl p-6 border border-white/[0.05] shadow-2xl"
        >
          <!-- Step 1: Input Email -->
          <div v-if="step === 1">
            <div class="text-center mb-6">
              <div
                class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl"
              >
                🔐
              </div>
              <h1 class="text-xl font-bold text-white">Lupa Password?</h1>
              <p class="text-white/60 text-xs mt-2 leading-relaxed">
                Masukkan email Anda dan kami akan kirim kode OTP untuk reset password.
              </p>
            </div>

            <div
              v-if="errorMsg"
              class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-lg px-4 py-3 mb-4"
            >
              {{ errorMsg }}
            </div>

            <div class="mb-4">
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
                  @keyup.enter="kirimOTP"
                  class="w-full bg-white/5 border border-white/10 focus:border-white/30 focus:ring-1 focus:ring-white/20 focus:bg-white/10 text-white placeholder:text-white/30 text-sm h-10 pl-10 pr-4 rounded-xl outline-none transition-all duration-300"
                />
              </div>
            </div>

            <button
              @click="kirimOTP"
              :disabled="loading"
              class="w-full h-10 rounded-xl bg-white text-black font-medium text-sm flex items-center justify-center gap-2 hover:bg-white/90 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span
                v-if="loading"
                class="w-4 h-4 border-2 border-black/50 border-t-transparent rounded-full animate-spin"
              />
              <span v-else>Kirim Kode OTP</span>
            </button>
          </div>

          <!-- Step 2: Input OTP -->
          <div v-if="step === 2">
            <div class="text-center mb-6">
              <div
                class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl"
              >
                📱
              </div>
              <h1 class="text-xl font-bold text-white">Cek Email Anda</h1>
              <p class="text-white/60 text-xs mt-2 leading-relaxed">
                Kode OTP dikirim ke <strong class="text-white">{{ email }}</strong
                >. Masukkan 6 digit kode di bawah.
              </p>
            </div>

            <div
              v-if="errorMsg"
              class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-lg px-4 py-3 mb-4"
            >
              {{ errorMsg }}
            </div>

            <!-- OTP Input -->
            <div class="flex gap-2 justify-center mb-5">
              <input
                v-for="(_, i) in otpDigits"
                :key="i"
                v-model="otpDigits[i]"
                :ref="(el) => (otpRefs[i] = el)"
                @input="handleOtpInput(i)"
                @keydown.backspace="handleOtpBackspace(i)"
                type="text"
                maxlength="1"
                class="w-11 h-12 text-center text-white font-bold text-lg bg-white/5 border border-white/10 rounded-xl outline-none focus:border-white/40 focus:ring-1 focus:ring-white/20 focus:bg-white/10 transition-all"
              />
            </div>

            <!-- Countdown -->
            <p class="text-center text-xs text-white/50 mb-4">
              <span v-if="countdown > 0"
                >Kirim ulang dalam <strong class="text-white">{{ countdown }}s</strong></span
              >
              <button
                v-else
                @click="kirimOTP"
                class="text-emerald-400 hover:text-emerald-300 transition-colors font-medium"
              >
                Kirim ulang kode
              </button>
            </p>

            <button
              @click="verifikasiOTP"
              :disabled="otpDigits.join('').length < 6 || loading"
              class="w-full h-10 rounded-xl bg-white text-black font-medium text-sm flex items-center justify-center gap-2 hover:bg-white/90 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span
                v-if="loading"
                class="w-4 h-4 border-2 border-black/50 border-t-transparent rounded-full animate-spin"
              />
              <span v-else>Verifikasi OTP</span>
            </button>
          </div>

          <!-- Step 3: Reset Password -->
          <div v-if="step === 3">
            <div class="text-center mb-6">
              <div
                class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl"
              >
                🔑
              </div>
              <h1 class="text-xl font-bold text-white">Buat Password Baru</h1>
              <p class="text-white/60 text-xs mt-2">Buat password yang kuat dan mudah diingat.</p>
            </div>

            <div
              v-if="errorMsg"
              class="bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-lg px-4 py-3 mb-4"
            >
              {{ errorMsg }}
            </div>

            <div class="space-y-3 mb-4">
              <div>
                <label class="block text-xs font-medium text-white/70 mb-1.5">Password Baru</label>
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
                    v-model="passwordBaru"
                    :type="showPass ? 'text' : 'password'"
                    placeholder="Minimal 8 karakter"
                    class="w-full bg-white/5 border border-white/10 focus:border-white/30 focus:ring-1 focus:ring-white/20 focus:bg-white/10 text-white placeholder:text-white/30 text-sm h-10 pl-10 pr-10 rounded-xl outline-none transition-all"
                  />
                  <button
                    type="button"
                    @click="showPass = !showPass"
                    class="absolute right-3 text-white/40 hover:text-white transition-colors"
                  >
                    <svg
                      v-if="showPass"
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                      />
                    </svg>
                    <svg
                      v-else
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"
                      />
                    </svg>
                  </button>
                </div>

                <!-- Password strength -->
                <div class="mt-2 flex gap-1">
                  <div
                    v-for="i in 4"
                    :key="i"
                    class="flex-1 h-1 rounded-full transition-all duration-300"
                    :class="passwordStrength >= i ? strengthColor : 'bg-white/10'"
                  />
                </div>
                <p class="text-xs mt-1" :class="strengthTextColor">{{ strengthLabel }}</p>
              </div>

              <div>
                <label class="block text-xs font-medium text-white/70 mb-1.5"
                  >Konfirmasi Password</label
                >
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
                    v-model="konfirmasiPassword"
                    :type="showPass ? 'text' : 'password'"
                    placeholder="Ulangi password baru"
                    class="w-full bg-white/5 border text-white placeholder:text-white/30 text-sm h-10 pl-10 pr-4 rounded-xl outline-none transition-all"
                    :class="
                      konfirmasiPassword && passwordBaru !== konfirmasiPassword
                        ? 'border-red-500/40 focus:border-red-500/60'
                        : 'border-white/10 focus:border-white/30 focus:ring-1 focus:ring-white/20 focus:bg-white/10'
                    "
                  />
                </div>
                <p
                  v-if="konfirmasiPassword && passwordBaru !== konfirmasiPassword"
                  class="text-xs text-red-400 mt-1"
                >
                  Password tidak cocok
                </p>
              </div>
            </div>

            <button
              @click="resetPassword"
              :disabled="!passwordBaru || passwordBaru !== konfirmasiPassword || loading"
              class="w-full h-10 rounded-xl bg-white text-black font-medium text-sm flex items-center justify-center gap-2 hover:bg-white/90 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span
                v-if="loading"
                class="w-4 h-4 border-2 border-black/50 border-t-transparent rounded-full animate-spin"
              />
              <span v-else>Simpan Password Baru</span>
            </button>
          </div>

          <!-- Step 4: Sukses -->
          <div v-if="step === 4" class="text-center py-4">
            <div class="text-5xl mb-4">✅</div>
            <h1 class="text-xl font-bold text-white mb-2">Password Berhasil Diubah!</h1>
            <p class="text-white/60 text-xs mb-6 leading-relaxed">
              Password Anda berhasil diperbarui. Silakan masuk dengan password baru.
            </p>
            <RouterLink
              to="/login"
              class="block w-full h-10 rounded-xl bg-white text-black font-medium text-sm flex items-center justify-center hover:bg-white/90 transition-all"
            >
              Masuk Sekarang
            </RouterLink>
          </div>

          <!-- Back to login -->
          <p v-if="step < 4" class="text-center text-xs text-white/50 mt-5">
            Ingat password?
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
import { ref, computed, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const step = ref(1)
const email = ref('')
const loading = ref(false)
const errorMsg = ref('')
const otpDigits = ref(['', '', '', '', '', ''])
const otpRefs = ref([])
const countdown = ref(0)
const passwordBaru = ref('')
const konfirmasiPassword = ref('')
const showPass = ref(false)

let countdownInterval = null

const passwordStrength = computed(() => {
  const p = passwordBaru.value
  if (!p) return 0
  let score = 0
  if (p.length >= 8) score++
  if (/[A-Z]/.test(p)) score++
  if (/[0-9]/.test(p)) score++
  if (/[^A-Za-z0-9]/.test(p)) score++
  return score
})

const strengthColor = computed(() => {
  if (passwordStrength.value <= 1) return 'bg-red-500'
  if (passwordStrength.value === 2) return 'bg-amber-500'
  if (passwordStrength.value === 3) return 'bg-blue-500'
  return 'bg-emerald-500'
})

const strengthTextColor = computed(() => {
  if (passwordStrength.value <= 1) return 'text-red-400'
  if (passwordStrength.value === 2) return 'text-amber-400'
  if (passwordStrength.value === 3) return 'text-blue-400'
  return 'text-emerald-400'
})

const strengthLabel = computed(() => {
  if (!passwordBaru.value) return ''
  if (passwordStrength.value <= 1) return 'Password lemah'
  if (passwordStrength.value === 2) return 'Password cukup'
  if (passwordStrength.value === 3) return 'Password kuat'
  return 'Password sangat kuat ✓'
})

function mulaiCountdown() {
  countdown.value = 60
  clearInterval(countdownInterval)
  countdownInterval = setInterval(() => {
    countdown.value--
    if (countdown.value <= 0) clearInterval(countdownInterval)
  }, 1000)
}

function kirimOTP() {
  if (!email.value) {
    errorMsg.value = 'Email wajib diisi.'
    return
  }
  if (!email.value.includes('@')) {
    errorMsg.value = 'Format email tidak valid.'
    return
  }
  loading.value = true
  errorMsg.value = ''
  setTimeout(() => {
    loading.value = false
    step.value = 2
    mulaiCountdown()
  }, 1200)
}

function handleOtpInput(i) {
  const val = otpDigits.value[i]
  if (val && i < 5) {
    otpRefs.value[i + 1]?.focus()
  }
}

function handleOtpBackspace(i) {
  if (!otpDigits.value[i] && i > 0) {
    otpDigits.value[i - 1] = ''
    otpRefs.value[i - 1]?.focus()
  }
}

function verifikasiOTP() {
  const otp = otpDigits.value.join('')
  if (otp.length < 6) return
  loading.value = true
  errorMsg.value = ''
  setTimeout(() => {
    loading.value = false
    // Demo: OTP apapun diterima
    step.value = 3
  }, 1200)
}

function resetPassword() {
  if (passwordBaru.value !== konfirmasiPassword.value) return
  loading.value = true
  errorMsg.value = ''
  setTimeout(() => {
    loading.value = false
    step.value = 4
  }, 1200)
}

onUnmounted(() => clearInterval(countdownInterval))
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
