<template>
  <footer class="footer">
    <div class="footer-container">
      <!-- Column 1: Logo and Description -->
      <div class="footer-column">
        <img :src="logo" alt="Company Logo" class="footer-logo" />
        <p class="footer-description">{{ footerDescription }}</p>
      </div>

      <!-- Column 2: Navigation Links -->
      <div class="footer-column">
        <h4 class="footer-heading">Quick Links</h4>
        <ul class="footer-links">
          <li><a href="/home" class="footer-link">Home</a></li>
          <li><a href="/shop" class="footer-link">Shop</a></li>
          <li><a href="/contact" class="footer-link">Contact</a></li>
        </ul>
      </div>

      <!-- Column 3: Social Media Links -->
      <div class="footer-column">
        <h4 class="footer-heading">Follow Us</h4>
        <div class="social-icons">
          <a :href="socialLinks.facebook" target="_blank" class="social-icon">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a :href="socialLinks.instagram" target="_blank" class="social-icon">
            <i class="fab fa-instagram"></i>
          </a>
          <a :href="socialLinks.tiktok" target="_blank" class="social-icon">
            <i class="fab fa-tiktok"></i>
          </a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "FooterComponent",
  computed: {
    // Getting data from Vuex store
    logo() {
      return this.getGeneralCustomize("logo");
    },
    footerDescription() {
      return this.getGeneralCustomize("footer");
    },
    socialLinks() {
      return {
        facebook: this.getGeneralCustomize("facebook"),
        instagram: this.getGeneralCustomize("instagram"),
        tiktok: this.getGeneralCustomize("tiktok"),
      };
    },
    ...mapGetters("generalCustomize", ["getGeneralCustomize"]),
  },
  mounted() {
    // Fetch general customize data when the component is mounted
    this.fetchGeneralCustomizeData();
  },
  methods: {
    fetchGeneralCustomizeData() {
      // Trigger the action to fetch general customize data
      this.$store.dispatch("generalCustomize/fetchGeneralCustomizes");
    },
  },
};
</script>

<style scoped>
.footer {
  background-color: #333;
  color: white;
  padding: 2rem;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  gap: 2rem;
  flex-wrap: wrap;
}

.footer-column {
  flex: 1;
  min-width: 250px;
}

.footer-logo {
  width: 120px;
  height: auto;
}

.footer-description {
  margin-top: 1rem;
  font-size: 1rem;
  color: #ccc;
}

.footer-heading {
  font-size: 1.2rem;
  font-weight: bold;
  margin-bottom: 1rem;
}

.footer-links {
  list-style: none;
  padding: 0;
}

.footer-link {
  display: block;
  color: #1E90FF;
  text-decoration: none;
  font-size: 1rem;
  margin: 0.5rem 0;
  transition: color 0.3s ease;
}

.footer-link:hover {
  color: #fff;
}

.social-icons {
  display: flex;
  gap: 1rem;
}

.social-icon {
  color: #1E90FF;
  font-size: 1.5rem;
  transition: transform 0.3s ease, color 0.3s ease;
}

.social-icon:hover {
  transform: scale(1.1);
  color: #fff;
}

@media (max-width: 768px) {
  .footer-container {
    flex-direction: column;
    align-items: center;
  }
}
.footer-logo {
  width: 100px !important;
  height: 100px !important;
}

</style>
