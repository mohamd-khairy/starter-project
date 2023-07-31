import axios from "@/plugins/axios";

const actions = {
  async getDrones({ commit }, data) {
    const response = await axios.get("drones", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
      },
    });
    const drones = response?.data.data.data;
    const statistics = response?.data.data.statistics;
    commit("SET_DRONES", drones);
    commit("SET_statistics", statistics);
  },
};

export default actions;
