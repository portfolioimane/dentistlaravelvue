import axios from '../../../utils/axios.js';

const state = {
  generalCustomizes: {},
};

const mutations = {
  SET_GENERAL_CUSTOMIZES(state, generalCustomizes) {
    state.generalCustomizes = generalCustomizes;
  },
  SET_LOGO(state, logo) {
    state.generalCustomizes['logo'] = logo;
  }
};

const actions = {
  async fetchGeneralCustomizes({ commit }) {
    try {
      const response = await axios.get('/admin/general-customizes');
      const generalCustomizes = response.data.reduce((acc, generalCustomize) => {
        acc[generalCustomize.key] = generalCustomize.value;
        return acc;
      }, {});
      commit('SET_GENERAL_CUSTOMIZES', generalCustomizes);
    } catch (error) {
      console.error('Error fetching general customizes:', error);
    }
  },
  
  async updateGeneralCustomize({ commit }, updatedFields) {
    try {
      await axios.post('/admin/general-customizes/update-or-create', updatedFields);
      commit('SET_GENERAL_CUSTOMIZES', { ...updatedFields });
    } catch (error) {
      console.error('Error updating general customizes:', error);
    }
  },

  async updateLogo({ commit }, file) {
    try {
      const formData = new FormData();
      formData.append('logo', file);
      const response = await axios.post('/admin/general-customizes/upload-logo', formData);
      commit('SET_LOGO', response.data.logo);
    } catch (error) {
      console.error('Error updating logo:', error);
    }
  }
};

const getters = {
  getGeneralCustomize: (state) => (key) => {
    return state.generalCustomizes[key];
  }
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters
};
