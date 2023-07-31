import axios from "@/plugins/axios";

const actions = {
  async getNotifications({ commit }) {
    try {
      let response = await axios.get(`notifications`);
      const { notifications, count } = response?.data.data;
      console.log(notifications);
      commit("setNotifications", {
        data: notifications.data,
        current_page: notifications.current_page,
        last_page: notifications.last_page,
        count,
        noMoreItems: !notifications.next_page_url ? true : false
      });
    } catch (error) {
      console.log(error);
    }
  },
  addNotification({ commit }, notification) {
    commit("addNotification", notification);
  },
  readNotification({commit}, ids){
    commit("readNotification", ids);
  },
  async loadMoreNotifications({ commit }, page) {
    let response = await axios.get(`notifications?page=${page}`);
    const { notifications, count } = response?.data.data;
    console.log(notifications);
    commit("setNotifications", {
      data: notifications.data,
      current_page: notifications.current_page,
      last_page: notifications.last_page,
      count,
      noMoreItems: !notifications.next_page_url ? true : false
    });
  }
};


export default actions;
