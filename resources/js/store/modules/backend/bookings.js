import axios from '../../../utils/axios.js';

const state = {
  bookings: [],
  currentBooking: null,
};

const mutations = {
  SET_BOOKINGS(state, bookings) {
    state.bookings = bookings;
  },
  SET_CURRENT_BOOKING(state, booking) {
    state.currentBooking = booking;
  },
  REMOVE_BOOKING(state, bookingId) {
    state.bookings = state.bookings.filter(booking => booking.id !== bookingId);
  },
};

const actions = {
  // Fetch all bookings
  async fetchBookings({ commit }) {
    try {
      const response = await axios.get('/admin/bookings');
      commit('SET_BOOKINGS', response.data.bookings);
      console.log('Bookings fetched:', response.data.bookings);
    } catch (error) {
      console.error('Error fetching bookings:', error);
    }
  },

  // Fetch details of a specific booking
  async fetchBookingDetails({ commit }, bookingId) {
    try {
      const response = await axios.get(`/admin/bookings/${bookingId}`);
      commit('SET_CURRENT_BOOKING', response.data.booking);
    } catch (error) {
      console.error('Error fetching booking details:', error);
    }
  },

  // Create a new booking
  async createBooking({ dispatch }, bookingData) {
    try {
      const response = await axios.post('/admin/bookings', bookingData);
      console.log('Booking created:', response.data.booking);
      dispatch('fetchBookings'); // Refresh the bookings list
      return response.data.booking;
    } catch (error) {
      console.error('Error creating booking:', error);
      throw error;
    }
  },

  // Update an existing booking
  async updateBooking({ dispatch }, { bookingId, status }) {
    try {
      const response = await axios.put(`/admin/bookings/${bookingId}/status`, { status });
      console.log('Booking updated:', response.data.booking);
      dispatch('fetchBookings'); // Refresh the bookings list
      return response.data.booking;
    } catch (error) {
      console.error('Error updating booking:', error);
      throw error;
    }
  },

  // Delete a booking
  async deleteBooking({ commit }, bookingId) {
    try {
      await axios.delete(`/admin/bookings/${bookingId}`);
      commit('REMOVE_BOOKING', bookingId);
      console.log('Booking deleted:', bookingId);
    } catch (error) {
      console.error('Error deleting booking:', error);
      throw error;
    }
  },
};

const getters = {
  bookings: (state) => state.bookings,
  currentBooking: (state) => state.currentBooking,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
