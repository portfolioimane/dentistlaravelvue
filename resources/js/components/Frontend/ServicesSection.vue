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
  name: "DentalServicesSection",
  data() {
    return {
      services: [
        {
          title: "High-Quality Teeth Whitening",
          icon: "fas fa-tooth", // FontAwesome icon for Teeth Whitening
          description: "Achieve a brilliant, white smile with our safe and effective teeth whitening treatments, designed to provide long-lasting results and improve your confidence.",
        },
        {
          title: "Expert Root Canal Treatment",
          icon: "fas fa-syringe", // FontAwesome icon for Root Canal Treatment (Syringe for medical procedures)
          description: "Save your natural teeth with our expert root canal treatments, performed by experienced dentists to ensure minimal discomfort and maximum care.",
        },
        {
          title: "Durable Dental Implants",
          icon: "fas fa-user-md", // FontAwesome icon for Dental Implants
          description: "Restore your smile with top-quality dental implants that are long-lasting and look natural, offering both functionality and aesthetic appeal.",
        }
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
