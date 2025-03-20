// store/modules/review.js

import axios from '../../../utils/axios.js';

const state = {
  reviews: [],
  loading: false,
  error: null,
};

const getters = {
  allReviews: (state) => state.reviews,
  isLoading: (state) => state.loading,
  errorMessage: (state) => state.error,
};

const actions = {
  async fetchReviews({ commit }) {
    commit('setLoading', true);
    try {
      const response = await axios.get('/admin/reviews');
      commit('setReviews', response.data);
      console.log('reviews',response.data);
      commit('setError', null);
    } catch (error) {
      commit('setError', error.response?.data?.error || 'Failed to fetch reviews');
    } finally {
      commit('setLoading', false);
    }
  },

  async createReview({ commit }, reviewData) {
    commit('setLoading', true);
    try {
      const response = await axios.post('/admin/reviews', reviewData);
      commit('addReview', response.data);
      commit('setError', null);
    } catch (error) {
      commit('setError', error.response?.data?.error || 'Failed to create review');
    } finally {
      commit('setLoading', false);
    }
  },

  async updateReview({ commit }, { id, reviewData }) {
    commit('setLoading', true);
    console.log('reviewData', reviewData);
    try {
      const response = await axios.put(`/admin/reviews/${id}`, reviewData);
      commit('updateReview', response.data);
      commit('setError', null);
    } catch (error) {
      commit('setError', error.response?.data?.error || 'Failed to update review');
    } finally {
      commit('setLoading', false);
    }
  },

async deleteReview({ commit }, reviewId) {
  console.log('Deleting review ID:', reviewId); // Log the review ID
  const response = await axios.delete(`/admin/reviews/${reviewId}`);
  console.log('Delete response:', response.data); // Log response data
  commit('removeReview', reviewId);
},

};

const mutations = {
  setReviews(state, reviews) {
    state.reviews = reviews;
  },
  addReview(state, review) {
    state.reviews.push(review);
  },
updateReview(state, updatedReview) {
  const index = state.reviews.findIndex((review) => review.id === updatedReview.id);
  if (index !== -1) {
    // Update only the necessary fields to preserve product and user data
    const existingReview = state.reviews[index];
    state.reviews.splice(index, 1, {
      ...existingReview,
      ...updatedReview, // Merge updated fields, but keep product and user intact
    });
  }
},

  removeReview(state, id) {
    state.reviews = state.reviews.filter((review) => review.id !== id);
  },
  setLoading(state, status) {
    state.loading = status;
  },
  setError(state, error) {
    state.error = error;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
