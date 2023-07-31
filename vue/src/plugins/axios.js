import axios from "axios";
import Vue from "vue";
import { makeToast } from "@/helpers";
import store from "@/store";
import router from "@/router";
const token = localStorage.getItem("token");
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.common["Content-Type"] = "application/json";
axios.defaults.headers.common["Content-Type"] = "multipart/form-data";
// 'Content-Type': 'multipart/form-data',
axios.defaults.baseURL =
  process.env.VUE_APP_API_URL ?? "http://localhost:8000/api/v1";
axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
axios.interceptors.response.use(
  function (response) {
    // store.commit('setValidationError', []);


    const message = response?.data?.message || response?.data?.msg;
    // if (message) {
    //   makeToast("success", message);
    // }

    return response;
  },
  function (error) {
    const message =
      error?.response?.data?.message || error?.response?.data?.errors?.message;

    if (
      message &&
      typeof message == "string" &&
      error.response.status !== 401
    ) {
      makeToast("error", message);
    }
    // store.commit("setLoading", false);
    if (error.response && error.response.status === 422) {
      // if (typeof error.response.data.errors != 'undefined')
      // store.commit('setValidationError', error.response.data.errors);
    }
    // access denied
    else if (error.response && error.response.status === 403) {
      //   return router.push({ name: 'denied' });
    }
    // require login
    else if (error.response && error.response.status === 401) {
      // localStorage.removeItem("userInfo");
      // localStorage.removeItem("authInfo");
      // store.commit('setLogout');
      store.commit("auth/SET_TOKEN", "");
      store.commit("auth/SET_USER", {});
      axios.defaults.headers.common["Authorization"] = "";
      return router.push({ name: "auth-signin" });
    }

    // General errors / internal server error
    else if (error.response && error.response.status === 500) {
      // if (error.response.data.message) {
      //     store.commit('setError', null);
      //     store.commit('setError', {
      //         a: null,
      //         code: 500,
      //         message: error.response.data.message,
      //     });
      // }
    } else if (error.response && error.response.status === 520) {
      // store.dispatch('refreshUser');
      //   return router.push({ name: 'subscribe.index' });
    } else {
    }

    return Promise.reject(error);
  }
);

Vue.prototype.$axios = axios;

export default axios;
