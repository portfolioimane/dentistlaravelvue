<template>
  <div>
    <div class="customers">
      <h1>Manage Patients</h1>

      <!-- Table Container without horizontal scrolling -->
      <div class="table-container">
        <table class="customers-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Gender</th>
              <th>Date of Birth</th>
              <th>Insurance</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="customer in customers" :key="customer.id">
              <td>{{ customer.id }}</td>
              <td>{{ customer.name }}</td>
              <td>{{ customer.email }}</td>
              <td>{{ customer.phone || 'N/A' }}</td>
              <td>{{ customer.gender || 'N/A' }}</td>
              <td>{{ customer.date_of_birth || 'N/A' }}</td>
              <td>
                <p>{{ customer.is_insured ? 'Yes' : 'No' }}</p>
                <p>{{ customer.insurance_provider || 'N/A' }}</p>
                <p>{{ customer.insurance_id || 'N/A' }}</p>
              </td>
              <td class="action-buttons">
                <div class="history-btn">
                  <button class="btn primary" @click="manageHistory(customer)">History</button>
                </div>
                <div class="edit-delete-btns">
                  <button class="btn secondary" @click="editCustomer(customer)">Edit</button>
                  <button class="btn danger" @click="openDeleteModal(customer.id)">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteModal" class="modal-overlay">
        <div class="modal-content">
          <h3>Are you sure you want to delete this customer?</h3>
          <div class="modal-actions">
            <button class="btn danger" @click="deleteCustomer">Yes, Delete</button>
            <button class="btn primary" @click="closeDeleteModal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'Customers',
  data() {
    return {
      showDeleteModal: false,
      customerToDelete: null,
    };
  },
  computed: {
    ...mapGetters('backendCustomers', ['allCustomers']),
    customers() {
      return this.allCustomers.map(customer => ({
        ...customer,
        is_insured: Boolean(customer.is_insured),
      }));
    },
  },
  methods: {
    manageHistory(customer) {
      this.$router.push({ name: 'ManageHistories', params: { patientId: customer.id } });
    },
    editCustomer(customer) {
      this.$router.push({ name: 'EditCustomer', params: { id: customer.id } });
    },
    openDeleteModal(customerId) {
      this.customerToDelete = customerId;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.customerToDelete = null;
    },
    async deleteCustomer() {
      try {
        await this.$store.dispatch('backendCustomers/deleteCustomer', this.customerToDelete);
        this.showDeleteModal = false;
        this.customerToDelete = null;
        this.showMessage('Customer deleted successfully', 'success');
      } catch (error) {
        this.showMessage('Error deleting customer', 'error');
        console.error('Error deleting customer:', error);
      }
    },
    showMessage(text, type) {
      this.message = { text, type };
      setTimeout(() => {
        this.message = null;
      }, 5000);
    },
  },
  mounted() {
    this.$store.dispatch('backendCustomers/fetchCustomers');
  },
};
</script>

<style scoped>
/* Container Style */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Customer Table Section */
.customers {
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
  color: #1E90FF;
  margin-bottom: 20px;
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
}

/* Table Container with horizontal scrolling */
.table-container {
  overflow-x: auto; /* Enable horizontal scrolling */
  -webkit-overflow-scrolling: touch; /* For smooth scrolling on mobile devices */
}

.customers-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: auto; /* Auto layout to adjust columns */
  margin-top: 20px;
}

.customers-table th,
.customers-table td {
  border: 1px solid #ddd;
  padding: 15px;
  text-align: left;
}

.customers-table th {
  background-color: #1E90FF;
  color: white;
  font-weight: bold;
}

.customers-table tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

.customers-table tbody tr:hover {
  background-color: #f9f9f9;
}

.customer-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

/* Button Styles */
.btn {
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
  font-weight: bold;
}

.btn.secondary {
  background-color: #1E90FF;
  color: white;
}

.btn.danger {
  background-color: #e74c3c;
  color: white;
}

.btn.primary {
  background-color: #1E90FF;
  color: white;
}

.btn:hover {
  opacity: 0.9;
}

/* Action Buttons Layout */
.action-buttons {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px; /* Space between the buttons */
}

.history-btn {
  margin-bottom: 10px; /* Space below the "History" button */
}

.edit-delete-btns {
  display: flex;
  gap: 10px; /* Space between Edit and Delete buttons */
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  width: 100%;
  text-align: center;
}

.modal-actions {
  margin-top: 20px;
}

.modal-actions button {
  margin: 0 10px;
}
</style>
