<template>
  <div class="dashboard">
    <h1>Admin Dashboard</h1>

    <!-- Dashboard Stats Section -->
    <div class="stats">
      <div class="stat-card">
        <h3>Total Services</h3>
        <p>{{ servicesCount }}</p>
      </div>
      <div class="stat-card">
        <h3>Total Bookings</h3>
        <p>{{ totalBookings }}</p>
      </div>
      <div class="stat-card">
        <h3>Total Patients</h3>
        <p>{{ totalPatients }}</p>
      </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="recent-orders">
      <h2>Recent Bookings</h2>
      <table>
        <thead>
          <tr>
            <th>Patient Name</th>
            <th>Date</th>
            <th>Total Amount</th>
            <th>Service</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="booking in recentBookings" :key="booking.id">
            <td>{{ booking.name }}</td>
            <td>{{ formatDate(booking.created_at) }}</td>
            <td>{{ booking.total }} MAD</td>
            <td>{{ booking.service.name }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "Dashboard",
  computed: {
    ...mapGetters({
      services: "backendServices/allServices", // Changed from backendCourses to backendServices
      bookings: "backendBookings/bookings",
      customers: "backendCustomers/allCustomers",
    }),

    servicesCount() {
      return this.services.length; // Updated to reflect services count
    },

    totalBookings() {
      return this.bookings.length;
    },

    totalPatients() {
      return this.customers.length;
    },

    recentBookings() {
      return [...this.bookings]
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
        .slice(0, 3); // Limit to the most recent 3
    },
  },
  methods: {
    async fetchDashboardData() {
      try {
        await this.$store.dispatch("backendServices/fetchServices"); // Fetch services instead of courses
        await this.$store.dispatch("backendBookings/fetchBookings");
        await this.$store.dispatch("backendCustomers/fetchCustomers");
      } catch (error) {
        console.error("Error fetching dashboard data:", error);
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString("en-US"); // Format date
    },
  },
  mounted() {
    this.fetchDashboardData(); // Fetch data when the component is mounted
  },
};
</script>

<style scoped>
.dashboard {
  padding: 20px;
}

h3 {
  color: #ffffff !important;
}

.stats {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}

.stat-card {
  flex: 1;
  background-color: var(--primary-color);
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  border: 1px solid #ddd;
  transition: transform 0.3s ease, background-color 0.3s ease;
}

.stat-card:hover {
  background-color: var(--primary-color);
  transform: scale(1.05);
}

.stat-card h3 {
  margin: 0;
  color: var(--secondary-color);
}

.stat-card p {
  font-size: 24px;
  margin-top: 10px;
  font-weight: bold;
}

.recent-orders {
  margin-top: 20px;
}

.recent-orders table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.recent-orders th,
.recent-orders td {
  padding: 12px;
  border: 1px solid #ddd;
  text-align: left;
}

.recent-orders th {
  background-color: #f4f4f4;
}
</style>
