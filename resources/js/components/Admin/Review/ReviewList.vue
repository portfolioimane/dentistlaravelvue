<template>
  <div class="review-list-container">
    <h2 class="title">All Reviews</h2>

    <!-- Show loading state while data is being fetched -->
    <div v-if="loading" class="loading">Loading reviews...</div>
    
    <!-- Show error message if something went wrong -->
    <div v-if="error" class="error">{{ error }}</div>

    <!-- Display reviews as a table -->
    <table v-if="reviews.length" class="review-table">
      <thead>
        <tr>
          <th>Course</th>
          <th>User</th>
          <th>Rating</th>
          <th>Review</th>
          <th>Status</th>
          <th>Featured</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="review in reviews" :key="review.id">
          <td>{{ review.course?.title }}</td>
          <td>{{ review.user?.name }}</td>
          <td>
            <span class="rating-stars">
              {{ renderStars(review.rating) }}
            </span>
          </td>
          <td>{{ review.review }}</td>
          <td>
            <select
              v-model="review.status"
              @change="updateReviewStatus(review.id, review.status)"
              class="status-dropdown"
            >
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </td>
          <td>
            <input
              type="checkbox"
              v-model="review.is_featured"
              @change="toggleFeatured(review.id, review.is_featured)"
              class="featured-checkbox"
            />
          </td>
          <td>
            <button @click="deleteReviewNow(review.id)" class="delete-btn">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    
    <!-- Display message if no reviews are available -->
    <div v-if="!reviews.length" class="no-reviews">No reviews available.</div>
  </div>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  computed: {
    ...mapGetters('backendReview', ['allReviews', 'isLoading', 'errorMessage']),
    reviews() {
      // Normalize is_featured value to boolean
      return this.allReviews.map(review => ({
        ...review,
        is_featured: Boolean(review.is_featured), // Convert to true/false
      }));
    },
    loading() {
      return this.isLoading; // Get loading state from Vuex store
    },
    error() {
      return this.errorMessage; // Get error message from Vuex store
    },
  },
  methods: {
    ...mapActions('backendReview', ['fetchReviews', 'deleteReview', 'updateReview']),
    renderStars(rating) {
      const stars = [];
      for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
          stars.push('★');
        } else {
          stars.push('☆');
        }
      }
      return stars.join('');
    },
async updateReviewStatus(id, status) {
  try {
    // Send only the status field to the backend
    await this.updateReview({ id, reviewData: { status } });
  } catch (error) {
    this.$toast.error('Failed to update review status. Please try again.');
  }
},

async toggleFeatured(id, isFeatured) {
  try {
    // Send only the is_featured field to the backend
    await this.updateReview({ id, reviewData: { is_featured: isFeatured ? 1 : 0 } });
  } catch (error) {
    this.$toast.error('Failed to update featured status. Please try again.');
  }
},

    async deleteReviewNow(id) {
      try {
        await this.deleteReview(id);
      } catch (error) {
        this.$toast.error('Failed to delete review. Please try again.');
      }
    },
  },
  mounted() {
    this.fetchReviews(); // Fetch reviews when the component is mounted
  },
};
</script>


<style scoped>
.review-list-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 20px;
  background-color: #ffffff;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.title {
  font-size: 2.5rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #333;
}

.loading {
  color: #333;
  font-size: 1.2rem;
}

.error {
  color: red;
  font-size: 1.2rem;
  margin-bottom: 20px;
}

.review-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.review-table th,
.review-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.review-table th {
  background-color: #f7f7f7;
  font-weight: 600;
}

.review-table tr:hover {
  background-color: #f9f9f9;
}

.rating-stars {
  font-size: 1.5rem;
  color: #ffcc00;
}

.delete-btn {
  padding: 8px 15px;
  background-color: #e74c3c;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

.delete-btn:hover {
  background-color: #c0392b;
}

.status-dropdown,
.featured-checkbox {
  padding: 5px;
  font-size: 1rem;
}

.no-reviews {
  font-size: 1.2rem;
  color: #777;
  text-align: center;
  margin-top: 20px;
}
</style>
