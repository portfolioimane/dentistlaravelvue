<template>
  <div class="header-section">
    <!-- Display Error or Success Message -->
    <div v-if="notification" :class="notification.type" class="notification">
      {{ notification.message }}
    </div>

    <!-- Form for Updating Header -->
    <form class="header-form" @submit.prevent="updateHeader">
      <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" v-model="form.title" id="title" class="form-input" />
      </div>
      <div class="form-group">
        <label for="subtitle">Subtitle:</label>
        <input
          type="text"
          v-model="form.subtitle"
          id="subtitle"
          class="form-input"
        />
      </div>
      <div class="form-group">
        <label for="buttonText">Button Text:</label>
        <input
          type="text"
          v-model="form.buttonText"
          id="buttonText"
          class="form-input"
        />
      </div>
      
      <!-- Image Upload Section -->
      <div class="form-group">
        <p class="image-note">Please upload an image compressed to a maximum size of 2MB (2048 KB).</p>
        
        <!-- Display Error Message for Image -->
        <div v-if="imageError" class="error-message">{{ imageError }}</div>
        
        

        <input
          type="file"
          @change="handleImageChange"
          id="image"
          class="form-input-file"
        />
        <!-- Display Existing Image if Available -->
        <div v-if="form.imagePreview">
          <img :src="form.imagePreview" alt="Existing Image" class="existing-image" />
        </div>
      </div>
      
      <button type="submit" class="form-button">Update Header</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        title: '',
        subtitle: '',
        buttonText: '',
        image: null,
        imagePreview: null, // To hold the existing image preview URL
      },
      notification: null, // Notification message object
      imageError: null,   // Error message for image size
    };
  },
  computed: {
    header() {
      return this.$store.getters['backendHomePageHeader/getHeader'];
    },
    error() {
      return this.$store.getters['backendHomePageHeader/getError'];
    },
  },
  watch: {
    header: {
      immediate: true, // Trigger when component is created
      handler(newHeader) {
        if (newHeader) {
          this.form.title = newHeader.title || '';
          this.form.subtitle = newHeader.subtitle || '';
          this.form.buttonText = newHeader.buttonText || '';
          if (newHeader.image) {
            this.form.imagePreview = newHeader.image; // Assuming `image` holds the URL
          }
        }
      },
    },
  },
  created() {
    this.$store.dispatch('backendHomePageHeader/fetchHeader');
  },
  methods: {
    // Show success or error message
    showNotification(type, message) {
      this.notification = { type, message };
      setTimeout(() => {
        this.notification = null; // Clear the notification after 3 seconds
      }, 3000);
    },

    updateHeader() {
      const formData = new FormData();
      formData.append('title', this.form.title);
      formData.append('subtitle', this.form.subtitle);
      formData.append('buttonText', this.form.buttonText);
      if (this.form.image) {
        formData.append('image', this.form.image);
      }

      // Append the HTTP method (PUT) to the formData
      formData.append('_method', 'PUT');  // _method is often used in Laravel to simulate methods

      this.$store
        .dispatch('backendHomePageHeader/updateHeader', formData)
        .then(() => {
          this.showNotification('success', 'Header updated successfully!');
        })
        .catch((error) => {
          this.showNotification('error', `Error: ${error.message}`);
        });
    },

    handleImageChange(event) {
      const file = event.target.files[0];

      // Check file size (2MB = 2048KB)
      if (file && file.size > 2 * 1024 * 1024) {
        this.imageError = 'Image size exceeds 2MB. Please upload a smaller image.';
        this.form.image = null; // Clear the image input
        this.form.imagePreview = null; // Clear existing image preview
        return;
      }

      this.imageError = null; // Clear error message
      this.form.image = file;

      // Preview the selected image
      const reader = new FileReader();
      reader.onload = () => {
        this.form.imagePreview = reader.result;
      };
      reader.readAsDataURL(file);
    },
  },
};
</script>

<style scoped>
/* General Layout */
.header-section {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  font-family: 'Arial', sans-serif;
  color: #333;
  background: #ffffff;
}

/* Notification Message */
.notification {
  padding: 15px;
  margin-bottom: 20px;
  border-radius: 5px;
  font-weight: bold;
  text-align: center;
}

.notification.success {
  background-color: #28a745;
  color: white;
}

.notification.error {
  background-color: #dc3545;
  color: white;
}

/* Form */
.header-form {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Form Inputs */
.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-size: 1rem;
  font-weight: bold;
  margin-bottom: 5px;
}

.form-input,
.form-input-file {
  width: 100%;
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
  transition: border 0.3s ease-in-out;
}

.form-input:focus {
  border-color: #007bff;
  outline: none;
}

/* Submit Button */
.form-button {
  display: inline-block;
  width: 100%;
  padding: 12px 15px;
  font-size: 1rem;
  color: #fff;
  background-color: #1E90FF;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
  text-transform: uppercase;
  transition: background 0.3s ease-in-out;
}

.form-button:hover {
  background-color: #187bcd;
}

/* Image Note */
.image-note {
  font-size: 0.9rem;
  color: #555;
  margin: 5px 0;
}

/* Error Message */
.error-message {
  color: red;
  font-size: 0.9rem;
  margin: 5px 0;
}

/* Existing Image */
.existing-image {
  max-width: 100px !important;
  height: 100px !important;
  margin-top: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
</style>
