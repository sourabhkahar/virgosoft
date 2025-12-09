import { createRouter, createWebHistory } from 'vue-router'
import ProfilePage from '../views/ProfilePage.vue'
import LoginPage from '@/views/LoginPage.vue'
import RegistrationView from '@/views/RegistrationView.vue'
import { useUserStore } from '@/store/index.js'
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginPage,
      meta: { requiresAuth: false, isGuest: true }
    },
    {
      path: '/registration',
      name: 'registration',
      component: RegistrationView,
      meta: { requiresAuth: false, isGuest: true }
    },
     {
      path: '/',
      name: 'home',
      // component: ProfilePage,
      meta: { requiresAuth: true },
      redirect: { name: 'profile' }
    },
    {
      path: '/profile',
      name: 'profile',
      component: ProfilePage,
      meta: { requiresAuth: true }
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
      meta: { requiresAuth: true }
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
    next({ name: 'profile' })
  } else {
    next()
  }
})

export default router
