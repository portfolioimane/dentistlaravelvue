<template>
  <div class="home-page">
    <!-- Home Page Header Section -->
    <HomePageHeader />

    <!-- Services Section -->
    <ServicesSection />

        <!-- Product List Section -->
    <FeaturedService />

        <FeaturedReviews />

  </div>
</template>

<script>
// Import the components
import HomePageHeader from './HomePageHeader.vue';
import ServicesSection from './ServicesSection.vue';  // Import the new ServicesSection component
import FeaturedService from './Services/FeaturedService.vue';
import FeaturedReviews from './FeaturedReviews.vue';



export default {
  name: 'Home',
  components: {
    HomePageHeader,
    ServicesSection, // Register the ServicesSection component
    FeaturedService,
    FeaturedReviews,



  },
};
</script>

<style scoped>
.home-page {
  display: flex;
  flex-direction: column;
  padding: 0;
}
</style>
