<template>
  <div class="review-manager">
    <h2 class="title">Review Management</h2>

    <div v-if="loading" class="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>

    <form @submit.prevent="submitReview" class="review-form">
      <!-- Customer Selection Dropdown -->
      <select 
        v-model="reviewData.user_id" 
        required 
        class="input-field"
      >
        <option value="" disabled>Select Customer</option>
        <option 
          v-for="customer in customers" 
          :key="customer.id" 
          :value="customer.id"
        >
          {{ customer.name }}
        </option>
      </select>
      
      <!-- Product Selection Dropdown -->
      <select 
        v-model="reviewData.service_id" 
        required 
        class="input-field"
      >
        <option value="" disabled>Select Service</option>
        <option 
          v-for="service in services" 
          :key="service.id" 
          :value="service.id"
        >
          {{ service.name }} <!-- Ensure 'title' exists or change to the correct property -->
        </option>
      </select>

      <input 
        v-model="reviewData.rating" 
        placeholder="Rating (1-5)" 
        type="number" 
        required 
        class="input-field"
      />
      <textarea 
        v-model="reviewData.review" 
        placeholder="Review" 
        class="input-field" 
      />
      <select v-model="reviewData.status" required class="input-field">
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
      </select>
      <label class="featured-label">
        <input type="checkbox" v-model="reviewData.is_featured" /> Featured
      </label>
      <button type="submit" class="submit-button">Add Review</button>
    </form>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
  data() {
    return {
      reviewData: {
        user_id: '',
        service_id: '',
        rating: '',
        review: '',
        status: 'pending',
        is_featured: false,
      },
    };
  },
  computed: {
    loading() {
      return this.$store.getters['backendReview/isLoading'];
    },
    error() {
      return this.$store.getters['backendReview/errorMessage'];
    },
    services() {
      return this.$store.getters['backendServices/allServices'];
    },
    customers() {
      return this.$store.getters['backendCustomers/allCustomers'];
    },
  },
  methods: {
    ...mapActions('backendReview', ['createReview']),
    ...mapActions('backendServices', ['fetchServices']),
    ...mapActions('backendCustomers', ['fetchCustomers']),
    async submitReview() {
      try {
        await this.createReview(this.reviewData);
        // Reset the review form
        this.reviewData = {
          user_id: '',
          service_id: '',
          rating: '',
          review: '',
          status: 'pending',
          is_featured: false,
        };
        // Redirect to the admin/reviews page
        this.$router.push('/admin/reviews');
      } catch (error) {
        console.error('Error creating review:', error);
      }
    },
  },

  mounted() {
    this.fetchServices();
    this.fetchCustomers();
  },
};
</script>


<style scoped>
.review-manager {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  background-color: #ffffff;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.title {
  font-size: 2rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #333;
}

.error {
  color: red;
  font-size: 1rem;
  margin-bottom: 20px;
}

.loading {
  color: #333;
  font-size: 1rem;
}

.review-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.input-field {
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

.input-field:focus {
  border-color: #007bff;
  outline: none;
}

.featured-label {
  font-size: 1rem;
  color: #333;
}

.submit-button {
  padding: 12px;
  background-color: #D4AF37;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.submit-button:hover {
  background-color:#D4AF37;
}

.review-list {
  margin-top: 30px;
  padding-left: 0;
  list-style: none;
}

.review-item {
  background-color: #f9f9f9;
  padding: 15px;
  margin-bottom: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.service-name {
  font-weight: 600;
  font-size: 1.1rem;
  color: #007bff;
}

.rating {
  display: inline-block;
  margin-top: 5px;
  font-size: 1rem;
  color: #888;
}

.review-text {
  margin-top: 10px;
  font-size: 1rem;
  color: #333;
}

.delete-button {
  margin-top: 15px;
  padding: 8px 15px;
  background-color: #dc3545;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.delete-button:hover {
  background-color: #c82333;
}
</style>
