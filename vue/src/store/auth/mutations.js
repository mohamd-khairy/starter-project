export default {
  SET_TOKEN(state, data) {
    state.token = data;
  },
  SET_USER(state, data) {
    state.user = data;
    localStorage.setItem("user", JSON.stringify(data));
  },
  SET_PERMISSIONS(state, data) {
    state.permissions = data;
    localStorage.setItem("user_permissions", JSON.stringify(data));
  }
};
