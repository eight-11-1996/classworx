
export default{
  routes: [{
    path: '/resources/:id',
    name: 'resources',
    component: resolve => require(['modules/resource/ResourceManager.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  }]
}
