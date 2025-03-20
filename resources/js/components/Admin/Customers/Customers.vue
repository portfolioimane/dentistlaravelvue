<template>
  <div class="customers-container">
    <h1>Customer List</h1>
    <div v-if="loading" class="loading">Loading...</div>
    <div v-else>
      <table v-if="customers.length > 0" class="customer-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Avatar</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="customer in customers" :key="customer.id">
            <td class="customer-name">{{ customer.name }}</td>
            <td class="customer-email">{{ customer.email }}</td>
            <td class="customer-phone">{{ customer.phone || 'N/A' }}</td>
            <td class="customer-avatar">
              <img v-if="customer.avatar" :src="`/storage/${customer.avatar}`" alt="avatar" class="avatar" />
              <span v-else class="no-avatar">No Avatar</span>
            </td>
            <td class="customer-actions">
              <button class="delete-btn" @click="deleteCustomer(customer.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      <p v-else class="no-customers">No customers found</p>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'Customers',
  computed: {
    ...mapGetters('backendUsers', ['allCustomers', 'loading', 'error']),
    customers() {
      return this.allCustomers;
    }
  },
  methods: {
    ...mapActions('backendUsers', ['fetchCustomers']),
    deleteCustomer(id) {
      if (confirm("Are you sure you want to delete this customer?")) {
        // Call your delete action here, e.g., this.$store.dispatch('deleteUser', id);
        console.log(`Customer with ID ${id} deleted`);
        // Re-fetch the updated customer list after deletion
        this.fetchCustomers();
      }
    },
  },
  created() {
    this.fetchCustomers();
  }
};
</script>

<style scoped>
.customers-container {
  padding: 20px;
  max-width: 1200px;
  margin: auto;
}

.loading {
  font-size: 18px;
  color: #888;
  text-align: center;
}

.customer-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  font-family: 'Arial', sans-serif;
}

.customer-table th,
.customer-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.customer-table th {
  background-color: #f4f4f4;
  color: #333;
  font-weight: 600;
}

.customer-table tr:hover {
  background-color: #f9f9f9;
}

.customer-name,
.customer-email,
.customer-phone {
  font-size: 14px;
  color: #333;
}

.customer-avatar {
  text-align: center;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.no-avatar {
  font-size: 14px;
  color: #aaa;
  font-style: italic;
}

.no-customers {
  text-align: center;
  color: #888;
  font-size: 16px;
}

.error {
  color: red;
  margin-top: 20px;
  text-align: center;
}

.customer-actions {
  text-align: center;
}

.delete-btn {
  background-color: #e74c3c;
  color: white;
  border: none;
  padding: 6px 12px;
  font-size: 14px;
  cursor: pointer;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

.delete-btn:hover {
  background-color: #c0392b;
}
</style>
