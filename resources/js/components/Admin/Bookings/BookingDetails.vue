<template>
  <div class="booking-details-container">
    <h1 class="title">Booking Details</h1>

    <!-- Display basic booking information -->
    <div class="booking-info" v-if="booking">
      <div class="info-row">
        <div class="info-item"><strong>Booking ID:</strong> {{ booking.id }}</div>
        <div class="info-item"><strong>Customer Name:</strong> {{ booking.name }}</div>
      </div>
      <div class="info-row">
        <div class="info-item"><strong>Email:</strong> {{ booking.email }}</div>
        <div class="info-item"><strong>Phone:</strong> {{ booking.phone }}</div>
      </div>
      <div class="info-row">
        <div class="info-item"><strong>Service:</strong> {{ booking.service.name }}</div>
        <div class="info-item"><strong>Date:</strong> {{ booking.date }}</div>
      </div>
      <div class="info-row">
        <div class="info-item"><strong>Start Time:</strong> {{ booking.start_time }}</div>
        <div class="info-item"><strong>End Time:</strong> {{ booking.end_time }}</div>
      </div>
      <div class="info-row">
        <div class="info-item"><strong>Payment Method:</strong> {{ booking.payment_method }}</div>
        <div class="info-item"><strong>Total:</strong> ${{ booking.total }}</div>
      </div>
      <div class="info-row">
        <div class="info-item"><strong>Status:</strong> {{ booking.status }}</div>
        <div class="info-item"><strong>Paid Amount:</strong> ${{ booking.paid_amount }}</div>
      </div>
    </div>

    <!-- Back button to navigate to bookings list -->
    <button @click="goBack" class="btn-golden">Back to Bookings</button>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  data() {
    return {
      bookingId: this.$route.params.id, // Get booking ID from route params
    };
  },
  computed: {
    ...mapState('backendBookings', ['currentBooking']), // Map current booking from Vuex state
    booking() {
      return this.currentBooking;
    },
  },
  methods: {
    // Fetch booking details when the component is created
    fetchBookingDetails() {
      this.$store.dispatch('backendBookings/fetchBookingDetails', this.bookingId)
        .catch((error) => {
          console.error('Error fetching booking details:', error);
        });
    },

    // Go back to the bookings list
    goBack() {
      this.$router.push({ name: 'Bookings' });
    },
  },
  created() {
    this.fetchBookingDetails(); // Fetch booking details when the component is created
  },
};
</script>

<style scoped>
.booking-details-container {
  padding: 20px;
  max-width: 800px;
  margin: 0 auto;
}

.title {
  font-size: 28px;
  font-weight: bold;
  margin-bottom: 20px;
}

.booking-info {
  display: flex;
  flex-direction: column;
}

.info-row {
  display: flex;
  flex-wrap: wrap;
  margin-bottom: 10px;
}

.info-item {
  flex: 1 1 45%;
  padding: 8px 12px;
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  margin-right: 10px;
  margin-bottom: 10px;
  font-size: 16px;
  border-radius: 5px;
}

.info-item:last-child {
  margin-right: 0;
}

strong {
  font-weight: bold;
}

button {
  padding: 8px 12px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
  margin-top: 20px;
  font-size: 16px;
  border-radius: 5px;
}

button:hover {
  background-color: #0056b3;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .info-item {
    flex: 1 1 100%;
    margin-right: 0;
  }
}
</style>
