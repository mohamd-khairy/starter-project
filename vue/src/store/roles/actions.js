import axios from "@/plugins/axios";
const actions = {
  async getRoles({ commit }) {
    commit("SET_ROLES", []);
    const response = await axios.get("roles");
    const roles = response?.data?.data ?? [];
    commit("SET_ROLES", roles);
  },
  async getRole({ commit }, id) {
    const response = await axios.get(`roles/${id}`);
    const { role, permissions } = response?.data.data ?? {};
    commit("SET_PERMISSIONS", permissions);
    commit("SET_ROLE", role);
  },
  async updateRole({}, data) {
    const { id } = data;
    await axios.post(`/roles/${id}`, data);
  },
  async getPermissions({ commit }) {
    const response = await axios.get(`roles/permissions`);
    const { permissions } = response?.data.data ?? {};
    commit("SET_PERMISSIONS", permissions);
  },
  async storeRole({ commit }, data) {
    const response = await axios.post("roles", data);
    console.log(response?.data);
  },
  async removeRole({}, id) {
    await axios.delete(`/roles/${id}`);
  }
};

export default actions;
