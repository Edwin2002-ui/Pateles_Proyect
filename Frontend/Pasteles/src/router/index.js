import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import NotFoundView from '@/views/NotFoundView.vue'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,
      meta: { requireGuest: true }
    },
    {
      path: '/Home',
      name: 'home',
      component: () => import('../views/HomeView.vue'),
      meta: { requiresAuth: true } // Metaetiqueta: Requiere autenticación
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      component: NotFoundView,
      meta: { requireGuest: true }
    }
  ]
})



router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const isAuthenticated = !!authStore.token

  if (to.meta.requiresAuth && !isAuthenticated) {
    return next({ name: 'login' }) 
  }

  if (to.meta.requireGuest && isAuthenticated) {
    return next({ name: 'home' })
  }

  next()
})

export default router