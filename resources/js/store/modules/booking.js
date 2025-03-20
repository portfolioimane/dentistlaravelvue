import axios from "../../utils/axios.js";

const state = {
  bookings: [],
  availableSlots: [],
  totalBookings: 0,
};

const mutations = {
  setBookings(state, bookings) {
    state.bookings = bookings;
  },
  addBooking(state, booking) {
    state.bookings.push(booking);
  },
    SET_AVAILABLE_SLOTS(state, slots) {
    state.availableSlots = slots;
  },
};

const actions = {
  async fetchBookings({ commit }) {
    try {
      const response = await axios.get("/bookings");
      commit("setBookings", response.data);
    } catch (error) {
      console.error("Error fetching bookings:", error);
    }
  },
    async fetchAvailableSlots({ commit }, { date, service_id }) {
    if (!date || !service_id) return;
    try {

      const response = await axios.get('/available-slots', {
        params: { date, service_id },
      });
      commit('SET_AVAILABLE_SLOTS', response.data);
    } catch (error) {
      console.error('Error fetching available slots:', error.response?.data || error.message);
    }
  },
async fetchPaginatedBookings({ commit }, { page = 1, perPage = 5 }) {
  try {
    const response = await axios.get(`/mybookings?page=${page}&per_page=${perPage}`);
    // Access the bookings directly from response.data.bookings
      commit("setBookings", response.data.bookings.data); // Assuming the API returns the data in this structure    console.log('bookings', response.data.bookings);
  } catch (error) {
    console.error("Error fetching bookings:", error);
    commit("setBookings", []); // Set empty array on error
  }
},


  async submitBooking({ commit }, bookingData) {
    try {
      const response = await axios.post("/bookings/create", bookingData);
      commit("addBooking", response.data.booking);
      return response.data.booking;
    } catch (error) {
      console.error("Error submitting booking:", error);
      throw error;
    }
  },

  async fetchBookingById(_, bookingId) {
    try {
      const response = await axios.get(`/bookings/${bookingId}`);
      return response.data;
    } catch (error) {
      console.error("Error fetching booking by ID:", error);
      throw error;
    }
  },

  // Create Stripe Payment Intent
  async createStripePayment(_, totalAmount) {
    try {
      const response = await axios.post("/bookings/create-stripe-payment", { total: totalAmount });
      return response.data;
    } catch (error) {
      console.error("Error creating Stripe payment:", error);
      throw error;
    }
  },

  // Confirm PayPal Payment
  async confirmPayPalPayment(_, paypalOrderId) {
    try {
      const response = await axios.post("/bookings/confirm-paypal-payment", { paypalOrderId });
      return response.data;
    } catch (error) {
      console.error("Error confirming PayPal payment:", error);
      throw error;
    }
  },
};

const getters = {
  allBookings: (state) => state.bookings,
  bookingCount: (state) => state.totalBookings,
  availableSlots: (state) => state.availableSlots,

};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
