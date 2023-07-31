import Echo from "laravel-echo";
import Vue from "vue";
import state from "../store/auth/state";
window.Pusher = require("pusher-js");
let token = state.token;
// let token = "";

let ecoUrl = process.env.VUE_APP_API_URL ?? "http://127.0.0.1:8000/broadcasting/auth";
let wsHost = process.env.VUE_APP_WS_HOST ?? window.location.hostname;
const echo = new Echo({
  broadcaster: "pusher",
  key: "bae3160ce349d284eace",
  cluster: 'mt1',
  wsHost: wsHost,
  wsPort: 6001,
  wssPort: 6001,
  encrypted: false,
  disableStats: true,
  authEndpoint: `${ecoUrl}/broadcasting/auth`,
  auth: { headers: { Authorization: "Bearer " + token } },
  enabledTransports: ["ws", "wss", "websocket", "polling", "flashsocket"]
});

// const echo = new Echo({
//   broadcaster: "pusher",
//   key: "bae3160ce349d284eace",
//   cluster: "mt1",
//   wsHost: process.env.VUE_APP_WS_HOST ?? window.location.hostname,
//   wsPort: 6001,
//   wssPort: 6001,
//   disableStats: true,
//   enabledTransports: ["ws", "wss"],
//   forceTLS: true,
//   transports: ["websocket", "polling", "flashsocket"]
// });

Vue.prototype.$echo = echo;
export default echo;
