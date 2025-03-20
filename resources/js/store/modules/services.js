import axios from '../../utils/axios.js';

const state = {
    services: [], // Array to hold services data
    selectedService: null, // Store the selected service data
    loading: false,
    error: null,
    featuredServices: [], // New state property for featured services
};

const getters = {
    allServices: (state) => state.services,
    selectedService: (state) => state.selectedService,
    isLoading: (state) => state.loading,
    error: (state) => state.error,
    featuredServices: (state) => state.featuredServices, // Getter for featured services
};

const actions = {
    // Action to fetch all services
    async fetchServices({ commit }) {
        commit('setLoading', true);
        commit('setError', null);
        try {
            const response = await axios.get('/services'); // Assuming your API endpoint is '/services'
            commit('setServices', response.data); // Store fetched services
        } catch (error) {
            commit('setError', error.response ? error.response.data.message : error.message);
        } finally {
            commit('setLoading', false);
        }
    },

    // Action to fetch featured services
    async fetchFeaturedServices({ commit }) {
        commit('setLoading', true);
        commit('setError', null);
        try {
            const response = await axios.get('/services/featured'); // Assuming your API endpoint is '/services/featured'
            commit('setFeaturedServices', response.data); // Store fetched featured services
        } catch (error) {
            commit('setError', error.response ? error.response.data.message : error.message);
        } finally {
            commit('setLoading', false);
        }
    },

    // Action to fetch a single service by ID
    async fetchServiceById({ commit }, serviceId) {
        commit('setLoading', true);
        commit('setError', null);
        try {
            const response = await axios.get(`/services/${serviceId}`); // Fetch a single service by ID
            commit('setSelectedService', response.data); // Store the selected service
        } catch (error) {
            commit('setError', error.response ? error.response.data.message : error.message);
        } finally {
            commit('setLoading', false);
        }
    },
};

const mutations = {
    setServices(state, services) {
        state.services = services; // Set the fetched services data
    },
    setSelectedService(state, service) {
        state.selectedService = service; // Set the selected service data
    },
    setFeaturedServices(state, featuredServices) {
        state.featuredServices = featuredServices; // Set the fetched featured services
    },
    setLoading(state, loading) {
        state.loading = loading; // Set the loading state
    },
    setError(state, error) {
        state.error = error; // Set any errors encountered
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
