<template>
  <div class="container mt-5">
    <h2 class="section-title">Featured Services</h2>
    <div class="row">
      <div v-for="service in services" :key="service.id" class="col-lg-3 col-md-6 mb-4">
        <div class="card shadow-sm border-0 rounded-lg h-100 position-relative">
          <img :src="`${service.image}`" class="card-img-top rounded-top" alt="Service" />
          <div class="card-body p-3">
            <h5 class="card-title text-dark font-weight-bold">{{ service.name }}</h5>
            <p class="card-text text-muted" style="font-size: 0.875rem;">{{ service.description }}</p>
            <div class="d-flex justify-content-between align-items-center mt-2">
              <p class="font-weight-bold text-golden" style="font-size: 1rem;">${{ service.cost }}</p>
              <p class="font-weight-bold text-success" style="font-size: 0.9rem;">Duration: {{ service.duration }} mins</p>
            </div>
            <button @click="viewDetails(service.id)" class="btn btn-golden mt-3">View Details</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { reactive } from 'vue';

export default {
  computed: {
    services() {
      return this.$store.getters['services/featuredServices'];  // Fetch featured services from the store
    }
  },
  methods: {
    viewDetails(serviceId) {
      this.$router.push(`/service/${serviceId}`);  // Navigate to the service details page
    }
  },
  created() {
    this.$store.dispatch('services/fetchFeaturedServices');  // Fetch featured services when the component is created
  },
  mounted() {
    this.$store.dispatch('services/fetchFeaturedServices');  // Fetch featured services when the component is mounted
  },
};
</script>

<style scoped>
.section-title {
  font-size: 2rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 2.5rem;
  color: #333;
}

.card-img-top {
  height: 200px;
  object-fit: cover;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.card-body {
  padding: 1rem;
}

.card-title {
  font-size: 1.1rem;
  font-weight: 600;
  color: #333;
}

.card-text {
  font-size: 0.875rem;
  color: #666;
}

.text-muted {
  font-size: 0.8rem;
}

.text-primary {
  color: #0066cc;
}

.text-success {
  color: #28a745;
}

.card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 10px;
}

.card:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.text-golden {
  color: #ffd700 !important;
  font-weight: bold;
}

.btn-outline-dark {
  border-color: #333;
  color: #333;
  transition: all 0.3s ease;
}

.btn-outline-dark:hover {
  background-color: #333;
  color: #fff;
}

.text-center.text-muted {
  font-size: 0.9rem;
  color: #999;
  margin-top: 1rem;
  font-style: italic;
}

@media (max-width: 767px) {
  .card-body {
    padding: 0.75rem;
  }

  .card-title {
    font-size: 1rem;
  }

  .text-golden {
    font-size: 1rem;
  }
}
</style>
