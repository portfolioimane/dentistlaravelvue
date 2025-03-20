import axios from '../../../utils/axios.js';

const state = {
  services: [],
  service: {
    id: null,
    name: '',
    description: '',
    cost: 0.0,
    duration: 0,
    image: null,
    featured: false, // Add a featured flag if necessary
  },
};

const mutations = {
  SET_SERVICES(state, services) {
    state.services = services;
  },
  SET_SERVICE(state, service) {
    state.service = { ...service }; // Spread to ensure fresh copy
  },
  UPDATE_SERVICE(state, updatedService) {
    const index = state.services.findIndex(service => service.id === updatedService.id);
    if (index !== -1) {
      state.services.splice(index, 1, { ...updatedService });
    }
  },
  TOGGLE_FEATURED(state, serviceId) {
    const service = state.services.find(service => service.id === serviceId);
    if (service) {
      service.featured = !service.featured; // Toggle featured status
    }
  },
};

const actions = {
  async fetchServices({ commit }) {
    try {
      const response = await axios.get('/admin/services');
      commit('SET_SERVICES', response.data);
      console.log('allServices', response.data);
    } catch (error) {
      console.error('Error fetching services:', error);
    }
  },
  async fetchServiceById({ commit }, serviceId) {
    try {
      const response = await axios.get(`/admin/services/${serviceId}`);
      commit('SET_SERVICE', response.data);
    } catch (error) {
      console.error('Error fetching service:', error);
    }
  },
  async addService({ dispatch }, formData) {
    try {
      await axios.post('/admin/services', formData, {
        headers: {
          'Content-Type': 'multipart/form-data', // Ensure the header supports FormData
        },
      });

      dispatch('fetchServices');
    } catch (error) {
      console.error('Error adding service:', error);
    }
  },
  async updateService({ dispatch }, { id, formData }) {
    try {
      await axios.post(`/admin/services/${id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        params: {
          _method: 'PUT', // Correct usage of PUT for update
        },
      });

      dispatch('fetchServices');
    } catch (error) {
      console.error('Error updating service:', error);
    }
  },
  async deleteService({ dispatch }, serviceId) {
    try {
      await axios.delete(`/admin/services/${serviceId}`);
      dispatch('fetchServices');
    } catch (error) {
      console.error('Error deleting service:', error);
    }
  },
  async toggleFeatured({ commit, dispatch }, serviceId) {
    try {
      // Toggle featured status on the server
      await axios.put(`/admin/services/${serviceId}/toggle-featured`);
      
      // Update the local state
      commit('TOGGLE_FEATURED', serviceId);
      
      // Optionally refetch services if the server response isn't updated
      dispatch('fetchServices');
    } catch (error) {
      console.error('Error toggling featured service:', error);
    }
  },
};

const getters = {
  allServices: (state) => state.services,
  currentService: (state) => state.service,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
