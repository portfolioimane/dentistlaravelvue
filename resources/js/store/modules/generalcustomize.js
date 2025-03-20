import axios from '../../utils/axios.js';

const state = {
  generalCustomizes: {},
};

const mutations = {
  SET_GENERAL_CUSTOMIZES(state, generalCustomizes) {
    state.generalCustomizes = generalCustomizes;
  },

};

const actions = {
  async fetchGeneralCustomizes({ commit }) {
    try {
      const response = await axios.get('/general-customizes');
      const generalCustomizes = response.data.reduce((acc, generalCustomize) => {
        acc[generalCustomize.key] = generalCustomize.value;
        return acc;
      }, {});
      commit('SET_GENERAL_CUSTOMIZES', generalCustomizes);
    } catch (error) {
      console.error('Error fetching general customizes:', error);
    }
  },

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
