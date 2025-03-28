import axios from '../../../utils/axios.js'; // Import axios instance (adjust the path if needed)

const state = {
  smsEnabled: false,
  smsProvider: 'twilio', // Default to Twilio
  smsSid: '',
  smsAuthToken: '',
  smsPhoneNumber: '',
};

const mutations = {
  SET_SMS_SETTINGS(state, settings) {
    state.smsEnabled = settings.enabled;
    state.smsProvider = settings.provider;
    state.smsSid = settings.sid;
    state.smsAuthToken = settings.auth_token;
    state.smsPhoneNumber = settings.phone_number;
  },
  UPDATE_SMS_SETTINGS(state, settings) {
    state.smsEnabled = settings.enabled;
    state.smsProvider = settings.provider;
    state.smsSid = settings.sid;
    state.smsAuthToken = settings.auth_token;
    state.smsPhoneNumber = settings.phone_number;
  },
};

const actions = {
  async fetchSmsSettings({ commit }) {
    try {
      const response = await axios.get('/admin/smssettings');
         const settings = response.data[0];
      commit('SET_SMS_SETTINGS', settings);

        } catch (error) {
      console.error('Error fetching SMS settings:', error);
    }
  },

  async updateSmsSettings({ commit }, smsSettings) {
    try {
      const response = await axios.put('/admin/smssettings/update', smsSettings);
      commit('UPDATE_SMS_SETTINGS', smsSettings);
    } catch (error) {
      console.error('Error updating SMS settings:', error);
    }
  },
};

const getters = {
  isSmsEnabled: (state) => state.smsEnabled,
  smsProvider: (state) => state.smsProvider,
  smsSid: (state) => state.smsSid,
  smsAuthToken: (state) => state.smsAuthToken,
  smsPhoneNumber: (state) => state.smsPhoneNumber,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
