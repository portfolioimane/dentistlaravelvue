<template>
  <section class="services-section">
    <div class="service" v-for="(service, index) in services" :key="index">
      <div class="icon">
        <i :class="service.icon"></i>
      </div>
      <div class="text">
        <h3>{{ service.title }}</h3>
        <p>{{ service.description }}</p>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "ServicesSection",
  data() {
    return {
      services: [
        {
          title: "Digital Marketing",
          icon: "fas fa-bullhorn", // FontAwesome icon for Digital Marketing
          description: "Boost your business with targeted digital marketing strategies and campaigns.",
        },
        {
          title: "Sales Growth",
          icon: "fas fa-chart-line", // FontAwesome icon for Sales Growth
          description: "Drive sales and increase revenue with proven sales growth tactics and tools.",
        },
        {
          title: "Business Strategy",
          icon: "fas fa-cogs", // FontAwesome icon for Business Strategy
          description: "Optimize your business processes and improve efficiency with expert strategy guidance.",
        },
      ],
    };
  },
};
</script>

<style scoped>
.services-section {
  display: flex;
  justify-content: space-between;
  text-align: center;
  padding: 20px;
  background-color: #f8f8f8;
}

.service {
  flex: 1;
  padding: 15px;
  border-radius: 8px;
  background: white;
  box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
  margin: 10px;
  transition: transform 0.3s ease-in-out;
}

.service:hover {
  transform: translateY(-5px);
}

.icon {
  font-size: 40px;
  color: #63AFFF; /* Lighter blue */
  margin-bottom: 10px;
}

.text h3 {
  font-size: 18px;
  margin-bottom: 5px;
}

.text p {
  font-size: 14px;
  color: #555;
}
</style>
