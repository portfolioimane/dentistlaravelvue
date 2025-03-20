import axios from '../../utils/axios.js';

const state = {
  stripePublicKey: null,
  paypalPublicKey: null,
  isStripeEnabled: false, // Add default value
  isPaypalEnabled: false, // Add default value
};

const mutations = {
  SET_STRIPE_PUBLIC_KEY(state, key) {
    state.stripePublicKey = key;
  },
  SET_PAYPAL_PUBLIC_KEY(state, key) {
    state.paypalPublicKey = key;
  },
  SET_STRIPE_ENABLED(state, enabled) {
    state.isStripeEnabled = enabled; // Mutate stripe enabled status
  },
  SET_PAYPAL_ENABLED(state, enabled) {
    state.isPaypalEnabled = enabled; // Mutate paypal enabled status
  },
};

const actions = {
  // Fetch Stripe public key and status
  async fetchStripePublicKey({ commit }) {
    try {
      const response = await axios.get('/stripe/public-key');
      commit('SET_STRIPE_PUBLIC_KEY', response.data.publicKey);
      console.log('fetchedstripekey',response.data.publicKey);
      commit('SET_STRIPE_ENABLED', response.data.isStripeEnabled); // Commit the enabled status
    } catch (error) {
      console.error('Error fetching Stripe public key:', error);
    }
  },

  // Fetch PayPal public key and status
  async fetchPaypalPublicKey({ commit }) {
    try {
      const response = await axios.get('/paypal/public-key');
      commit('SET_PAYPAL_PUBLIC_KEY', response.data.publicKey);
      commit('SET_PAYPAL_ENABLED', response.data.isPaypalEnabled); // Commit the enabled status
    } catch (error) {
      console.error('Error fetching PayPal public key:', error);
    }
  },
};

const getters = {
  getStripePublicKey: (state) => state.stripePublicKey,
  getPaypalPublicKey: (state) => state.paypalPublicKey,
  isStripeEnabled: (state) => state.isStripeEnabled, // Getter for Stripe enabled status
  isPaypalEnabled: (state) => state.isPaypalEnabled, // Getter for PayPal enabled status
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
