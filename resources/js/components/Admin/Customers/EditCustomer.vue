<template>
  <div class="edit-customer">
    <h1>Edit Patient</h1>
    <form @submit.prevent="submitForm" class="customer-form">
      <div class="form-group">
        <label for="name">Name:</label>
        <input
          type="text"
          id="name"
          v-model="customer.name"
          required
          placeholder="Enter full name"
        />
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input
          type="email"
          id="email"
          v-model="customer.email"
          required
          placeholder="Enter email"
        />
      </div>

      <div class="form-group">
        <label for="phone">Phone:</label>
        <input
          type="text"
          id="phone"
          v-model="customer.phone"
          placeholder="Enter phone number"
        />
      </div>

      <div class="form-group">
        <label for="date_of_birth">Date of Birth:</label>
        <input
          type="date"
          id="date_of_birth"
          v-model="customer.date_of_birth"
        />
      </div>

      <div class="form-group">
        <label for="gender">Gender:</label>
        <select id="gender" v-model="customer.gender">
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
          v-model="customer.emergency_contact"
          placeholder="Emergency contact details"
        />
      </div>

      <div class="form-group">
        <label for="medical_history">Medical History:</label>
        <textarea
          id="medical_history"
          v-model="customer.medical_history"
          placeholder="Medical history details"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="is_insured">Is Insured?</label>
        <select id="is_insured" v-model="customer.is_insured">
          <option :value="1">Yes</option>
          <option :value="0">No</option>
        </select>
      </div>

      <!-- Insurance fields, visible only if 'is_insured' is 1 (Yes) -->
      <div class="form-group" v-if="customer.is_insured == 1">
        <label for="insurance_provider">Insurance Provider:</label>
        <input
          type="text"
          id="insurance_provider"
          v-model="customer.insurance_provider"
          placeholder="Insurance provider name"
        />
      </div>

      <div class="form-group" v-if="customer.is_insured == 1">
        <label for="insurance_id">Insurance ID:</label>
        <input
          type="text"
          id="insurance_id"
          v-model="customer.insurance_id"
          placeholder="Insurance ID"
        />
      </div>

      <!-- Avatar Upload -->
      <div class="form-group">
        <label for="avatar">Upload Avatar:</label>
        <input
          type="file"
          id="avatar"
          @change="handleAvatarUpload"
          accept="image/*"
        />
        <small class="help-text">
          <span>For optimal performance, upload images with a maximum size of 2MB.</span>
        </small>
        <p v-if="avatarError" class="error-message">The image file size exceeds the 2MB limit. Please upload a smaller image.</p>
      </div>

      <!-- Avatar Preview Section -->
      <div v-if="avatarPreview" class="avatar-preview">
        <img :src="avatarPreview" alt="Avatar Preview" class="img-fluid avatar-img" />
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Save Changes</button>
        <router-link to="/admin/customers" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  data() {
    return {
      customer: {
        id: null,
        name: '',
        email: '',
        phone: '',
        date_of_birth: '',
        gender: 'male',
        emergency_contact: '',
        medical_history: '',
        is_insured: 0,  // Default to 'No' (0)
        insurance_provider: null,  // Make insurance fields nullable
        insurance_id: null,  // Make insurance fields nullable
        avatar: null,
      },
      avatarError: false,  // Flag to track avatar validation error
      avatarPreview: null, // URL for avatar preview
    };
  },
  computed: {
    ...mapGetters('backendCustomers', ['currentCustomer']),
  },
  methods: {
    ...mapActions('backendCustomers', ['fetchCustomerById', 'updateCustomer']),

    async fetchCustomer() {
      const customerId = this.$route.params.id;
      await this.fetchCustomerById(customerId);
      this.customer = { ...this.currentCustomer };
      this.avatarPreview = this.customer.avatar ? `${this.customer.avatar}` : null;
    },

    handleAvatarUpload(event) {
      const file = event.target.files[0];
      if (file) {
        if (file.size > 2 * 1024 * 1024) {
          this.avatarError = true;
          this.customer.avatar = null;
          this.avatarPreview = null;
        } else {
          this.avatarError = false;
          this.customer.avatar = file;

          // Create a preview URL for the avatar
          this.avatarPreview = URL.createObjectURL(file);
        }
      }
    },

    async submitForm() {
      if (this.avatarError) {
        alert("Please upload a valid avatar.");
        return;
      }

      // Convert `is_insured` to a boolean (0 or 1)
      this.customer.is_insured = this.customer.is_insured === 1 ? 1 : 0;
      console.log("is_insured:", this.customer.is_insured); // Check the value of `is_insured`

      // If the insurance is 'No', set the insurance fields to null before submitting
      if (this.customer.is_insured === 0) {
        this.customer.insurance_provider = null;
        this.customer.insurance_id = null;
      }

      const formData = new FormData();
      
      // Only append fields if they are filled
      if (this.customer.name) formData.append('name', this.customer.name);
      if (this.customer.email) formData.append('email', this.customer.email);
      if (this.customer.phone) formData.append('phone', this.customer.phone);
      if (this.customer.date_of_birth) formData.append('date_of_birth', this.customer.date_of_birth);
      if (this.customer.gender) formData.append('gender', this.customer.gender);
      if (this.customer.emergency_contact) formData.append('emergency_contact', this.customer.emergency_contact);
      if (this.customer.medical_history) formData.append('medical_history', this.customer.medical_history);
      formData.append('is_insured', this.customer.is_insured);
      if (this.customer.insurance_provider) formData.append('insurance_provider', this.customer.insurance_provider);
      if (this.customer.insurance_id) formData.append('insurance_id', this.customer.insurance_id);

      if (this.customer.avatar) {
        formData.append('avatar', this.customer.avatar);
      }

      await this.updateCustomer({ id: this.$route.params.id, formData });
      this.$router.push('/admin/patients');
    }
  },
  mounted() {
    this.fetchCustomer();
  },
};
</script>


<style scoped>
.edit-customer {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.edit-customer h1 {
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
.customer-form select,
.customer-form textarea,
.customer-form input[type="file"] {
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

.help-text {
  font-size: 0.85rem;
  color: #777;
  margin-top: 0.5rem;
}

.error-message {
  color: red;
  font-size: 0.85rem;
  margin-top: 0.5rem;
}

.avatar-preview {
  width: 100px !important;
  height: 100px !important;
  margin-top: 1rem;
}

.avatar-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

@media (max-height: 768px) {
  .edit-customer {
    margin: 1rem auto;
  }

  .customer-form input,
  .customer-form textarea {
    padding: 0.4rem;
    font-size: 0.85rem;
  }

  .btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
  }
}
</style>
