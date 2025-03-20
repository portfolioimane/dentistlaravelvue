// store/modules/homepageHeader.js

import axios from '../../../utils/axios.js';

const state = {
  header: null,
  error: null,
};

const getters = {
  getHeader: (state) => state.header,
  getError: (state) => state.error,
};

const actions = {
   async fetchHeaderFront({ commit }) {
    try {
      const response = await axios.get('/homepage-header'); // Updated API route with prefix
      commit('setHeader', response.data);
    } catch (error) {
      commit('setError', error.response ? error.response.data.message : 'An error occurred');
    }
  },
  async fetchHeader({ commit }) {
    try {
      const response = await axios.get('/admin/customize/homepage-header'); // Updated API route with prefix
      commit('setHeader', response.data);
    } catch (error) {
      commit('setError', error.response ? error.response.data.message : 'An error occurred');
    }
  },

  async updateHeader({ commit }, payload) {
    try {
      const response = await axios.post('/admin/customize/homepage-header', payload); // Updated API route with prefix
      commit('setHeader', response.data);
    } catch (error) {
      commit('setError', error.response ? error.response.data.message : 'An error occurred');
    }
  },
};

const mutations = {
  setHeader(state, header) {
    state.header = header;
  },
  setError(state, error) {
    state.error = error;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
