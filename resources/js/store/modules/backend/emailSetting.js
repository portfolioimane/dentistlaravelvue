import axios from '../../../utils/axios.js'; // Import axios instance (adjust the path if needed)

const state = {
  emailEnabled: false,
  mailProvider: '', // Generic mail provider (e.g., Mailgun, SendGrid, etc.)
  mailHost: '',
  mailPort: '',
  mailUsername: '',
  mailPassword: '',
  mailEncryption: '',
  mailFromAddress: '',
  mailFromName: '',
};

const mutations = {
  SET_EMAIL_SETTINGS(state, settings) {
    // Set email settings based on the provider (Mailgun, SendGrid, etc.)
    state.emailEnabled = settings.enabled;
    state.mailProvider = settings.provider;
    state.mailHost = settings.host;
    state.mailPort = settings.port;
    state.mailUsername = settings.username;
    state.mailPassword = settings.password;
    state.mailEncryption = settings.encryption || '';
    state.mailFromAddress = settings.from_address;
    state.mailFromName = settings.from_name;
  },
  UPDATE_EMAIL_SETTINGS(state, settings) {
    // Update email settings when modifying
    state.emailEnabled = settings.enabled;
    state.mailProvider = settings.provider;
    state.mailHost = settings.host;
    state.mailPort = settings.port;
    state.mailUsername = settings.username;
    state.mailPassword = settings.password;
    state.mailEncryption = settings.encryption;
    state.mailFromAddress = settings.from_address;
    state.mailFromName = settings.from_name;
  },
};

const actions = {
async fetchEmailSettings({ commit }) {
  try {
    const response = await axios.get('/admin/emailsettings');
    console.log('Raw API Response:', response.data); // Log API response
    
    const settings = response.data[0]; // Extract first item (if array)
    commit('SET_EMAIL_SETTINGS', settings);
    console.log('State after committing:', settings);
  } catch (error) {
    console.error('Error fetching email settings:', error);
  }
},


async updateEmailSettings({ commit }, emailSettings) {
  try {
    // Log the emailSettings being sent to the API
    console.log('Sending email settings to API:', emailSettings);

    const response = await axios.put('/admin/emailsettings/update', emailSettings);

    // Log the response from the API
    console.log('Response from API:', response);

    // Commit the mutation to update the state
    commit('UPDATE_EMAIL_SETTINGS', emailSettings);

  } catch (error) {
    // Log the error in case of failure
    console.error('Error updating email settings:', error);
  }
},

};

const getters = {
  isEmailEnabled: (state) => state.emailEnabled,
  mailProvider: (state) => state.mailProvider,
  mailHost: (state) => state.mailHost,
  mailPort: (state) => state.mailPort,
  mailUsername: (state) => state.mailUsername,
  mailPassword: (state) => state.mailPassword,
  mailEncryption: (state) => state.mailEncryption,
  mailFromAddress: (state) => state.mailFromAddress,
  mailFromName: (state) => state.mailFromName,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
