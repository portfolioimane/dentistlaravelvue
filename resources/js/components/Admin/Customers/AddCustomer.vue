<template>
  <div class="add-customer">
    <h1>Add New Patient</h1>
    <form @submit.prevent="submitForm" class="customer-form">
      <div class="form-group">
        <label for="name">Full Name:</label>
        <input
          type="text"
          id="name"
          v-model="newCustomer.name"
          required
          placeholder="e.g., John Doe"
        />
      </div>

      <div class="form-group">
        <label for="email">Email Address:</label>
        <input
          type="email"
          id="email"
          v-model="newCustomer.email"
          required
          placeholder="e.g., john.doe@example.com"
        />
      </div>

      <div class="form-group">
        <label for="phone">Phone Number:</label>
        <input
          type="text"
          id="phone"
          v-model="newCustomer.phone"
          placeholder="e.g., +212 123 456 789"
        />
      </div>

      <div class="form-group">
        <label for="date_of_birth">Date of Birth:</label>
        <input
          type="date"
          id="date_of_birth"
          v-model="newCustomer.date_of_birth"
        />
      </div>

      <div class="form-group">
        <label for="gender">Gender:</label>
        <select
          id="gender"
          v-model="newCustomer.gender"
        >
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>

      <div class="form-group">
        <label for="emergency_contact">Emergency Contact:</label>
        <input
          type="text"
          id="emergency_contact"
          v-model="newCustomer.emergency_contact"
          placeholder="e.g., John Doe, +212 123 456 789"
        />
      </div>

      <div class="form-group">
        <label for="medical_history">Medical History:</label>
        <textarea
          id="medical_history"
          v-model="newCustomer.medical_history"
          placeholder="Describe the customer's medical history"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="is_insured">Is Insured?</label>
        <select id="is_insured" v-model="newCustomer.is_insured">
          <option :value="true">Yes</option>
          <option :value="false">No</option>
        </select>
      </div>

      <div class="form-group" v-if="newCustomer.is_insured">
        <label for="insurance_provider">Insurance Provider:</label>
        <input
          type="text"
          id="insurance_provider"
          v-model="newCustomer.insurance_provider"
          placeholder="e.g., Allianz"
        />
      </div>

      <div class="form-group" v-if="newCustomer.is_insured">
        <label for="insurance_id">Insurance ID:</label>
        <input
          type="text"
          id="insurance_id"
          v-model="newCustomer.insurance_id"
          placeholder="e.g., INS12345678"
        />
      </div>



      <div class="button-group">
        <button type="submit" class="btn primary">Add Patient</button>
        <router-link to="/admin/customers" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      newCustomer: {
        name: '',
        email: '',
        phone: '',
        date_of_birth: '',
        gender: 'male',
        emergency_contact: '',
        medical_history: '',
        is_insured: false, // Initial value set to false
        insurance_provider: '',
        insurance_id: '',
      },
    };
  },
methods: {
  async submitForm() {
    console.log("Form submission started");
    const formData = new FormData();

    // Convert `is_insured` to an integer (0 or 1)
    this.newCustomer.is_insured = this.newCustomer.is_insured === true ? 1 : 0;
    console.log("is_insured:", this.newCustomer.is_insured); // Check the value of `is_insured`

    // Append all fields, including password, to formData
    Object.entries(this.newCustomer).forEach(([key, value]) => {
      formData.append(key, value);
    });

    try {
      console.log("Dispatching addCustomer action with formData:", formData);
      await this.$store.dispatch('backendCustomers/addCustomer', formData);  // Dispatching action to store
      console.log("Customer added successfully");
      this.$router.push('/admin/patients');  // Redirect to customer list
    } catch (error) {
      console.error("Error adding customer:", error);  // Handling error
    }
  },
},

};
</script>


<style scoped>
.add-customer {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.add-customer h1 {
  color: #1E90FF;
  font-size: 1.6rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-align: center;
}

.customer-form .form-group {
  margin-bottom: 0.5rem;
}

.customer-form label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.25rem;
  color: #555;
}

.customer-form input[type="text"],
.customer-form input[type="email"],
.customer-form input[type="date"],
.customer-form input[type="checkbox"],
.customer-form textarea,
.customer-form select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.9rem;
}

.customer-form input:focus,
.customer-form select:focus,
.customer-form textarea:focus {
  border-color: #d4af37;
  outline: none;
}

.customer-form textarea {
  resize: vertical;
  min-height: 80px;
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
