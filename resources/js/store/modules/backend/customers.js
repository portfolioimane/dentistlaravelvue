import axios from '../../../utils/axios.js';

const state = {
  customers: [],
  customer: {
    id: null,
    name: '',
    email: '',
    phone: '',
    date_of_birth: null,
    gender: '',
    emergency_contact: '',
    medical_history: '',
    is_insured: false,
    insurance_provider: '',
    insurance_id: '',
  },
};

const mutations = {
  SET_CUSTOMERS(state, customers) {
    state.customers = customers;
  },
  SET_CUSTOMER(state, customer) {
    state.customer = { ...customer }; // Spread the customer object to ensure a fresh copy
  },
  UPDATE_CUSTOMER(state, updatedCustomer) {
    const index = state.customers.findIndex(customer => customer.id === updatedCustomer.id);
    if (index !== -1) {
      state.customers.splice(index, 1, { ...updatedCustomer });
    }
  },
  DELETE_CUSTOMER(state, customerId) {
    state.customers = state.customers.filter(customer => customer.id !== customerId);
  },
};

const actions = {
  async fetchCustomers({ commit }) {
    try {
      const response = await axios.get('/admin/patients');
      commit('SET_CUSTOMERS', response.data);
      console.log('allcustomers', response.data);
    } catch (error) {
      console.error('Error fetching customers:', error);
    }
  },
  async fetchCustomerById({ commit }, customerId) {
    try {
      const response = await axios.get(`/admin/patients/${customerId}`);
      commit('SET_CUSTOMER', response.data);
    } catch (error) {
      console.error('Error fetching customer:', error);
    }
  },
  async addCustomer({ dispatch }, formData) {
    try {
      await axios.post('/admin/patients', formData, {
        headers: {
          'Content-Type': 'multipart/form-data', // Ensure the header supports FormData
        },
      });

      dispatch('fetchCustomers');
    } catch (error) {
      console.error('Error adding customer:', error);
    }
  },
  async updateCustomer({ dispatch }, { id, formData }) {
    try {
      await axios.post(`/admin/patients/${id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        params: {
          _method: 'PUT', // Correct usage of PUT for update
        },
      });

      dispatch('fetchCustomers');
    } catch (error) {
      console.error('Error updating customer:', error);
    }
  },
  async deleteCustomer({ dispatch }, customerId) {
    try {
      await axios.delete(`/admin/patients/${customerId}`);
      dispatch('fetchCustomers');
    } catch (error) {
      console.error('Error deleting customer:', error);
    }
  },
};

const getters = {
  allCustomers: (state) => state.customers,
  currentCustomer: (state) => state.customer,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
