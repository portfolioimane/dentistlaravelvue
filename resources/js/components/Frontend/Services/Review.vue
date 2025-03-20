<template>
  <div class="review-section mt-5">
    <h3 class="text-center mb-4">Customer Reviews</h3>

    <div v-if="reviews.length" class="review-list">
      <div v-for="review in reviews" :key="review.id" class="review-item mb-3 p-3 border rounded">
        <div class="d-flex align-items-center">
          <!-- Display User Avatar -->
          <img
            v-if="review.user.avatar"
            :src="`/${review.user.avatar}`"
            alt="User Avatar"
            class="user-avatar me-3"
          />
          <h5>{{ review.user.name }}</h5>
        </div>
        <p>{{ review.review }}</p>
        <div class="rating">
          <!-- Display filled stars -->
          <span v-for="n in review.rating" :key="n" class="star">&#9733;</span>
          <!-- Display empty stars -->
          <span v-for="n in (5 - review.rating)" :key="'empty-' + n" class="star-empty">&#9734;</span>
        </div>
      </div>
    </div>
    <div v-else>
      <p>No reviews yet. Be the first to review this service!</p>
    </div>

    <div v-if="showConfirmation" class="alert alert-success mt-3">
      Your review has been successfully submitted and is pending approval.
    </div>

    <div v-if="isAuthenticated" class="review-form mt-4">
      <h4>Write a Review</h4>
      <form @submit.prevent="submitReview">
        <div class="mb-3">
          <label class="form-label">Rating</label>
          <div class="stars-container">
            <!-- Stars that change based on rating -->
            <span
              v-for="n in 5"
              :key="n"
              @mouseover="hoverRating(n)"
              @mouseleave="clearHover"
              @click="setRating(n)"
              :class="{
                'star-filled': n <= (hoveredRating !== null ? hoveredRating : rating),
                'star-empty': n > (hoveredRating !== null ? hoveredRating : rating)
              }"
              class="star-rating"
              >&#9733;</span
            >
          </div>
          <!-- Error message for rating -->
          <p v-if="errors.rating" class="text-danger">{{ errors.rating }}</p>
        </div>

        <div class="mb-3">
          <label for="comment" class="form-label">Your Review</label>
          <textarea
            id="comment"
            v-model="comment"
            class="form-control"
            rows="3"
          ></textarea>
          <!-- Error message for comment -->
          <p v-if="errors.comment" class="text-danger">{{ errors.comment }}</p>
        </div>

        <button type="submit" class="btn btn-golden">Submit Review</button>
      </form>
    </div>

    <!-- Message for unauthenticated users -->
    <div v-else class="mt-4">
      <router-link :to="{ name: 'Login', query: { redirect: $route.fullPath } }"
        >Login here</router-link
      >.
    </div>
    
  </div>
</template>

<script>
export default {
  props: {
    serviceId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      comment: '',
      rating: 0, // Selected rating
      hoveredRating: null, // Used for hover state
      showConfirmation: false, // To show the confirmation message
      errors: {
        rating: '',
        comment: '',
      },
    };
  },
  computed: {
    reviews() {
      return this.$store.getters['reviews/reviews'];
    },
    isAuthenticated() {
      return this.$store.getters['auth/isAuthenticated'];
    },
  },
  created() {
    // Only fetch reviews if the user is authenticated
    if (this.isAuthenticated) {
      this.fetchReviews();
    }
  },
  methods: {
    fetchReviews() {
      this.$store.dispatch('reviews/fetchReviews', this.serviceId);
    },

    hoverRating(n) {
      this.hoveredRating = n; // Set hovered star
    },

    clearHover() {
      this.hoveredRating = null; // Clear hover state
    },

    setRating(rating) {
      this.rating = rating; // Set the actual rating
    },

    submitReview() {
      // Clear existing errors
      this.errors.rating = '';
      this.errors.comment = '';

      let isValid = true;

      // Check if rating is selected
      if (!this.rating) {
        this.errors.rating = 'Please select a rating.';
        isValid = false;
      }

      // Check if the comment field is filled
      if (!this.comment.trim()) {
        this.errors.comment = 'Please write a review.';
        isValid = false;
      }

      // Stop submission if validation fails
      if (!isValid) return;

      // Proceed with review submission
      const reviewData = {
        serviceId: this.serviceId,
        review: this.comment,
        rating: this.rating,
      };

      this.$store.dispatch('reviews/submitReview', reviewData).then(() => {
        // Show confirmation message
        this.showConfirmation = true;

        // Hide confirmation after a few seconds
        setTimeout(() => {
          this.showConfirmation = false;
        }, 3000);

        // Clear the form
        this.comment = '';
        this.rating = 0;
      });
    },
  },
};
</script>


<style scoped>
.review-section {
  background-color: #f9f9f9;
  padding: 2rem;
  border-radius: 8px;
}

.review-list .review-item {
  background-color: #fff;
}

.review-list .star {
  color: #ffbc00; /* Yellow color for filled stars */
}

.review-list .star-empty {
  color: #ccc; /* Gray color for empty stars */
}

.star-rating {
  cursor: pointer;
  font-size: 1.5rem;
  margin-right: 5px;
  transition: color 0.2s ease;
}

.star-filled {
  color: #ffbc00; /* Filled yellow stars */
}

.star-empty {
  color: #ccc; /* Empty gray stars */
}

.star-rating:hover {
  color: #ff8000; /* Slightly darker yellow for hover effect */
}

.stars-container {
  display: flex;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.alert {
  font-size: 1rem;
  font-weight: 500;
  border-radius: 8px;
}

.text-danger {
  color: #dc3545; /* Red for error messages */
  font-size: 0.9rem;
  margin-top: 0.5rem;
}
</style>
