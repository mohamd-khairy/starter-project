import axios from '@/plugins/axios'

const actions = {
  async getLocations({ commit }) {
    const response = await axios.get(`location`)
    const locations = response?.data.data
    commit('SET_LOCATIONS', locations)
  },
  async getLocation({ commit }, id) {
    const response = await axios.get(`location/${id}`)
    const location = response?.data.data
    commit('SET_LOCATION', location)
  },
  async updateLocation({ state }, form) {
    const { id } = state?.location ?? {}
    await axios.post(`location/${id}`, form)
  },
}

export default actions
