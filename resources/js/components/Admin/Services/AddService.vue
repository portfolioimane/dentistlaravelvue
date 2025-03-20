<template>
  <div class="add-service">
    <h1>Add New Service</h1>
    <form @submit.prevent="submitForm" class="service-form">
      <div class="form-group">
        <label for="name">Service Title:</label>
        <input
          type="text"
          id="name"
          v-model="newService.name"
          required
          placeholder="e.g., Web Development"
        />
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea
          id="description"
          v-model="newService.description"
          required
          placeholder="Describe the service"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="cost">Price (in MAD):</label>
        <input
          type="number"
          id="cost"
          v-model="newService.cost"
          required
          placeholder="e.g., 1500"
          min="0"
        />
      </div>

      <!-- Duration Field -->
      <div class="form-group">
        <label for="duration">Duration (in minutes):</label>
        <input
          type="number"
          id="duration"
          v-model="newService.duration"
          required
          placeholder="e.g., 120"
          min="1"
        />
      </div>

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

      <!-- Preview Section -->
      <div v-if="newService.image" class="image-preview">
        <img :src="imagePreview" alt="Service Image Preview" />
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Add Service</button>
        <router-link to="/admin/services" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      newService: {
        name: '',         // Mapping name field
        description: '',  // Mapping description field
        cost: '',         // Mapping cost field
        image: null,
        duration: '',     // Mapping duration field
      },
      imageError: false, // Flag to track image validation error
      imagePreview: '',  // URL for image preview
    };
  },
  methods: {
    handleImageUpload(event) {
      const file = event.target.files[0];  // Handling image upload
      if (file) {
        // Check if file size exceeds 2MB (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
          this.imageError = true; // Set error flag to true
          this.newService.image = null; // Reset the image if it exceeds the size limit
          this.imagePreview = ''; // Reset preview if the image is invalid
        } else {
          this.imageError = false; // No error if file is valid
          this.newService.image = file; // Set the image if it's valid

          // Create a preview URL for the image
          const reader = new FileReader();
          reader.onload = (e) => {
            this.imagePreview = e.target.result; // Set the preview URL
          };
          reader.readAsDataURL(file); // Read the file as a data URL
        }
      }
    },
    async submitForm() {
      if (this.imageError) {
        // Prevent form submission if there's an image error
        alert("Please upload a valid image.");
        return;
      }

      console.log("Form submission started");
      const formData = new FormData();
      Object.entries(this.newService).forEach(([key, value]) => {
        console.log(`Appending ${key}: ${value}`);  // Debugging the form data
        formData.append(key, value);
      });

      try {
        console.log("Dispatching addService action with formData:", formData);
        await this.$store.dispatch('backendServices/addService', formData);  // Dispatching action to store
        console.log("Service added successfully");
        this.$router.push('/admin/services');  // Redirect to service list
      } catch (error) {
        console.error("Error adding service:", error);  // Handling error
      }
    },
  },
};
</script>

<style scoped>
.image-preview {
  margin-top: 1rem;
  text-align: left;
}

.image-preview img {
  width: 100px !important;
  height: 100px !important;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.add-service {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.add-service h1 {
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

@media (max-height: 768px) {
  .add-service {
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
