
export default{
  routes: [{
    path: '/canales',
    name: 'canales',
    component: resolve => require(['modules/test/Canales.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/signup',
    name: 'signup',
    component: resolve => require(['modules/home/Signup.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/login',
    name: 'loginAccount',
    component: resolve => require(['modules/home/LogIn.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/forget_password',
    name: 'forgetPassword',
    component: resolve => require(['modules/home/RecoverAccount.vue'], resolve),
    meta: {
      tokenRequired: false
    }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: resolve => require(['modules/dashboard/Dashboard.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/semesters',
    name: 'semester',
    component: resolve => require(['modules/semester/Semester.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/courses',
    name: 'courses',
    component: resolve => require(['modules/course/Course.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  }
  ]
}
