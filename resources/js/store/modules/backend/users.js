// store/modules/users.js
import axios from '../../../utils/axios.js';

const state = {
  users: [], // Array to store the list of users
  currentUser: null, // To store the currently logged-in user
  customers: [], // Array to store the list of customers
  loading: false, // Flag to show loading state
  error: null, // To store error messages
};

const getters = {
  allUsers: (state) => state.users, // Get all users
  currentUser: (state) => state.currentUser, // Get current user
  allCustomers: (state) => state.customers, // Get all customers
  loading: (state) => state.loading, // Get loading state
  error: (state) => state.error, // Get error state
};

const mutations = {
  setUsers: (state, users) => {
    state.users = users; // Set users to the state
  },
  setCurrentUser: (state, user) => {
    state.currentUser = user; // Set current user to the state
  },
  setCustomers: (state, customers) => {
    state.customers = customers; // Set customers to the state
  },
  setLoading: (state, loading) => {
    state.loading = loading; // Set loading state
  },
  setError: (state, error) => {
    state.error = error; // Set error state
  },
  clearUsers: (state) => {
    state.users = []; // Clear users array
  },
  clearCurrentUser: (state) => {
    state.currentUser = null; // Clear current user
  },
  clearCustomers: (state) => {
    state.customers = []; // Clear customers array
  },
};

const actions = {
  async fetchUsers({ commit }) {
    commit('setLoading', true);
    try {
      const response = await axios.get('/admin/users'); // Fetch users from API
      commit('setUsers', response.data);
    } catch (error) {
      commit('setError', error.message);
    } finally {
      commit('setLoading', false);
    }
  },
  async fetchCurrentUser({ commit }) {
    commit('setLoading', true);
    try {
      const response = await axios.get('/admin/current-user'); // Fetch current user from API
      commit('setCurrentUser', response.data);
    } catch (error) {
      commit('setError', error.message);
    } finally {
      commit('setLoading', false);
    }
  },
  async fetchCustomers({ commit }) {
    commit('setLoading', true);
    try {
      const response = await axios.get('/admin/users/customers'); // Fetch customers from API
      commit('setCustomers', response.data); // Commit customers to the state
      } catch (error) {
      commit('setError', error.message);
    } finally {
      commit('setLoading', false);
    }
  },
  async addUser({ commit, state }, user) {
    commit('setLoading', true);
    try {
      const response = await axios.post('/admin/users', user); // Add new user via API
      commit('setUsers', [...state.users, response.data]); // Add new user to the users array
    } catch (error) {
      commit('setError', error.message);
    } finally {
      commit('setLoading', false);
    }
  },
  async updateUser({ commit, state }, user) {
    commit('setLoading', true);
    try {
      const response = await axios.put(`/admin/users/${user.id}`, user); // Update user via API
      const updatedUsers = state.users.map((u) =>
        u.id === user.id ? response.data : u
      );
      commit('setUsers', updatedUsers); // Update the users array
    } catch (error) {
      commit('setError', error.message);
    } finally {
      commit('setLoading', false);
    }
  },
  async deleteUser({ commit, state }, userId) {
    commit('setLoading', true);
    try {
      await axios.delete(`/admin/users/${userId}`); // Delete user via API
      commit('setUsers', state.users.filter((user) => user.id !== userId)); // Remove user from the list
    } catch (error) {
      commit('setError', error.message);
    } finally {
      commit('setLoading', false);
    }
  },
  clearUserData({ commit }) {
    commit('clearUsers'); // Clear users data
    commit('clearCurrentUser'); // Clear current user data
    commit('clearCustomers'); // Clear customers data
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
