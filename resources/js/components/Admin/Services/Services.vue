<template>
  <div class="services">
    <h1>Manage Services</h1>
    
    <table class="services-table">
      <thead>
        <tr>
          <th>Service ID</th>
          <th>Name</th>
          <th>Description</th>
          <th>Cost (MAD)</th>
          <th>Duration</th> <!-- New column for Duration -->
          <th>Image</th>
                    <th>Featured</th>

          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="service in allServices" :key="service.id">
          <td>{{ service.id }}</td>
          <td>{{ service.name }}</td>
          <td>{{ service.description }}</td>
          <td>{{ parseFloat(service.cost).toFixed(2) }}</td>
          <td>{{ formatDuration(service.duration) }}</td> 
          <td>
            <img 
              :src="service.image ? `${service.image}` : '/images/services/default-service.png'" 
              alt="Service Image" 
              class="service-image"
            />
          </td>
          
          
          
         <td>
  <input 
    type="checkbox" 
    :checked="service.featured" 
    @change="toggleFeatured(service)" 
  />
</td>

          <td class="action-buttons">
            <div class="edit-delete-btns">
              <button class="btn secondary" @click="editService(service)">Edit</button>
              <button class="btn danger" @click="openDeleteModal(service.id)">Delete</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Are you sure you want to delete this service?</h3>
        <div class="modal-actions">
          <button class="btn danger" @click="deleteService">Yes, Delete</button>
          <button class="btn primary" @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'Services',
  data() {
    return {
      showDeleteModal: false,
      serviceToDelete: null,
      message: null,
    };
  },
  computed: {
      ...mapGetters('backendServices', ['allServices']),

  services() {
    return this.allServices.map(service => ({
      ...service,
      featured: Boolean(service.featured), // Force boolean conversion
    }));
  },
},

  methods: {
    formatDuration(durationInMinutes) {
      const hours = Math.floor(durationInMinutes / 60);
      const minutes = durationInMinutes % 60;
      return `${hours}h ${minutes}m`;
    },
    editService(service) {
      console.log('Editing service:', service);  // Debug log
      this.$router.push({ name: 'EditService', params: { id: service.id } });
    },
    openDeleteModal(serviceId) {
      console.log('Opening delete modal for serviceId:', serviceId);  // Debug log
      this.serviceToDelete = serviceId;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      console.log('Closing delete modal');  // Debug log
      this.showDeleteModal = false;
      this.serviceToDelete = null;
    },
    async deleteService() {
      try {
        console.log('Deleting service with ID:', this.serviceToDelete);  // Debug log
        await this.$store.dispatch('backendServices/deleteService', this.serviceToDelete);
        this.showDeleteModal = false;
        this.serviceToDelete = null;
        this.showMessage('Service deleted successfully', 'success');
      } catch (error) {
        this.showMessage('Error deleting service', 'error');
        console.error('Error deleting service:', error);
      }
    },
    showMessage(text, type) {
      this.message = { text, type };
      setTimeout(() => {
        this.message = null;
      }, 5000);
    },
async toggleFeatured(service) {
  try {
    service.featured = !service.featured; // Ensure local update
    await this.$store.dispatch('backendServices/toggleFeatured', service.id);
    this.showMessage('Service featured status updated', 'success');
  } catch (error) {
    this.showMessage('Error updating featured status', 'error');
    console.error('Error updating featured status:', error);
  }
},

  },
  mounted() {
    console.log('Fetching services...');  // Debug log
    this.$store.dispatch('backendServices/fetchServices')
      .then(() => {
        console.log('Services fetched successfully:', this.allServices);  // Debug log
      })
      .catch((error) => {
        console.error('Error fetching services:', error);  // Debug log
      });
  },
};
</script>


<style scoped>
.services {
  padding: 30px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
  color: #1E90FF;
  margin-bottom: 20px;
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
}

.services-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.services-table th,
.services-table td {
  border: 1px solid #ddd;
  padding: 15px;
  text-align: left;
}

.services-table th {
  background-color: #1E90FF;
  color: white;
  font-weight: bold;
}

.services-table tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

.services-table tbody tr:hover {
  background-color: #f9f9f9;
}

.service-image {
  max-width: 100px;
  max-height: 100px;
  object-fit: cover;
}

.btn {
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
  font-weight: bold;
}

.btn.secondary {
  background-color: #1E90FF;
  color: white;
}

.btn.danger {
  background-color: #e74c3c;
  color: white;
}

.btn.primary {
  background-color: #1E90FF;
  color: white;
}

.btn:hover {
  opacity: 0.9;
}

/* Action Buttons Styling */
.action-buttons {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
}

.edit-delete-btns {
  display: flex;
  gap: 10px;
}

.service-image {
  width: 100px !important;
  height: 100px !important;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  width: 100%;
  text-align: center;
}

.modal-actions {
  margin-top: 20px;
}

.modal-actions button {
  margin: 0 10px;
}

</style>
