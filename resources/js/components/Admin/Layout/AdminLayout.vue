<template>
  <div class="admin-dashboard">
    <aside class="sidebar">
      <h2>Admin Dashboard</h2>
      <ul>
        <li>
          <router-link 
            to="/admin/dashboard" 
            class="sidebar-link" 
            :class="{ active: isActive('/admin/dashboard') }">
            <i class="material-icons sidebar-icon">dashboard</i>
            Dashboard
          </router-link>
        </li>

        <!-- Manage Services Section -->
        <li>
          <div @click="toggleServicesDropdown" class="dropdown-header">
            <i class="material-icons sidebar-icon">shopping_bag</i>
            Manage Services
            <i class="material-icons dropdown-arrow">{{ isServicesDropdownOpen ? 'arrow_drop_up' : 'arrow_drop_down' }}</i>
          </div>
          <ul v-if="isServicesDropdownOpen" class="dropdown-list">
            <li>
              <router-link 
                to="/admin/services" 
                class="sidebar-link" 
                :class="{ active: isActive('/admin/services') }">View Services</router-link>
            </li>
            <li>
              <router-link 
                to="/admin/services/add" 
                class="sidebar-link" 
                :class="{ active: isActive('/admin/services/add') }">Add Service</router-link>
            </li>
    

          </ul>
        </li>
        <li>
  <router-link 
    to="/admin/buisnesshours" 
    class="sidebar-link" 
    :class="{ active: isActive('/admin/buisnesshours') }">
    <i class="material-icons sidebar-icon">access_time</i>
    Business Hours
  </router-link>
</li>


        <li>
  <router-link 
    to="/admin/bookings" 
    class="sidebar-link" 
    :class="{ active: isActive('/admin/bookings') }">
    <i class="material-icons sidebar-icon">assignment_turned_in</i>
    Manage Bookings
  </router-link>
</li>

<li>
  <div @click="toggleCustomersDropdown" class="dropdown-header">
    <i class="material-icons sidebar-icon">people</i>
    Manage Patients
    <i class="material-icons dropdown-arrow">{{ isCustomersDropdownOpen ? 'arrow_drop_up' : 'arrow_drop_down' }}</i>
  </div>
  <ul v-if="isCustomersDropdownOpen" class="dropdown-list">
    <li>
      <router-link 
        to="/admin/patients" 
        class="sidebar-link" 
        :class="{ active: isActive('/admin/patients') }">
        View Patients
      </router-link>
    </li>
    <li>
      <router-link 
        to="/admin/patients/add" 
        class="sidebar-link" 
        :class="{ active: isActive('/admin/patients/add') }">
        Add Patient
      </router-link>
    </li>
  </ul>
</li>

        <!-- Manage Reviews Section (Dropdown) -->
        <li>
          <div @click="toggleReviewsDropdown" class="dropdown-header">
            <i class="material-icons sidebar-icon">reviews</i>
            Manage Reviews
            <i class="material-icons dropdown-arrow">{{ isReviewsDropdownOpen ? 'arrow_drop_up' : 'arrow_drop_down' }}</i>
          </div>
          <ul v-if="isReviewsDropdownOpen" class="dropdown-list">
            <li>
              <router-link 
                to="/admin/reviews" 
                class="sidebar-link" 
                :class="{ active: isActive('/admin/reviews') }">All Reviews</router-link>
            </li>
            <li>
              <router-link 
                to="/admin/reviews/add" 
                class="sidebar-link" 
                :class="{ active: isActive('/admin/reviews/add') }">Add Review</router-link>
            </li>
          </ul>
        </li>








<li>
  <router-link 
    to="/admin/contact-messages" 
    class="sidebar-link" 
    :class="{ active: isActive('/admin/contact-messages') }">
    <i class="material-icons sidebar-icon">message</i>
    Contact Messages
  </router-link>
</li>


        <!-- Manage Customize Section -->
        <li>
          <div @click="toggleCustomizeDropdown" class="dropdown-header">
            <i class="material-icons sidebar-icon">palette</i>
            Customize Website
            <i class="material-icons dropdown-arrow">{{ isCustomizeDropdownOpen ? 'arrow_drop_up' : 'arrow_drop_down' }}</i>
          </div>
          <ul v-if="isCustomizeDropdownOpen" class="dropdown-list">
              <li>
              <router-link 
                to="/admin/generalcustomize" 
                class="sidebar-link" 
                :class="{ active: isActive('/admin/generalcustomize') }">General</router-link>
            </li>
            <li>
              <router-link 
                to="/admin/customize/homepageheader" 
                class="sidebar-link" 
                :class="{ active: isActive('/admin/customize/homepageheader') }">HeroSection</router-link>
            </li>
          </ul>
        </li>

        <!-- Manage Settings Section -->
<li>
  <div @click="toggleSettingsDropdown" class="dropdown-header">
    <i class="material-icons sidebar-icon">settings</i>
    Manage Settings
    <i class="material-icons dropdown-arrow">{{ isSettingsDropdownOpen ? 'arrow_drop_up' : 'arrow_drop_down' }}</i>
  </div>
  <ul v-if="isSettingsDropdownOpen" class="dropdown-list">
    
    <!-- Payment Settings Link -->
    <li>
      <router-link 
        to="/admin/paymentsetting" 
        class="sidebar-link" 
        :class="{ active: isActive('/admin/paymentsetting') }">Payments</router-link>
    </li>
    
    <!-- Email Settings Link -->
    <li>
      <router-link 
        to="/admin/emailsetting" 
        class="sidebar-link" 
        :class="{ active: isActive('/admin/emailsetting') }">Email Settings</router-link>
    </li>

    <!-- SMS Settings Link -->
    <li>
      <router-link 
        to="/admin/smssetting" 
        class="sidebar-link" 
        :class="{ active: isActive('/admin/smssetting') }">SMS Settings</router-link>
    </li>

  </ul>
</li>

      </ul>
    </aside>

    <main class="content">
      <div class="navbar-section">
        <a :href="websiteUrl" class="navbar-link view-website" target="_blank">
          <i class="material-icons navbar-icon">public</i>
          View Website
        </a>
        <button @click="logout" class="navbar-btn logout-btn">
          <i class="material-icons">exit_to_app</i> Logout
        </button>
      </div>

      <router-view />
    </main>
  </div>
</template>

<script>
export default {
  name: 'AdminLayout',
data() {
  return {
    isServicesDropdownOpen: false,
    isCustomizeDropdownOpen: false, 
    isSettingsDropdownOpen: false,
    isReviewsDropdownOpen: false, // For the reviews dropdown
    isCustomersDropdownOpen: false, // For the customers dropdown
  };
},
methods: {
  toggleServicesDropdown() {
    this.isServicesDropdownOpen = !this.isServicesDropdownOpen;
  },
  toggleCustomizeDropdown() {
    this.isCustomizeDropdownOpen = !this.isCustomizeDropdownOpen;
  },
  toggleSettingsDropdown() {
    this.isSettingsDropdownOpen = !this.isSettingsDropdownOpen;
  },
  toggleReviewsDropdown() {
    this.isReviewsDropdownOpen = !this.isReviewsDropdownOpen; // For toggling reviews dropdown
  },
  toggleCustomersDropdown() {
    this.isCustomersDropdownOpen = !this.isCustomersDropdownOpen; // For toggling customers dropdown
  },
  isActive(route) {
    return this.$route.path === route;
  },
  logout() {
    this.$store.dispatch('auth/logout');
    this.$router.push('/login');
  }
},

  mounted() {
    this.$store.dispatch('auth/checkAuth');
  }
};
</script>


<style scoped>
/* Global reset and font settings */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f4f4f4;
}

.admin-dashboard {
  display: flex;
  height: 100vh;
  overflow: hidden;
  color: #333;
}

/* Sidebar */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 250px;
  height: 100vh;
  background-color: var(--secondary-color);
  color: #f4f4f4;
  padding: 20px;
  overflow-y: auto;
  border-right: 1px solid #444;
  z-index: 100;
}

/* Content Area */
.content {
  margin-left: 250px;
  flex-grow: 1;
  padding: 20px;
  background-color: #ffffff;
  color: #333;
  height: 100vh;
  overflow-y: auto;
}

/* Navbar Section */
.navbar-section {
  background-color: #ffffff;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-radius: 5px;
  padding: 10px 20px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.sidebar h2 {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 30px;
  color: #f4f4f4;
}

.sidebar ul {
  padding: 0;
  margin: 0;
  list-style-type: none;
}

.sidebar li {
  margin-bottom: 15px;
}

/* Dropdown Section */
.dropdown-header {
  cursor: pointer;
  padding: 10px;
  font-size: 18px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #f4f4f4;
  transition: background-color 0.3s ease;
}

.dropdown-header:hover {
  background-color: #187bcd;
}

.dropdown-list {
  padding-left: 20px;
  margin-top: 10px;
}

/* Sidebar Links */
.sidebar-link {
  color: #f4f4f4;
  text-decoration: none;
  font-size: 16px;
  padding: 12px;
  display: flex;
  align-items: center;
  border-radius: 4px;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.sidebar-link:hover {
  background-color: #187bcd;
}

.sidebar-link.active {
  background-color: #187bcd;
}

.sidebar-icon {
  margin-right: 10px;
}

/* Navbar and Logout Button */
.navbar-link, .navbar-btn {
  font-size: 16px;
  color: var(--secondary-color);
  font-weight: bold;
  text-decoration: none;
  display: flex;
  align-items: center;
  margin-right: 15px;
  transition: color 0.3s ease;
}

.navbar-link:hover, .navbar-btn:hover {
  color: #1E90FF;
}

.navbar-icon {
  margin-right: 8px;
}

/* Logout Button */
.navbar-btn {
  color: var(--secondary-color);
  border: none;
  font-size: 16px;
  cursor: pointer;
  border-radius: 5px;
  padding: 8px 16px;
  display: flex;
  align-items: center;
  transition: background-color 0.3s ease;
  background-color: transparent;
}
</style>