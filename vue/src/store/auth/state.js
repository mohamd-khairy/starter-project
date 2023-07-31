export default {
  token: localStorage.getItem("token") ?? null,
  user: localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user")) : {},
  permissions: localStorage.getItem("user_permissions") ?? []
};
