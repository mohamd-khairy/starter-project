const mutations = {
  setNotifications: (state, value) => {
    let {data, current_page, last_page, count} = value;
    console.log("aaa");
    console.log(data);
    state.notifications.data = [...state.notifications.data, ...data];
    state.notifications.current_page = current_page;
    state.notifications.last_page = last_page;
    state.notifications.count = count;
  },
  addNotification: (state, value) => {
    state.notifications.data = [value, ...state.notifications.data];
  }
  readNotification(state, ids) => {
    state.notifications.data.forEach(notification => {
      if (ids.includes(notification.id)) {
        notification.read_at = new Date().toISOString();
      }
    });
  }
}

export default mutations
