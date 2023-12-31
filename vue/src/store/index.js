import Vue from 'vue'
import Vuex from 'vuex'

// Global vuex
import AppModule from './app'
import Users from './users'
import Auth from "./auth";
import Roles from "./roles";
import Settings from "./settings";
import Locations from "./locations";
import Types from "./types";
import Events from "./events";
import Reports from "./reports";
import Drones from "./drones";
import Flights from "./flights";

// Example Apps
import BoardModule from '../apps/board/store'
import EmailModule from '../apps/email/store'
import TodoModule from '../apps/todo/store'

Vue.use(Vuex)
Vue.directive('can', function (el, binding, vnode) {
  if (localStorage.getItem('user_permissions').indexOf(binding.value) !== -1) {
    return vnode.elm.style.display = "inline-flex";
  } else {
    return vnode.elm.style.display = "none";
    // return vnode.elm.hidden = true;
  }
})

/**
 * Main Vuex Store
 */
const store = new Vuex.Store({
  modules: {
    app: AppModule,
    'board-app': BoardModule,
    'email-app': EmailModule,
    'todo-app': TodoModule,
    users: Users,
    roles: Roles,
    settings: Settings,
    locations: Locations,
    types: Types,
    events: Events,
    reports: Reports,
    drones: Drones,
    flights: Flights,
    auth: Auth
  }
})

export default store
