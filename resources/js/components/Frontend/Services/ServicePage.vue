<template>
  <div class="service-detail">
    <!-- Check if service is available before rendering -->
    <div v-if="service" class="service-content">
      <div class="service-header">
        <!-- Service Image -->
        <div v-if="service.image" class="service-image">
          <img :src="service.image" :alt="service.name" class="img-fluid" />
        </div>

        <div class="service-info">
          <h1 class="service-title">{{ service.name }}</h1>
          <p class="service-description">{{ service.description }}</p>

          <div class="service-meta">
            <span class="service-duration">
              <i class="fa fa-clock"></i> {{ formatDuration(service.duration) }}
            </span>
            <span class="service-cost">
              <i class="fa fa-tag"></i> {{ service.cost ? `${service.cost} MAD` : 'Free' }}
            </span>
          </div>

          <!-- Book Now Button -->
          <div class="book-button">
            <button @click="bookNow" class="btn-book">
              <i class="fa fa-check"></i> Book Now
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Optionally show loading spinner if service is not yet loaded -->
    <div v-else class="loading">
      <i class="fa fa-spinner fa-spin"></i> Loading service...
    </div>

        <Review v-if="serviceLoaded" :serviceId="Number(serviceId)" />

  </div>
</template>

<script>
import Review from './Review.vue';

import { mapGetters, mapActions } from 'vuex';

export default {
    components: {
    Review
  },
  data() {
    return {
      serviceLoaded: false, // Track if the service has finished loading
    };
  },
  computed: {
    ...mapGetters({
      service: 'services/selectedService',
    }),

    serviceId() {
      return this.$route.params.serviceId;  // Capture the service ID from the URL
    },
  },
  created() {
    this.fetchServiceById(this.serviceId);
  },
  methods: {
    ...mapActions({
      fetchServiceById: 'services/fetchServiceById',
    }),

    formatDuration(duration) {
      if (!duration) return 'Duration not specified';
      
      const hours = Math.floor(duration / 60);
      const minutes = duration % 60;
      let formattedDuration = '';

      if (hours > 0) {
        formattedDuration += `${hours} hour${hours > 1 ? 's' : ''}`;
      }
      if (minutes > 0) {
        if (formattedDuration) formattedDuration += ' ';
        formattedDuration += `${minutes} minute${minutes > 1 ? 's' : ''}`;
      }

      return formattedDuration || 'Duration not specified';
    },

    // Redirect to booking page
    bookNow() {
      if (this.service && this.service.id) {
        this.$router.push({ name: 'checkout', params: { serviceId: this.service.id } });
      }
    },
  },
  watch: {
    service(newService) {
      if (newService) {
        this.serviceLoaded = true;
      }
    },
  },
};
</script>

<style scoped>
.service-detail {
  padding: 40px;
  max-width: 100%;
  margin: 50px auto;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.service-content {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  gap: 30px;
  align-items: center;
}

.service-header {
  display: flex;
  flex-direction: row;
  align-items: center;
}

.service-image {
  flex: 1;
  margin-right: 30px;
}

.service-image img {
  width: 100%;
  max-width: 400px;
  height: auto;
  border-radius: 8px;
}

.service-info {
  flex: 2;
}

.service-title {
  font-size: 2rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 10px;
}

.service-description {
  font-size: 1.2rem;
  color: #555;
  margin-bottom: 20px;
}

.service-meta {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

.service-meta span {
  font-size: 1.1rem;
  color: #777;
}

.service-meta i {
  margin-right: 8px;
  color: #1E90FF;
}

.book-button {
  margin-top: 30px;
}

.btn-book {
  background-color: #1E90FF;
  color: white;
  padding: 15px 25px;
  border: none;
  border-radius: 50px;
  font-size: 1.1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease;
}

.btn-book i {
  margin-right: 8px;
}

.btn-book:hover {
  background-color: #187bcd;
}

.loading {
  font-size: 1.5rem;
  color: #999;
  text-align: center;
}

.fa {
  font-size: 1.2rem;
}

.fa-spinner {
  font-size: 3rem;
  color: #1E90FF;
  margin-right: 10px;
}
</style>
