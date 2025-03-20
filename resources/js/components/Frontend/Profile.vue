<template>
  <div v-if="isLoading" class="loading-state">
    <p>Loading...</p>
  </div>
  <div v-else class="profile-container">
    <h2 class="profile-heading">User Profile</h2>
    
    <!-- Avatar Section -->
    <div class="avatar-section">
      <div class="avatar-wrapper">
        <img 
          v-if="form.avatarPreview" 
          :src="form.avatarPreview" 
          alt="User Avatar" 
          class="avatar-image" 
        />
        <div v-else class="avatar-placeholder">
          <span>{{ form.name ? form.name[0] : 'U' }}</span>
        </div>
      </div>
      <input type="file" @change="uploadAvatar" class="avatar-upload" />
    </div>

    <!-- Profile Form -->
    <form @submit.prevent="updateUserProfile" class="profile-form" enctype="multipart/form-data">
      <div class="form-group">
        <label for="name">Name:</label>
        <input 
          type="text" 
          id="name" 
          v-model="form.name" 
          placeholder="Enter your name" 
          class="form-input" 
        />
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input 
          type="email" 
          id="email" 
          v-model="form.email" 
          placeholder="Enter your email" 
          class="form-input" 
        />
      </div>

      <div class="form-group">
        <label for="phone">Phone:</label>
        <input 
          type="text" 
          id="phone" 
          v-model="form.phone" 
          placeholder="Enter your phone number" 
          class="form-input" 
        />
      </div>

      <!-- Password Fields -->
      <div class="form-group">
        <label for="password">New Password:</label>
        <input 
          type="password" 
          id="password" 
          v-model="form.password" 
          placeholder="Enter new password" 
          class="form-input" 
        />
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirm New Password:</label>
        <input 
          type="password" 
          id="password_confirmation" 
          v-model="form.password_confirmation" 
          placeholder="Confirm new password" 
          class="form-input" 
        />
      </div>

      <div class="form-actions">
        <button 
          type="submit" 
          :disabled="!isFormValid" 
          class="submit-btn"
        >
          Update Profile
        </button>
      </div>
    </form>

    <!-- Success/Error Messages -->
    <div v-if="message" :class="['message', message.type]">
      <p>{{ message.text }}</p>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data() {
    return {
      form: {
        name: "",
        email: "",
        phone: "",
        avatar: null,
        avatarPreview: "",
        password: "",
        password_confirmation: "",
      },
      isLoading: true,
      message: null, // Stores success or error messages
    };
  },
  computed: {
    ...mapGetters("auth", ["user", "authChecked"]),
    isFormValid() {
      return (
        this.form.name && 
        this.form.email && 
        (this.form.password === "" || this.form.password === this.form.password_confirmation)
      ); 
    }
  },
  watch: {
    user(newUser) {
      if (newUser) {
        this.form = {
          name: newUser.name || "",
          email: newUser.email || "",
          phone: newUser.phone || "",
          avatarPreview: newUser.avatar ? `/${newUser.avatar}` : "", 
        };
      }
      this.isLoading = false; 
    },
  },
  methods: {
    ...mapActions("auth", ["checkAuth", "updateUser"]),
async updateUserProfile() {
  const formData = new FormData();
  formData.append("name", this.form.name);
  formData.append("email", this.form.email);
  formData.append("phone", this.form.phone);

  if (this.form.avatar) {
    formData.append("avatar", this.form.avatar);
  }

  if (this.form.password) {
    formData.append("password", this.form.password);
    formData.append("password_confirmation", this.form.password_confirmation);
  }
  formData.append("_method", "PUT");

  try {
    await this.updateUser(formData);
    await this.$store.dispatch('auth/checkAuth');

    this.message = {
      type: 'success',
      text: 'Your profile has been updated successfully.',
    };

    // Clear the message after 3 seconds
    setTimeout(() => {
      this.message = null;
    }, 3000); // 3000 milliseconds (3 seconds)
  } catch (error) {
    console.error("Error updating profile:", error);
    this.message = {
      type: 'error',
      text: error.message || 'Failed to update profile.',
    };

    // Clear the message after 3 seconds
    setTimeout(() => {
      this.message = null;
    }, 5000); // 3000 milliseconds (5 seconds)
  }
},

    uploadAvatar(event) {
      const file = event.target.files[0];
      if (file) {
        if (!file.type.startsWith('image/')) {
          this.message = {
            type: 'error',
            text: 'Please upload a valid image file.',
          };
          return;
        }

        this.form.avatar = file;
        const reader = new FileReader();
        reader.onload = (e) => {
          this.form.avatarPreview = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    }
  },
  async mounted() {
    await this.checkAuth();
  }
};
</script>

<style scoped>
.profile-container {
  width: 100%;
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  background-color: #f8f8f8;
  border-radius: 8px;
}

.profile-heading {
  text-align: center;
  font-size: 2rem;
  color: #333;
  margin-bottom: 20px;
}

.avatar-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 30px;
}

.avatar-wrapper {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  overflow: hidden;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #e0e0e0;
  margin-bottom: 10px;
}

.avatar-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-placeholder {
  font-size: 40px;
  font-weight: bold;
  color: #fff;
  background-color: var(--primary-color);
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
}

.avatar-upload {
  margin-top: 10px;
  padding: 6px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.profile-form .form-group {
  margin-bottom: 15px;
}

.profile-form .form-group label {
  font-weight: bold;
  display: block;
  margin-bottom: 5px;
}

.profile-form .form-group input {
  width: 100%;
  padding: 8px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.profile-form .form-actions {
  display: flex;
  justify-content: center;
}

.submit-btn {
  background-color: var(--primary-color);
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}

.submit-btn:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.loading-state {
  text-align: center;
  font-size: 18px;
  color: #666;
}

.message {
  margin-top: 20px;
  padding: 10px;
  text-align: center;
  border-radius: 4px;
  font-weight: bold;
}

.message.success {
  background-color: #4CAF50;
  color: white;
}

.message.error {
  background-color: #f44336;
  color: white;
}
</style>
