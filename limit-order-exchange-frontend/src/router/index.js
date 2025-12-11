import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '@/store/index.js'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      meta: { requiresAuth: true, name: 'Home' },
      redirect: { name: 'order-wallet' }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginPage.vue'),
      meta: { requiresAuth: false, isGuest: true }
    },
    {
      path: '/registration',
      name: 'registration',
      component: () => import('../views/RegistrationView.vue'),
      meta: { requiresAuth: false, isGuest: true }
    },
    {
      path: '/limit-order',
      name: 'limit-order',
      component: () => import('../views/LimitOrder.vue'),
      meta: { requiresAuth: true, name: 'Limit Order' }
    },
    {
      path: '/order-wallet',
      name: 'order-wallet',
      component: () => import('../views/OrderWallet.vue'),
      meta: { requiresAuth: true, name: 'Order & Wallet' }
    },
  ],
})

router.beforeEach((to, from, next) => {
  // to and from are both route objects. must call `next`.
  const { user } = useUserStore()
  // console.log('Router Guard:', to.name, 'User Token:', user.token);
  if (to.meta.requiresAuth && !user.token) {
    next({ path: 'login' })
  } else if (user.token && (to.meta.isGuest)) {
    next({ name: 'order-wallet' })
  } else {
    next()
  }
})

export default router
