import axios from '../../utils/axios.js';

const state = {
  reviews: [],
  featuredReviews: [], // Added state to store featured reviews
};

const mutations = {
  SET_REVIEWS(state, reviews) {
    state.reviews = reviews;
  },
  SET_FEATURED_REVIEWS(state, featuredReviews) {
    state.featuredReviews = featuredReviews; // Mutation to set featured reviews
  },
};

const actions = {
  async fetchReviews({ commit }, serviceId) {
    try {
      const response = await axios.get(`/services/${serviceId}/reviews`);
      commit('SET_REVIEWS', response.data);
    } catch (error) {
      console.error('Error fetching reviews:', error);
    }
  },

  async fetchFeaturedReviews({ commit }) {
    try {
      const response = await axios.get('/reviews/latest-featured'); // Assuming the API endpoint is /reviews/latest-featured
      commit('SET_FEATURED_REVIEWS', response.data);
      console.log('featured',response.data); // Commit the featured reviews
    } catch (error) {
      console.error('Error fetching featured reviews:', error);
    }
  },

  async submitReview({ commit }, reviewData) {
    try {
      const response = await axios.post(`/services/${reviewData.serviceId}/reviews`, reviewData);
    } catch (error) {
      console.error('Error submitting review:', error);
    }
  },
};

const getters = {
  reviews: (state) => state.reviews,
  featuredReviews: (state) => state.featuredReviews, // Getter for featured reviews
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
