import axios from '../../../utils/axios.js';

const state = {
  buisnessHours: {
    monday: { open_time: '', close_time: '' },
    tuesday: { open_time: '', close_time: '' },
    wednesday: { open_time: '', close_time: '' },
    thursday: { open_time: '', close_time: '' },
    friday: { open_time: '', close_time: '' },
    saturday: { open_time: '', close_time: '' },
    sunday: { open_time: '', close_time: '' },
  },
};

const getters = {
  buisnessHours: (state) => state.buisnessHours,
};

const actions = {
  // Fetch buisness hours from the backend
  async fetchBuisnessHours({ commit }) {
    try {
      const response = await axios.get('/admin/buisnesshours');
      const buisnessHours = response.data;
      commit('setBuisnessHours', buisnessHours);
      console.log('buisnesshours', response.data);
    } catch (error) {
      console.error('Error fetching buisness hours:', error);
      throw error;
    }
  },

  // Update buisness hours
  async updateBuisnessHours({ commit }, buisnessHours) {
    try {
      const response = await axios.post('/admin/buisnesshours', buisnessHours);
      commit('setBuisnessHours', buisnessHours);
      return response.data;
    } catch (error) {
      console.error('Error updating buisness hours:', error);
      throw error;
    }
  },
};

const mutations = {
  setBuisnessHours(state, buisnessHours) {
    state.buisnessHours = buisnessHours;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
