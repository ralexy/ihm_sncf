import { createRouter, createWebHistory } from 'vue-router'
//import Home from '../views/Home.vue'
import Step0 from '../views/Step0.vue'
import Step1 from '../views/Step1.vue'
import Step2 from '../views/Step2.vue'
import Step3 from '../views/Step3.vue'
import Result from '../views/Result.vue'


const routes = [
  {
    path: '/',
    name: 'Step0',
    component: Step0
  },
  {
    path: '/step1',
    name: 'Step1',
    component: Step1
  },
  {
    path: '/step2',
    name: 'Step2',
    component: Step2
  },
  {
    path: '/step3',
    name: 'Step3',
    component: Step3
  },
  {
    path: '/result',
    name: 'Result',
    component: Result
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
