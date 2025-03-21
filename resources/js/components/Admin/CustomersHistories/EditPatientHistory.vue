<template>
  <div class="edit-patient-history">
    <h1>Edit Patient History</h1>
    <form @submit.prevent="submitForm" class="patient-history-form">
      <div class="form-group">
        <label for="treatment_date">Treatment Date:</label>
        <input
          type="date"
          id="treatment_date"
          v-model="patientHistory.treatment_date"
          required
        />
      </div>

      <div class="form-group">
        <label for="treatment_details">Treatment Details:</label>
        <textarea
          id="treatment_details"
          v-model="patientHistory.treatment_details"
          required
          placeholder="Write the treatment details here"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="dentist_name">Dentist Name:</label>
        <input
          type="text"
          id="dentist_name"
          v-model="patientHistory.dentist_name"
          required
          placeholder="e.g., Dr. John Doe"
        />
      </div>
     <div class="form-group">
        <label for="treatment_type">Treatment Type:</label>
        <select
          id="treatment_type"
          v-model="patientHistory.treatment_type"
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
          v-model="patientHistory.treatment_cost"
          required
          min="0"
          step="0.01"
        />
      </div>

      <div class="form-group">
        <label for="amount_paid">Amount Paid:</label>
        <input
          type="number"
          id="amount_paid"
          v-model="patientHistory.amount_paid"
          required
          min="0"
          step="0.01"
        />
      </div>

      <div class="form-group">
        <label for="prescriptions">Prescriptions (Optional):</label>
        <textarea
          id="prescriptions"
          v-model="patientHistory.prescriptions"
          placeholder="Enter any prescriptions here"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="follow_up_instructions">Follow-up Instructions (Optional):</label>
        <textarea
          id="follow_up_instructions"
          v-model="patientHistory.follow_up_instructions"
          placeholder="Enter follow-up instructions here"
        ></textarea>
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Update Patient History</button>
        <router-link :to="`/admin/patients/${resolvedPatientId}/histories`" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {

  data() {
    return {
      patientHistory: {
        treatment_date: '',
        treatment_details: '',
        dentist_name: '',
        treatment_type: 'consultation',
        treatment_cost: 0.00,
        amount_paid: 0.00,
        prescriptions: '',
        follow_up_instructions: '',
      },
    };
  },
  computed: {
      ...mapGetters('backendPatientHistories', ['currentPatientHistory']),

    resolvedHistoryId() {
      return this.$route.params.historyId;
    },
       services() {
      return this.$store.getters['backendServices/allServices']; // Fetching all services from Vuex store
    },
       resolvedPatientId() {
      return this.$route.params.patientId;
    },
  },
  created() {
    this.fetchPatientHistory();
  },
  methods: {
      ...mapActions('backendPatientHistories', ['fetchPatientHistoryById']),
          ...mapActions('backendServices', ['fetchServices']),


async fetchPatientHistory() {
    await this.fetchPatientHistoryById(this.resolvedHistoryId);
    
    // Ensure treatment_type is set to an existing value or default to the first service
    const existingService = this.services.find(service => service.name === this.currentPatientHistory.treatment_type);
    this.patientHistory = { 
      ...this.currentPatientHistory, 
      treatment_type: existingService ? existingService.name : this.services[0]?.name || 'consultation'
    };
  },

    async submitForm() {
    const formData = new FormData();
formData.append('treatment_date', this.patientHistory.treatment_date);
formData.append('treatment_details', this.patientHistory.treatment_details);
formData.append('dentist_name', this.patientHistory.dentist_name);
formData.append('treatment_type', this.patientHistory.treatment_type);
formData.append('treatment_cost', this.patientHistory.treatment_cost);
formData.append('amount_paid', this.patientHistory.amount_paid);
formData.append('prescriptions', this.patientHistory.prescriptions);
formData.append('follow_up_instructions', this.patientHistory.follow_up_instructions);

      try {
        await this.$store.dispatch('backendPatientHistories/updatePatientHistory', {
          historyId:this.resolvedHistoryId,
          patientId: this.resolvedPatientId,
          historyData: formData,
        });
        this.$router.push(`/admin/patients/${this.resolvedPatientId}/histories`);
      } catch (error) {
        console.error('Error updating patient history:', error);
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
.edit-patient-history {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.edit-patient-history h1 {
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
