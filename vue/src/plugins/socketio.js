// import VueSocketIO from 'vue-socket.io'
// import Vue from 'vue'
// import SocketIO from 'socket.io-client'

// const socketConnection = SocketIO('http://localhost:3000');

// Vue.use(new VueSocketIO({
//   debug: true,
//   connection:socketConnection

//   // vuex: {
//   //   store,
//   //   actionPrefix: 'SOCKET_',
//   //   mutationPrefix: 'SOCKET_'
//   // },
//   // options: { path: "/my-app/" } //Optional options
// }))

import VueSocketio from "vue-socket.io";
import socketio from "socket.io-client";
import Vue from "vue";
Vue.use(VueSocketio, socketio("https://drones-node.wakeb.tech"));
// Vue.use(VueSocketio, socketio("http://localhost:3000"));
