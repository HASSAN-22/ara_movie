import { createRouter, createWebHistory } from 'vue-router'
import FrontLayout from '@/components/FrontLayout'
import DashboardLayout from '@/components/DashboardLayout'
import store from '../store'
const routes = [
  {
    path: '/',
    component: FrontLayout,
    children:[
      {
        path: '/',
        name: 'Index',
        component: () => import(/* webpackChunkName: "/" */ '../views/Index.vue')
      },
      {
        path: '/:value?',
        name: 'IndexWithValue',
        component: () => import(/* webpackChunkName: "/:value?" */ '../views/Index.vue')
      },
      {
        path: '/list/:slug',
        name: 'MoviesList',
        component: () => import(/* webpackChunkName: "/list/:slug" */ '../views/MoviesList.vue')
      },
      
      {
        path: '/detail/:slug',
        name: 'Detail',
        component: () => import(/* webpackChunkName: "/detail/:slug" */ '../views/Detail.vue')
      },
      {
        path: '/not-found',
        name: 'NotFound',
        component: () => import(/* webpackChunkName: "not-found" */ '../views/NotFound.vue')
      },
      {
        path: '/show-vip',
        name: 'ShowVip',
        component: () => import(/* webpackChunkName: "show-vip" */ '../views/ShowVip.vue')
      },
      {
        path: '/show-page/:title',
        name: 'ShowPage',
        component: () => import(/* webpackChunkName: "show-page/:title" */ '../views/Page.vue')
      },
      {
        path: '/forget-password/:token/:email',
        name: 'ForgetPassword',
        component: () => import(/* webpackChunkName: "forget-password/:token" */ '../views/ForgetPassword.vue')
      }
    ]
  },
  {
    path: '/dashboard',
    meta: { requiresAuth: true },
    component:DashboardLayout,
    children:[
      /** ################################################### Admin Routes ########################################################### **/
        {
          path: '/dashboard',
          name: 'Dashboard',
          component: () => import(/* webpackChunkName: "dashboard" */ '../views/Dashboard')
        },
        {
          path: '/category',
          name: 'Category',
          component: () => import(/* webpackChunkName: "category" */ '../views/panel/Category')
        },
        {
          path: '/user',
          name: 'User',
          component: () => import(/* webpackChunkName: "user" */ '../views/panel/User')
        },
        {
          path: '/movie',
          name: 'Movie',
          component: () => import(/* webpackChunkName: "movie" */ '../views/panel/Movie')
        },
        {
          path: '/movie-link',
          name: 'MovieLink',
          component: () => import(/* webpackChunkName: "movie-link" */ '../views/panel/MovieLink')
        },
        {
          path: '/movie-tag',
          name: 'MovieTag',
          component: () => import(/* webpackChunkName: "movie-tag" */ '../views/panel/MovieTag')
        },
        {
          path: '/report-link',
          name: 'ReportLink',
          component: () => import(/* webpackChunkName: "report-link" */ '../views/panel/ReportLink')
        },
        {
          path: '/plan',
          name: 'Plan',
          component: () => import(/* webpackChunkName: "plan" */ '../views/panel/Plan')
        },
        {
          path: '/profile',
          name: 'Profile',
          component: () => import(/* webpackChunkName: "profile" */ '../views/panel/Profile')
        },
        {
          path: '/discount',
          name: 'Discount',
          component: () => import(/* webpackChunkName: "discount" */ '../views/panel/Discount')
        },
        {
          path: '/cart',
          name: 'Cart',
          component: () => import(/* webpackChunkName: "cart" */ '../views/panel/Cart')
        },
        {
          path: '/bank-portal',
          name: 'BankPortal',
          component: () => import(/* webpackChunkName: "bank-portal" */ '../views/panel/BankPortal')
        },
        {
          path: '/wallet-transaction',
          name: 'WalletTransaction',
          component: () => import(/* webpackChunkName: "wallet-transaction" */ '../views/panel/WalletTransaction')
        },
        {
          path: '/checkout',
          name: 'Checkout',
          component: () => import(/* webpackChunkName: "checkout" */ '../views/panel/Checkout')
        },
        {
          path: '/vip',
          name: 'Vip',
          component: () => import(/* webpackChunkName: "vip" */ '../views/panel/Vip')
        },
        {
          path: '/comment',
          name: 'Comment',
          component: () => import(/* webpackChunkName: "comment" */ '../views/panel/Comment')
        },
        {
          path: '/page',
          name: 'Page',
          component: () => import(/* webpackChunkName: "page" */ '../views/panel/Page')
        },
        {
          path: '/config-site',
          name: 'ConfigSite',
          component: () => import(/* webpackChunkName: "config-site" */ '../views/panel/ConfigSite')
        },
        {
          path: '/slider',
          name: 'Slider',
          component: () => import(/* webpackChunkName: "slider" */ '../views/panel/Slider')
        },

      /** ################################################### User Routes ########################################################### **/
      {
        path: '/user-profile',
        name: 'UserProfile',
        component: () => import(/* webpackChunkName: "user-profile" */ '../views/userPanel/Profile')
      },
      {
        path: '/user-cart',
        name: 'UserCart',
        component: () => import(/* webpackChunkName: "user-cart" */ '../views/userPanel/Cart')
      },
      {
        path: '/user-wallet-transaction',
        name: 'UserWalletTransaction',
        component: () => import(/* webpackChunkName: "user-wallet-transaction" */ '../views/userPanel/WalletTransaction')
      },
      {
        path: '/user-checkout',
        name: 'UserCheckout',
        component: () => import(/* webpackChunkName: "user-checkout" */ '../views/userPanel/Checkout')
      },
      {
        path: '/user-vip',
        name: 'UserVip',
        component: () => import(/* webpackChunkName: "user-vip" */ '../views/userPanel/Vip')
      },
      {
        path: '/user-watch-list',
        name: 'UserWatchList',
        component: () => import(/* webpackChunkName: "user-watch-list" */ '../views/userPanel/WatchList')
      },
    ]
  },
  {
    path: '/auth',
    redirect:'/login',
    name: 'Auth',
    component: FrontLayout,
    meta: {isGuest:true},
    children:[
      {
        path: '/login',
        name: 'Login',
        component: () => import(/* webpackChunkName: "login" */ '../views/Login')
      },
      {
        path: '/register',
        name: 'Register',
        component: () => import(/* webpackChunkName: "register" */ '../views/Register')
      }
    ]
  },

]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
  scrollBehavior (to, from, savedPosition) {
    return { top: 0 };
  }

})

router.beforeEach((to,from,next)=>{
  
  if(to.name === undefined && to.fullPath !== '/'){
    next({name:'NotFound'});
  }
  if(to.meta.requiresAuth === true){
    store.dispatch('checkToken')
  }
  if(to.meta.requiresAuth && !store.getters.getToken){
    next({name:'Login'});
  }else if(store.getters.getToken && (to.meta.isGuest)){
    next({name:'Dashboard'});
  }else{
    next();
  }
});

export default router
