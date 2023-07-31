import axios from "@/plugins/axios";
const actions = {
  async getSettings({ commit }) {
    commit("SET_SETTING_LOADING", true);
    commit("SET_SETTINGS", []);
    const response = await axios.get("settings");
    commit("SET_SETTING_LOADING", false);
    let settings = response?.data?.data ?? [];
    commit("SET_SETTINGS", settings);
  },
  async getSetting({ commit }, id) {
    const response = await axios.get(`settings/${id}`);
    const { setting } = response?.data.data ?? {};
    commit("SET_SETTING", setting);
  },
  async updateSettings({}, data) {
    console.log("settingData", data);
    return await axios.post(`/settings`, data);
  },
  async getGeneralSettings({ commit }) {
    try {
      commit("SET_GENERAL_SETTING_LOADING", true);
      commit("SET_GENERAL_SETTING", []);
      const response = await axios.get("general-setting");
      commit("SET_GENERAL_SETTING_LOADING", false);
      let settings = response?.data?.data ?? [];
      commit("SET_GENERAL_SETTING", settings);
      return response;
    } catch (error) {
      console.log(error);
    }
  }
};

export default actions;
