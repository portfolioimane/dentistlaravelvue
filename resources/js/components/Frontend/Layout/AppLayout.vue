<template>
  <div class="app-container">
    <Header />
    <main>
      <router-view />
    </main>
    <Footer />
  </div>
</template>

<script>
import Header from '../Header.vue';
import Footer from './Footer.vue';

export default {
  name: "AppLayout",
  components: {
    Header,
    Footer,
  },
};
</script>

<style scoped>
.app-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh; /* Make the container at least as tall as the viewport */
}

main {
  flex: 1; /* This will make the main content take up all available space, pushing footer to the bottom */
}
</style>
