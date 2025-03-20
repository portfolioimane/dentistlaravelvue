<template>
  <div v-if="isLoading" class="loading-state">
    <p>Loading bookings...</p>
  </div>
  <div v-else>
    <h2>Your Bookings</h2>
    <div v-if="bookings.length === 0">
      <p>No bookings found.</p>
    </div>
    <div v-else>
      <table class="bookings-table">
        <thead>
          <tr>
            <th>Service</th>
            <th>Date</th>
            <th>Slot Time</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Payment Method</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="booking in bookings" :key="booking.id">
            <td>{{ booking.service.name }}</td>
            <td>{{ booking.date }}</td>
            <td>{{ booking.start_time }} - {{ booking.end_time }}</td>
            <td>{{ booking.name }}</td>
            <td>{{ booking.email }}</td>
            <td>{{ booking.phone }}</td>
            <td>{{ booking.status }}</td>
            <td>{{ booking.payment_method }}</td>
         
          </tr>
        </tbody>
      </table>

      <!-- Pagination Controls -->
      <div class="pagination">
        <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage >= totalPages">Next</button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data() {
    return {
      isLoading: true,
      currentPage: 1,
      totalPages: 1,
    };
  },
  computed: {
    ...mapGetters("booking", ["allBookings", "bookingCount"]),
    bookings() {
      return this.allBookings;
    },
  },
  methods: {
    ...mapActions("booking", ["fetchPaginatedBookings"]),
    
    async fetchBookings() {
      this.isLoading = true;
      try {
        await this.fetchPaginatedBookings({ page: this.currentPage, perPage: 5 });
        const count = this.$store.getters["booking/bookingCount"];
        console.log("Booking Count:", count); // Debugging
        this.totalPages = count ? Math.ceil(count / 5) : 1; // Ensure it’s not NaN
      } catch (error) {
        console.error("Error fetching bookings:", error);
      }
      this.isLoading = false;
    },

    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
        this.fetchBookings();
      }
    },

    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        this.fetchBookings();
      }
    },
  },
  async mounted() {
    await this.fetchBookings();
  },
};
</script>

<style scoped>
.bookings-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.bookings-table th,
.bookings-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.bookings-table th {
  background-color: #f4f4f4;
}

.bookings-table button {
  background-color: var(--primary-color);
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.bookings-table button:hover {
  background-color: #999999;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.pagination button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 8px 12px;
  margin: 0 5px;
  cursor: pointer;
}

.pagination button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.loading-state {
  text-align: center;
  font-size: 18px;
  color: #666;
}
</style>
