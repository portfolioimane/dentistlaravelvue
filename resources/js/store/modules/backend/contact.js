// store/contact.js
import axios from '../../../utils/axios.js';

const state = {
  contactMessages: [],  // Store the contact messages
  successMessage: '',
  errorMessage: '',
};

const getters = {
  // Getter to access the contact messages
  getContactMessages: state => state.contactMessages,
};

const mutations = {
  // Mutation to set the contact messages
  SET_CONTACT_MESSAGES(state, messages) {
    state.contactMessages = messages;
  },
  // Mutation for success message
  SET_SUCCESS_MESSAGE(state, message) {
    state.successMessage = message;
  },
  // Mutation for error message
  SET_ERROR_MESSAGE(state, message) {
    state.errorMessage = message;
  },
};

const actions = {
  // Action to fetch contact messages from the API
  fetchMessages({ commit }) {
    axios.get('/admin/contact-messages')
      .then(response => {
        commit('SET_CONTACT_MESSAGES', response.data); // Store the messages
      })
      .catch(error => {
        console.error('There was an error fetching the messages:', error);
        commit('SET_ERROR_MESSAGE', 'Error fetching contact messages');
      });
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
