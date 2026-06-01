// API Services untuk komunikasi dengan Backend

import api from './axios'

// ========== AUTH SERVICES ==========
export const authService = {
  register(data) {
    return api.post('/register', data)
  },

  login(email, password) {
    return api.post('/login', { email, password })
  },

  logout() {
    return api.post('/logout')
  },

  setToken(token) {
    localStorage.setItem('token', token)
  },

  getToken() {
    return localStorage.getItem('token')
  },

  removeToken() {
    localStorage.removeItem('token')
  },

  isAuthenticated() {
    return !!this.getToken()
  },
}

// ========== DOCTOR SERVICES ==========
export const doctorService = {
  getAll(params = {}) {
    return api.get('/doctors', { params })
  },

  getById(id) {
    return api.get(`/doctors/${id}`)
  },

  getSchedule(id) {
    return api.get(`/doctors/${id}/schedule`)
  },

  create(data) {
    return api.post('/doctors', data)
  },

  update(id, data) {
    return api.put(`/doctors/${id}`, data)
  },

  delete(id) {
    return api.delete(`/doctors/${id}`)
  },
}

// ========== APPOINTMENT SERVICES ==========
export const appointmentService = {
  // Get all appointments (admin)
  getAll(params = {}) {
    return api.get('/admin/appointments', { params })
  },

  // Get user's appointments
  getUserAppointments(params = {}) {
    return api.get('/appointments', { params })
  },

  getById(id) {
    return api.get(`/appointments/${id}`)
  },

  create(data) {
    return api.post('/appointments', data)
  },

  update(id, data) {
    return api.put(`/appointments/${id}`, data)
  },

  delete(id) {
    return api.delete(`/appointments/${id}`)
  },

  cancel(id) {
    return api.delete(`/appointments/${id}`)
  },
}

// ========== MEDICAL RECORD SERVICES ==========
export const medicalRecordService = {
  getAll(params = {}) {
    return api.get('/medical-records', { params })
  },

  getById(id) {
    return api.get(`/medical-records/${id}`)
  },

  create(data) {
    return api.post('/medical-records', data)
  },

  update(id, data) {
    return api.put(`/medical-records/${id}`, data)
  },

  delete(id) {
    return api.delete(`/medical-records/${id}`)
  },
}

// ========== CONSULTATION SERVICES ==========
export const consultationService = {
  getAll(params = {}) {
    return api.get('/consultations', { params })
  },

  getById(id) {
    return api.get(`/consultations/${id}`)
  },

  create(data) {
    return api.post('/consultations', data)
  },

  update(id, data) {
    return api.put(`/consultations/${id}`, data)
  },

  delete(id) {
    return api.delete(`/consultations/${id}`)
  },

  end(id) {
    return api.delete(`/consultations/${id}`)
  },
}

// ========== PROFILE SERVICES ==========
export const profileService = {
  getProfile() {
    return api.get('/profile')
  },

  updateProfile(data) {
    return api.put('/profile', data)
  },
}

// ========== NOTIFICATION SERVICES ==========
export const notificationService = {
  getAll(params = {}) {
    return api.get('/notifications', { params })
  },

  markAsRead(id) {
    return api.put(`/notifications/${id}/read`)
  },

  markAllAsRead() {
    return api.put('/notifications/read-all')
  },

  delete(id) {
    return api.delete(`/notifications/${id}`)
  },
}

export default {
  authService,
  doctorService,
  appointmentService,
  medicalRecordService,
  consultationService,
  profileService,
  notificationService,
}
