<template>
  <div class="edit-service">
    <h1>Edit Service</h1>
    <form @submit.prevent="submitForm" class="service-form">
      <div class="form-group">
        <label for="name">Service Name:</label>
        <input
          type="text"
          id="name"
          v-model="service.name"
          required
          placeholder="e.g., Website Development"
        />
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea
          id="description"
          v-model="service.description"
          required
          placeholder="Describe the service"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="cost">Cost (in MAD):</label>
        <input
          type="number"
          id="cost"
          v-model="service.cost"
          required
          placeholder="e.g., 1500"
          min="0"
        />
      </div>

      <div class="form-group">
        <label for="duration">Duration (in minutes):</label>
        <input
          type="number"
          id="duration"
          v-model="service.duration"
          required
          placeholder="e.g., 60"
          min="1"
        />
      </div>

      <!-- Image upload -->
      <div class="form-group">
        <label for="image">Upload Image (Max 2MB):</label>
        <input
          type="file"
          id="image"
          @change="handleImageUpload"
          accept="image/*"
        />
        <small class="help-text">
          <span>For optimal performance, please upload compressed images with a maximum size of 2MB. Consider using a tool to reduce the file size before uploading.</span>
        </small>
        <!-- Display error message if the image is too large -->
        <p v-if="imageError" class="error-message">The image file size exceeds the 2MB limit. Please upload a smaller image.</p>
      </div>

      <!-- Image preview -->
      <div v-if="imagePreview" class="image-preview">
        <img :src="imagePreview" alt="Service Image Preview" class="img-fluid img-preview" />
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Save Changes</button>
        <router-link to="/admin/services" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  data() {
    return {
      service: {
        id: null,
        name: '',
        description: '',
        cost: '',
        duration: '',
        image: null,
      },
      imageError: false,  // Flag for image validation error
      imagePreview: null, // URL for image preview
    };
  },
  computed: {
    ...mapGetters('backendServices', ['currentService']),
  },
  methods: {
    ...mapActions('backendServices', ['fetchServiceById', 'updateService']),

    async fetchService() {
      const serviceId = this.$route.params.id;
      await this.fetchServiceById(serviceId);
      this.service = { ...this.currentService };
      this.imagePreview = this.service.image ? `${this.service.image}` : null;
    },

    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        if (file.size > 2 * 1024 * 1024) {
          this.imageError = true;
          this.service.image = null;
          this.imagePreview = null;
        } else {
          this.imageError = false;
          this.service.image = file;

          // Create a preview URL for the image
          this.imagePreview = URL.createObjectURL(file);
        }
      }
    },

    async submitForm() {
      if (this.imageError) {
        alert("Please upload a valid image.");
        return;
      }

      const formData = new FormData();
      formData.append('name', this.service.name);
      formData.append('description', this.service.description);
      formData.append('cost', this.service.cost);
      formData.append('duration', this.service.duration);

      if (this.service.image) {
        formData.append('image', this.service.image);
      }

      await this.updateService({ id: this.$route.params.id, formData });
      this.$router.push('/admin/services');
    },
  },
  mounted() {
    this.fetchService();
  },
};
</script>

<style scoped>
.edit-service {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.edit-service h1 {
  color: #1E90FF;
  font-size: 1.6rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-align: center;
}

.service-form .form-group {
  margin-bottom: 0.5rem;
}

.service-form label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.25rem;
  color: #555;
}

.service-form input[type="text"],
.service-form input[type="number"],
.service-form input[type="file"],
.service-form select,
.service-form textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.9rem;
}

.service-form input:focus,
.service-form select:focus,
.service-form textarea:focus {
  border-color: #d4af37;
  outline: none;
}

.service-form textarea {
  resize: vertical;
  min-height: 80px;
}

.button-group {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  font-size: 0.9rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  text-transform: uppercase;
}

.btn.primary {
  background-color: #1E90FF;
  color: #fff;
}

.btn.secondary {
  background-color: #555;
  color: #fff;
}

.btn:hover {
  opacity: 0.9;
}

.help-text {
  font-size: 0.85rem;
  color: #777;
  margin-top: 0.5rem;
}

.error-message {
  color: red;
  font-size: 0.85rem;
  margin-top: 0.5rem;
}

.img-preview {
  width: 100px !important;
  height: 100px !important;
}

@media (max-height: 768px) {
  .edit-service {
    margin: 1rem auto;
  }

  .service-form input,
  .service-form textarea {
    padding: 0.4rem;
    font-size: 0.85rem;
  }

  .btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
  }
}
</style>
