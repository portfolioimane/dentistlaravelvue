<template>
  <div class="customer-dashboard">
    <aside class="sidebar">
      <h2>Welcome, Customer!</h2>
      <ul>
        <li>
          <router-link 
            to="/customerdashboard/profile" 
            class="sidebar-link" 
            :class="{ active: isActive('/customerdashboard/profile') }">
            <i class="material-icons sidebar-icon">person</i>
            My Profile
          </router-link>
        </li>
        <li>
          <router-link 
            to="/customerdashboard/mybookings" 
            class="sidebar-link" 
            :class="{ active: isActive('/customerdashboard/mybookings') }">
            <i class="material-icons sidebar-icon">shopping_cart</i>
            My Bookings
          </router-link>
        </li>
   
      </ul>
    </aside>
    <main class="content">
      <router-view /> <!-- Nested views rendered here -->
    </main>
  </div>
</template>

<script>
export default {
  name: 'CustomerLayout',
  methods: {
    isActive(route) {
      return this.$route.path === route;
    }
  }
};
</script>

<style scoped>
.customer-dashboard {
  display: flex;
  min-height: 100vh;
  background-color: #f5f5f5; /* Soft light gray for overall background */
}

.sidebar {
  width: 240px;
  color: #333; /* Darker text for readability */
  display: flex;
  flex-direction: column;
  padding: 20px;
  border-right: 1px solid #ddd;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); /* Soft shadow for a professional feel */
}

.sidebar h2 {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 30px;
  color: var(--primary-color); /* Golden color for emphasis */
}

.sidebar ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.sidebar li {
  margin-bottom: 15px;
}

.sidebar-link {
  display: flex;
  align-items: center;
  padding: 12px 15px;
  text-decoration: none;
  color: #333;
  font-size: 16px;
  font-weight: 500;
  background-color: #fff;
  border-radius: 8px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Slight shadow effect */
}

.sidebar-link i {
  margin-right: 10px;
  color: var(--primary-color); /* Golden icon for consistency */
}

.sidebar-link:hover {
  background-color: #fff7e6; /* Subtle golden hover effect */
}

.sidebar-link.active {
  background-color: #f0e1a7; /* Light golden for active links */
  font-weight: bold;
  color: var(--primary-color);
  box-shadow: 0 4px 10px rgba(243, 156, 18, 0.2); /* Stronger shadow for active link */
}

.content {
  flex-grow: 1;
  padding: 20px;
  background-color: #ffffff; /* Clean white for content area */
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1); /* Subtle inset shadow */
  overflow-y: auto;
}
</style>
