<template>
  <div v-if="header" class="hero-section">
    <!-- Background Image with Overlay -->
    <div
      class="hero-bg"
      :style="{ backgroundImage: `url(${header.image})` }"
    >
      <!-- Overlay on Background Image -->
      <div class="hero-overlay"></div>
      
      <!-- Content -->
      <div class="hero-content">
        <h1 class="hero-title">{{ header.title }}</h1>
        <p class="hero-subtitle">{{ header.subtitle }}</p>
        <button class="hero-btn" @click="handleButtonClick">
          {{ header.buttonText }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  data() {
    return {
      // Add any other local data if needed
    };
  },
  computed: {
    ...mapGetters('backendHomePageHeader', ['getHeader', 'getError']),
    header() {
      return this.getHeader;
    },
  },
  mounted() {
    this.fetchHeaderFront();
  },
  methods: {
    async fetchHeaderFront() {
      try {
        await this.$store.dispatch('backendHomePageHeader/fetchHeaderFront');
      } catch (error) {
        console.error('Error fetching homepage header:', error);
      }
    },
    handleButtonClick() {
      // Handle button click (e.g., navigate to the shop or another page)
      this.$router.push('/courses');
    },
  },
};
</script>

<style scoped>
.hero-section {
  width: 100%;
  height: 60vh; /* Reduced height */
  position: relative;
  overflow: hidden;
}

.hero-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  z-index: 0;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4); /* Slightly lighter overlay for text contrast */
  z-index: 1;
}

.hero-content {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  color: white;
  z-index: 2;
  max-width: 80%; /* Prevents text from stretching too wide */
}

.hero-title {
  font-size: 3rem; /* Adjusted font size */
  font-weight: 700;
  line-height: 1.3;
  margin: 0;
  text-transform: capitalize; /* Capitalize first letter of each word */
}


.hero-subtitle {
  font-size: 1.4rem; /* Adjusted font size */
  font-weight: 400;
  margin-top: 1rem;
  line-height: 1.5;
}

.hero-btn {
  margin-top: 2rem;
  padding: 1rem 2.5rem;
  background-color: #1E90FF; /* Gold color for button */
  color: white;
  border: none;
  border-radius: 30px;
  font-size: 1.2rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.hero-btn:hover {
  background-color: #187bcd; /* Darker gold on hover */
  transform: translateY(-3px); /* Button lift effect */
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

.hero-btn:focus {
  outline: none; /* Removes outline on focus */
}

@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
  .hero-subtitle {
    font-size: 1.4rem;
  }
  .hero-btn {
    font-size: 1rem;
    padding: 1rem 2rem;
  }
}
</style>

