// store/contact.js
import axios from '../../utils/axios.js';

const state = {
  successMessage: '',
  errorMessage: '',
};

const getters = {
  successMessage: (state) => state.successMessage,
  errorMessage: (state) => state.errorMessage,
};

const mutations = {
  setSuccessMessage(state, message) {
    state.successMessage = message;
  },
  setErrorMessage(state, message) {
    state.errorMessage = message;
  },
};

const actions = {
  async sendContactMessage({ commit }, formData) {
    try {
      const response = await axios.post('/contact', formData);
      commit('setSuccessMessage', response.data.message);
      commit('setErrorMessage', ''); // Clear previous errors
    } catch (error) {
      commit('setSuccessMessage', ''); // Clear previous success messages
      commit('setErrorMessage', error.response.data.message || 'Something went wrong!');
    }
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
