import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService } from '@/api/services'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(authService.getToken())
  const isLoading = ref(false)
  const error = ref(null)

  const isAuthenticated = computed(() => !!token.value)

  async function register(userData) {
    isLoading.value = true
    error.value = null
    try {
      const response = await authService.register(userData)
      token.value = response.data.token
      user.value = response.data.user
      authService.setToken(token.value)
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Registration failed'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  async function login(email, password) {
    isLoading.value = true
    error.value = null
    try {
      const response = await authService.login(email, password)
      token.value = response.data.token
      user.value = response.data.user
      authService.setToken(token.value)
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  async function logout() {
    isLoading.value = true
    error.value = null
    try {
      await authService.logout()
    } finally {
      token.value = null
      user.value = null
      authService.removeToken()
      isLoading.value = false
    }
  }

  function setUser(userData) {
    user.value = userData
  }

  return {
    user,
    token,
    isLoading,
    error,
    isAuthenticated,
    register,
    login,
    logout,
    setUser,
  }
})
