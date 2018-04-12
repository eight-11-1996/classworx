
export default{
  routes: [{
    path: '/resources/:id',
    name: 'resources',
    component: resolve => require(['modules/resource/ResourceManager.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  },
  {
    path: '/resources_viewer',
    name: 'resources_viewer',
    component: resolve => require(['modules/resource/ResourceViewer.vue'], resolve),
    meta: {
      tokenRequired: true
    }
  }]
}
