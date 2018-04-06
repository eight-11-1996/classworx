
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
    path: '/enrolled_courses',
    name: 'enrolledCourses',
    component: resolve => require(['modules/course/EnrolledCourse.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/courses/:id',
    name: 'courses',
    component: resolve => require(['modules/course/Course.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/courses/accounts/:id',
    name: 'courseAccounts',
    component: resolve => require(['modules/course/CourseAccount.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/exams/:courseId',
    name: 'exams',
    component: resolve => require(['modules/exam/Exam.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/quizzes/:courseId',
    name: 'quizzes',
    component: resolve => require(['modules/quiz/Quiz.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/mchoice',
    name: 'mchoice',
    component: resolve => require(['modules/question/MultipleChoice.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/quizzes/questions/:id',
    name: 'quizzesQuestions',
    component: resolve => require(['modules/question/Question.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/exams/questions/:id/',
    name: 'examsQuestions',
    component: resolve => require(['modules/question/Question.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  }
  ]
}
