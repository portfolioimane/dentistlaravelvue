<template>
  <div class="featured-reviews-section">
    <h2 class="section-title">Testimonials</h2>

    <!-- Display the featured reviews -->
    <div class="reviews-container">
      <div
        class="review-card"
        v-for="(review, index) in featuredReviews"
        :key="index"
      >
        <div class="review-header">
          <div class="review-author-info">
            <!-- Display user avatar -->
            <img
              v-if="review.user.avatar"
              :src="review.user.avatar"
              alt="User Avatar"
              class="review-author-avatar"
            />
            <span class="review-author">{{ review.user.name }}</span>
          </div>
          <!-- Display rating on a separate line -->
          <div class="review-rating">
            <span class="stars">
              <i
                v-for="star in review.rating"
                :key="star"
                class="fas fa-star"
                :class="{ 'filled': star }"
              ></i>
            </span>
          </div>
        </div>
        <p class="review-text">{{ review.review }}</p>
        <div class="review-footer">
          <span class="review-product">{{ review.service.name }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  computed: {
    // Accessing the featured reviews from Vuex store
    ...mapGetters('reviews', ['featuredReviews']),
  },
  mounted() {
    // Fetch the featured reviews when the component is mounted
    this.fetchFeaturedReviews();
  },
  methods: {
    async fetchFeaturedReviews() {
      try {
        // Dispatching the action to fetch featured reviews
        await this.$store.dispatch('reviews/fetchFeaturedReviews');
      } catch (error) {
        console.error('Error fetching featured reviews:', error);
      }
    },
  },
};
</script>

<style scoped>
.featured-reviews-section {
  padding: 3rem 2rem;
  background-color: #f8f8f8;
}

.section-title {
  font-size: 2rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 2.5rem;
  color: #333;
}

.reviews-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
  padding: 0 10px;
}

.review-card {
  background-color: white;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
  text-align: center;
  transition: transform 0.3s ease;
}

.review-card:hover {
  transform: translateY(-5px);
}

.review-header {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;
  align-items: center; /* Centering content horizontally */
}

.review-author-info {
  display: flex;
  align-items: center;
  justify-content: center; /* Centering the avatar and name */
  margin-bottom: 0.5rem;
}

.review-author-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 10px;
}

.review-author {
  font-size: 1.1rem;
  font-weight: bold;
  color: #555;
}

.review-rating {
  display: flex;
  justify-content: center; /* Centering the rating stars */
}

.review-rating .stars i {
  color: #ff9900;
}

.review-rating .stars .filled {
  color: #f39c12;
}

.review-text {
  font-size: 1rem;
  color: #555;
  margin-bottom: 1.5rem;
  line-height: 1.6;
  text-align: center; /* Centering the text */
}

.review-footer {
  font-size: 0.9rem;
  color: #777;
  font-style: italic;
  text-align: center; /* Centering the footer text */
}

.review-footer .review-product {
  color: #333;
  font-weight: bold;
}

@media (max-width: 768px) {
  .reviews-container {
    grid-template-columns: 1fr 1fr;
  }
}

@media (max-width: 480px) {
  .reviews-container {
    grid-template-columns: 1fr;
  }
}
</style>
