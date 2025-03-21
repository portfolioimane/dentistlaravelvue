<template>
  <div class="add-patient-history">
    <h1>Add New Patient History</h1>
    <form @submit.prevent="submitForm" class="patient-history-form">
      <div class="form-group">
        <label for="treatment_date">Treatment Date:</label>
        <input
          type="date"
          id="treatment_date"
          v-model="newPatientHistory.treatment_date"
          required
        />
      </div>

      <div class="form-group">
        <label for="treatment_details">Treatment Details:</label>
        <textarea
          id="treatment_details"
          v-model="newPatientHistory.treatment_details"
          required
          placeholder="Write the treatment details here"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="dentist_name">Dentist Name:</label>
        <input
          type="text"
          id="dentist_name"
          v-model="newPatientHistory.dentist_name"
          required
          placeholder="e.g., Dr. John Doe"
        />
      </div>

      <div class="form-group">
        <label for="treatment_type">Treatment Type:</label>
        <select
          id="treatment_type"
          v-model="newPatientHistory.treatment_type"
          required
        >
          <option value="" disabled>Select Treatment Type</option>
          <option
            v-for="service in services"
            :key="service.id"
            :value="service.name"
          >
            {{ service.name }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label for="treatment_cost">Treatment Cost:</label>
        <input
          type="number"
          id="treatment_cost"
          v-model.number="newPatientHistory.treatment_cost"
          required
          min="0"
          step="0.01"
          pattern="^\d+(\.\d{1,2})?$"
          title="Maximum of two decimal places"
        />
      </div>

      <div class="form-group">
        <label for="amount_paid">Amount Paid:</label>
        <input
          type="number"
          id="amount_paid"
          v-model.number="newPatientHistory.amount_paid"
          required
          min="0"
          step="0.01"
          pattern="^\d+(\.\d{1,2})?$"
          title="Maximum of two decimal places"
        />
      </div>

      <div class="form-group">
        <label for="prescriptions">Prescriptions (Optional):</label>
        <textarea
          id="prescriptions"
          v-model="newPatientHistory.prescriptions"
          placeholder="Enter any prescriptions here"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="follow_up_instructions">Follow-up Instructions (Optional):</label>
        <textarea
          id="follow_up_instructions"
          v-model="newPatientHistory.follow_up_instructions"
          placeholder="Enter follow-up instructions here"
        ></textarea>
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Add Patient History</button>
        <router-link :to="`/admin/patients/${resolvedPatientId}/histories`" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
  props: {
    patientId: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      newPatientHistory: {
        treatment_date: '',
        treatment_details: '',
        dentist_name: '',
        treatment_type: '',
        treatment_cost: 0.00,
        amount_paid: 0.00,
        prescriptions: '',
        follow_up_instructions: '',
      },
    };
  },
  computed: {
    resolvedPatientId() {
      return this.patientId || this.$route.params.patientId;
    },
    services() {
      return this.$store.getters['backendServices/allServices']; // Fetching all services from Vuex store
    },
  },
  methods: {
    ...mapActions('backendServices', ['fetchServices']),
    async submitForm() {
      try {
        await this.$store.dispatch('backendPatientHistories/addPatientHistory', {
          patientId: this.resolvedPatientId,
          historyData: this.newPatientHistory,
        });
        this.$router.push(`/admin/patients/${this.resolvedPatientId}/histories`);
      } catch (error) {
        console.error('Error adding patient history:', error);
      }
    },
  },
  mounted() {
    // Fetch the list of services when the component is mounted
    this.fetchServices();
  },
};
</script>

<style scoped>
.add-patient-history {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.add-patient-history h1 {
  color: #1E90FF;
  font-size: 1.6rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-align: center;
}

.patient-history-form .form-group {
  margin-bottom: 0.5rem;
}

.patient-history-form label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.25rem;
  color: #555;
}

.patient-history-form input[type="text"],
.patient-history-form input[type="number"],
.patient-history-form input[type="date"],
.patient-history-form select,
.patient-history-form textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.9rem;
}

.button-group {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  font-size: 0.9rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  text-transform: uppercase;
}

.btn.primary {
  background-color: #1E90FF;
  color: #fff;
}

.btn.secondary {
  background-color: #555;
  color: #fff;
}

.btn:hover {
  opacity: 0.9;
}
</style>
