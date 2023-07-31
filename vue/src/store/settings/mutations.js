const mutations = {
  SET_SETTINGS(state, value) {
    state.settings = value;
  },
  SET_SETTING(state, value) {
    state.setting = value;
  },
  SET_SETTING_LOADING(state, value) {
    state.settingLoading = value;
  },
  SET_GENERAL_SETTING(state, value) {
    state.generalSettings = value;
  },
  SET_GENERAL_SETTING_LOADING(state, value) {
    state.settingLoading = value;
  }
};
export default mutations;
