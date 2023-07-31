import axios from '@/plugins/axios'

const actions = {
  async getDetectionTypes({commit}, data) {
    const response = await axios.get("detection-types-index", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn
      }
    });
    const types = response?.data
    commit('SET_TYPES', types)
  },
  async getDetectionType({commit}, id) {
    const response = await axios.get(`detection-types/${id}`)
    const type = response?.data.data
    commit('SET_TYPE', type)
  },
  async storeDetectionType({commit}, data) {
    return await axios.post("detection-types", data);
  },
  async updateDetectionType({state}, data) {
    const {id} = state?.type ?? {}
    return await axios.post(`detection-types/${id}`, data)
  },
  async deleteDetectionType({commit, dispatch}, id) {
    return await axios.delete(`detection-types/${id}`)
  },
  async deleteAll({commit}, data) {
    return await axios.get("detection-types/actions", {
      params: {
        ids: data.ids,
        action: data.action,
        value: data.value
      }
    });
  },
}

export default actions
