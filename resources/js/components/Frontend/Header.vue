<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <img :src="logo" alt="Company Logo" class="header-logo" />
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <router-link to="/" class="nav-link">Home</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/servicesList" class="nav-link">Services</router-link>
        </li>
        <li class="nav-item">
          <router-link to="/contact" class="nav-link">Contact</router-link>
        </li>

  

        <!-- Login/Register Dropdown -->
        <li class="nav-item dropdown" v-if="!isAuthenticated">
          <a class="nav-link dropdown-toggle" href="#" id="authDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="material-icons">person</span> Account
            <span class="material-icons ms-1">arrow_drop_down</span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="authDropdown">
            <li><router-link to="/login" class="dropdown-item">Login</router-link></li>
            <li><router-link to="/register" class="dropdown-item">Register</router-link></li>
          </ul>
        </li>

        <!-- Profile Dropdown (for authenticated users) -->
        <li class="nav-item dropdown" v-if="isAuthenticated && user">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="material-icons">account_circle</span> {{ user.name }}
            <span class="material-icons ms-1">arrow_drop_down</span>
          </a>
          <ul class="dropdown-menu" aria-labelledby="userDropdown">
            <li><router-link to="/customerdashboard/profile" class="dropdown-item">Profile</router-link></li>
            <li><router-link to="/customerdashboard/myenrollments" class="dropdown-item">CustomerDashboard</router-link></li>
            <li><a @click="logout" class="dropdown-item">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'Navbar',

  computed: {
    ...mapGetters('auth', ['isAuthenticated', 'user']),

    ...mapGetters("generalCustomize", ["getGeneralCustomize"]),
       logo() {
      return this.getGeneralCustomize("logo");
    },
  },

  data() {
    return {
      loading: true,  // Loading state to track wishlist and cart data fetch
    };
  },

  watch: {
    // Watch for changes in the authentication state
    isAuthenticated(newVal) {
      if (newVal) {
        // If logged in, fetch the cart and wishlist
        this.$store.dispatch('auth/checkAuth');
    
      } 
    }
  },

  mounted() {
    this.$store.dispatch('auth/checkAuth');  // Check if the user is authenticated using the cookie
        this.fetchGeneralCustomizeData();

    // Only fetch cart and wishlist if authenticated
  
  },



  methods: {
     fetchGeneralCustomizeData() {
      // Trigger the action to fetch general customize data
      this.$store.dispatch("generalCustomize/fetchGeneralCustomizes");
    },




    async logout() {
      try {
        await this.$store.dispatch('auth/logout');  // This will logout the user and clear the session
        this.$router.push('/');  // Redirect to home after logout
      } catch (error) {
        console.error('Logout failed:', error);
      }
    },
  },
};
</script>

<style scoped>
/* Navbar Styling */
.navbar {
  background-color: #fff;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.navbar-brand {
  font-size: 1.75rem;
  font-weight: 700;
  color:var(--primary-color);
}

.navbar-nav .nav-link {
  padding: 12px 10px;
  font-size: 1rem;
  font-weight: 500;
  color: #333;
  text-transform: uppercase;
}

.navbar-nav .nav-link:hover {
  color:var(--primary-color);
  transition: color 0.3s ease;
}

/* Cart and Wishlist Badge Styling */
.badge {
  font-size: 14px;
  background-color:var(--primary-color);
  color: white;
  border-radius: 50%;
  padding: 3px 7px;
}

/* Dropdown Styling */
.nav-item .dropdown-menu {
  border-radius: 8px;
}

.nav-item .dropdown-item {
  color: #333;
  font-weight: 400;
}

.nav-item .dropdown-item:hover {
  background-color: var(--primary-color);
  color: white;
}

.nav-item.dropdown .dropdown-toggle::after {
  content: none;
}

.navbar-nav .nav-item {
  position: relative;
}

/* Adjust Material Icons */
.material-icons {
  font-size: 1.5rem;
  vertical-align: middle;
}

.cart-icon, .wishlist-icon {
  color:var(--primary-color) !important;
}

.bg-badge{
  background-color:var(--primary-color) !important;
  color:#fff;
}
/* Add arrow icon to indicate dropdown */
.material-icons.ms-1 {
  font-size: 1rem;
  margin-left: 4px;
}

/* Mobile Responsiveness */
@media (max-width: 991px) {
  .navbar-brand {
    font-size: 1.5rem;
  }

  .nav-link {
    padding: 8px 12px;
    font-size: 0.9rem;
  }
}
.header-logo {
  width: 80px !important;
  height: 80px !important;
}

</style>
