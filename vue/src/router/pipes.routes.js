export default [{
  path: '/models',
  name: 'pipes-list',
  meta: {
    auth: true,
    permissions: 'read-event'
  },
  component: () => import(/* webpackChunkName: "pipes-list" */ '@/pages/models/IndexPage.vue')
},
{
  path: '/models/show/:id',
  name: 'models-show',
  meta: {
    auth: true,
    permissions: 'read-event'
  },
  component: () => import(/* webpackChunkName: "pipes-list" */ '@/pages/models/drones/PipesPage.vue')
},
{
  path: '/models/notes/:id',
  name: 'models-notes',
  meta: {
    auth: true,
    permissions: 'read-event'
  },
  component: () => import(/* webpackChunkName: "pipes-list" */ '@/pages/models/drones/Notes.vue')
}]
