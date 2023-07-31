export default [{
  path: '/flights',
  name: 'flights-list',
  meta: {
    auth: true
  },
  component: () => import(/* webpackChunkName: "flights-list" */ '@/pages/flights/FlightsPage.vue')
},
{
  path: '/flights/locations',
  name: 'flights-list',
  meta: {
    auth: true
  },
  exact: true,
  component: () => import(/* webpackChunkName: "flights-list" */ '@/pages/flights/FlightLocations.vue')
},
{
  path: '/flights/location/:id',
  name: 'flights-live',
  meta: {
    auth: true,
    permissions: 'read-event'
  },
  component: () => import(/* webpackChunkName: "pipes-list" */ '@/pages/flights/FlightLive.vue')
},
{
  path: '/flights/show',
  name: 'flights-show',
  meta: {
    auth: true
  },
  component: () => import(/* webpackChunkName: "flights-show" */ '@/pages/flights/ShowPage.vue')
}]
