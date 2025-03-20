<template>
  <div>
    <h1>Our Services</h1>

    <div v-if="isLoading" class="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="!isLoading && !error" class="service-list">
      <div v-for="service in services" :key="service.id" class="service-card">
        <router-link :to="{ name: 'service', params: { serviceId: service.id } }" class="service-link">
          <div class="service-image">
            <img :src="service.image || 'default-image-url.jpg'" alt="Service Image" />
          </div>
          <div class="service-details">
            <h3 class="service-title">{{ service.name }}</h3>
            <p class="service-description">{{ service.description }}</p>
            <p class="service-price">{{ service.cost ? `${service.cost} MAD` : 'Contact for Pricing' }}</p>
            <button class="view-button">View Details</button>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'ServicesList',
  computed: {
    ...mapGetters('services', {
      services: 'allServices',  // Mapping `allServices` from Vuex getter to `services` in the component
      isLoading: 'isLoading',  // Mapping `isLoading` from Vuex getter to `isLoading` in the component
      error: 'error',  // Mapping `error` from Vuex getter to `error` in the component
    }),
  },
  created() {
    this.fetchServices();  // Dispatching the action to fetch services
  },
  methods: {
    ...mapActions('services', ['fetchServices']),  // Mapping the `fetchServices` action from Vuex
  },
};
</script>

<style scoped>
.error {
  color: red;
}

.loading {
  font-size: 1.5rem;
  color: #999;
}

.service-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  padding: 20px;
}

.service-card {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.service-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.service-link {
  display: block;
  text-decoration: none;
  color: inherit;
}

.service-image img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.service-details {
  padding: 20px;
}

.service-title {
  font-size: 1.2rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 10px;
}

.service-description {
  font-size: 1rem;
  color: #555;
  margin-bottom: 15px;
}

.service-price {
  font-size: 1rem;
  font-weight: bold;
  color: #1E90FF;
  margin-bottom: 10px;
}

.view-button {
  background-color: #1E90FF;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.view-button:hover {
  background-color: #187bcd;
}
</style>
