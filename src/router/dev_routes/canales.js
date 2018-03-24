
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
    path: '/word_translation',
    name: 'wordTranslation',
    component: resolve => require(['modules/translation/word.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/lesson_management',
    name: 'lessonManagement',
    component: resolve => require(['modules/lesson/LessonManagementv2.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/audio_upload',
    name: 'uploadAudio',
    component: resolve => require(['modules/lesson/uploadAudio.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: resolve => require(['modules/dashboard/dashboardv2.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/word_audio',
    name: 'wordAudio',
    component: resolve => require(['modules/lesson/WordAudiov2.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/content_management',
    name: 'contentManagement',
    component: resolve => require(['modules/lesson/ContentManagementv2.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/sentence_popup',
    name: 'sentencePopup',
    component: resolve => require(['modules/lesson/SentencePopup.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/word_popup',
    name: 'wordPopup',
    component: resolve => require(['modules/lesson/WordPopup.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/dashboard_temp',
    name: 'dashboardTemp',
    component: resolve => require(['modules/dashboard/dashboard1.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/accent_audio',
    name: 'accentAudio',
    component: resolve => require(['modules/lesson/AccentAudio.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/accent_video',
    name: 'accentVideo',
    component: resolve => require(['modules/lesson/AccentVideo.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/history',
    name: 'history',
    component: resolve => require(['modules/history/History.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/audio_files',
    name: 'audioFiles',
    component: resolve => require(['modules/audio/AudioFile.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/category_lesson',
    name: 'categoryLesson',
    component: resolve => require(['modules/lesson/CategoryLesson.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/subcategory_lesson',
    name: 'subCategoryLesson',
    component: resolve => require(['modules/lesson/SubCategoryLesson.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  }
  ]
}
