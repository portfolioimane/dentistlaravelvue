<template>
  <div class="patient-histories">
    <h1>Manage Patient Histories for : {{ patientName }}</h1>
    
    <button class="btn primary" @click="addPatientHistory">Add New History</button>
    
    <!-- Check if there are no histories -->
    <div v-if="allPatientHistories.length === 0">
      <p>No histories for now.</p>
    </div>

    <!-- Display histories if available -->
    <table class="patient-histories-table" v-else>
      <thead>
        <tr>
          <th>History ID</th>
          <th>Treatment Date</th>
          <th>Treatment Type</th>
          <th>Cost</th>
          <th>Amount Paid</th>
          <th>Remaining Balance</th>
          <th>Prescriptions</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="history in allPatientHistories" :key="history.id">
          <td>{{ history.id }}</td>
          <td>{{ history.treatment_date }}</td>
          <td>{{ history.treatment_type }}</td>
          <td>{{ history.treatment_cost }}</td>
          <td>{{ history.amount_paid }}</td>
          <td>{{ history.remaining_balance }}</td>
          <td>{{ history.prescriptions }}</td>

          <td>
            <button class="btn secondary" @click="editPatientHistory(history)">Edit</button>
            <button class="btn danger" @click="openDeleteModal(history.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Are you sure you want to delete this history record?</h3>
        <div class="modal-actions">
          <button class="btn danger" @click="deleteHistory">Yes, Delete</button>
          <button class="btn primary" @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { mapGetters } from 'vuex';

export default {
  name: 'ManagePatientHistories',
  data() {
    return {
      showDeleteModal: false,
      historyToDelete: null,
      message: null,
    };
  },
  computed: {
    ...mapGetters('backendCustomers', ['currentCustomer']),
    ...mapGetters('backendPatientHistories', ['allPatientHistories']),
    patientName() {
      return this.currentCustomer ? this.currentCustomer.name : 'Loading...';
    }
  },
  methods: {
    async fetchPatientHistories() {
      const patientId = this.$route.params.patientId;
      await this.$store.dispatch('backendCustomers/fetchCustomerById', patientId);
      await this.$store.dispatch('backendPatientHistories/fetchHistoriesByPatient', patientId);
    },
    addPatientHistory() {
      this.$router.push({ name: 'AddPatientHistory', params: { patientId: this.$route.params.patientId } });
    },
    editPatientHistory(history) {
      this.$router.push(`/admin/patients/${this.$route.params.patientId}/histories/edit/${history.id}`);
    },
    openDeleteModal(historyId) {
      this.historyToDelete = historyId;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.historyToDelete = null;
    },
    async deleteHistory() {
      try {
        await this.$store.dispatch('backendPatientHistories/deletePatientHistory', this.historyToDelete);
        this.closeDeleteModal();
        this.showMessage('Patient history deleted successfully', 'success');
        this.fetchPatientHistories();
      } catch (error) {
        this.showMessage('Error deleting patient history', 'error');
        console.error('Error deleting patient history:', error);
      }
    },
    showMessage(text, type) {
      this.message = { text, type };
      setTimeout(() => {
        this.message = null;
      }, 5000);
    }
  },
  mounted() {
    this.fetchPatientHistories();
  },
};
</script>

<style scoped>
.patient-histories {
  padding: 30px;
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

.patient-histories-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.patient-histories-table th,
.patient-histories-table td {
  border: 1px solid #ddd;
  padding: 15px;
  text-align: left;
}

.patient-histories-table th {
  background-color: #1E90FF;
  color: white;
  font-weight: bold;
}

.patient-histories-table tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

.patient-histories-table tbody tr:hover {
  background-color: #f9f9f9;
}

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
