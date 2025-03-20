import axios from '../../utils/axios.js';

const state = {
  user: null,
  token: sessionStorage.getItem('user-token') || '',  // Retrieve token from sessionStorage
  authChecked: false,
};

const mutations = {
  SET_USER(state, user) {
    state.user = user;
  },
  SET_TOKEN(state, token) {
    state.token = token;
  },
  SET_AUTH_CHECKED(state, value) {
    state.authChecked = value;
  },
};

const actions = {
async login({ commit }, credentials) {
  try {
    console.log('Sending login request with credentials:', credentials);
    
    const { data } = await axios.post('/login', credentials); // Send credentials in request body
    const { user, token } = data;

    // Commit user data to Vuex
    commit('SET_USER', user);
    commit('SET_TOKEN', token);

    // Store token in sessionStorage for persistence
    sessionStorage.setItem('user-token', token);

    console.log('Login successful, received data:', data);
  } catch (error) {
    console.error('Login failed:', error.response?.data || error.message);
    throw error.response?.data || error.message;
  }
},


async register({ dispatch }, userData) {
  try {
    const { data } = await axios.post('/register', userData);
    console.log('Registration successful:', data);


    // Auto-login after registration with session_id
    const loginData = {
      email: userData.email,
      password: userData.password,
    };
    
    await dispatch('login', loginData);
  } catch (error) {
    console.error('Registration failed:', error.response?.data || error.message);
    throw error.response?.data || error.message;
  }
},


  logout({ commit }) {
    commit('SET_TOKEN', null);
    commit('SET_USER', null);
    sessionStorage.removeItem('user-token');

    console.log('Logout successful');
  },

  async checkAuth({ commit }) {
    commit('SET_AUTH_CHECKED', false);

    const token = sessionStorage.getItem('user-token');
    if (token) {
      commit('SET_TOKEN', token);
      try {
        const { data } = await axios.get('/user');
        commit('SET_USER', data.user);
      } catch (error) {
        console.error('Failed to fetch user data:', error.response?.data || error.message);
        commit('SET_USER', null);
        commit('SET_TOKEN', '');
        sessionStorage.removeItem('user-token');
      }
    } else {
      commit('SET_USER', null);
      commit('SET_TOKEN', '');
    }

    commit('SET_AUTH_CHECKED', true);
  },


  async resetPassword({ commit }, { email, password, token }) {
    try {
      const response = await axios.post('/password/reset', {
        email,
        password,
        password_confirmation: password,
        token,
      });
      console.log('Password reset successful:', response.data);
      return response.data;
    } catch (error) {
      console.error('Password reset failed:', error.response.data);
      throw error.response.data;
    }
  },

  async sendPasswordResetLink({ commit }, { email }) {
    try {
      const response = await axios.post('/password/email', { email });
      return response.data;
    } catch (err) {
      throw err;
    }
  },

  async updateUser({ commit, state }, userData) {
    try {
      const response = await axios.post('/user', userData);
      const updatedUser = response.data.user;
      commit('SET_USER', updatedUser);
      console.log('Profile updated successfully:', updatedUser);
      return updatedUser;
    } catch (error) {
      console.error('Failed to update profile:', error.response.data);
      throw error.response.data;
    }
  },
};

const getters = {
  isAuthenticated: (state) => !!state.token,
  user: (state) => state.user,
  authChecked: (state) => state.authChecked,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
