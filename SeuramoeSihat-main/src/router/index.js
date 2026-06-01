import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', component: () => import('../views/Home.vue') },
  { path: '/cari-dokter', component: () => import('../views/CariDokter.vue') },
  { path: '/dokter/:id', component: () => import('../views/DetailDokter.vue') },
  {
    path: '/antrian',
    component: () => import('../views/Antrian.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/appointments',
    component: () => import('../views/AdminDashboard.vue'),
    meta: { requiresAuth: true, requiresAdmin: true },
  },
  {
    path: '/rekam-medis',
    component: () => import('../views/RekamMedis.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/profil',
    component: () => import('../views/Profil.vue'),
    meta: { requiresAuth: true },
  },
  { path: '/login', component: () => import('../views/auth/Login.vue') },
  { path: '/register', component: () => import('../views/auth/Register.vue') },
  {
    path: '/booking/:id',
    component: () => import('../views/Booking.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/konsultasi',
    component: () => import('../views/Konsultasi.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/konsultasi/:id',
    component: () => import('../views/Konsultasi.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/notifikasi',
    component: () => import('../views/Notifikasi.vue'),
    meta: { requiresAuth: true },
  },
  { path: '/lupa-password', component: () => import('../views/auth/LupaPassword.vue') },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const user = JSON.parse(localStorage.getItem('user') || '{}')
  
  // Check authentication
  if (to.meta.requiresAuth && !token) {
    next('/login')
    return
  }
  
  // Check admin role
  if (to.meta.requiresAdmin && user.role !== 'admin') {
    next('/')
    return
  }
  
  next()
})

export default router
