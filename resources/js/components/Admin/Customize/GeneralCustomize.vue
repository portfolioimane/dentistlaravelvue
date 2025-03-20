<template>
  <div class="general-customize">
    <h2 class="page-title">Customize Website</h2>

    <div class="form-group">
      <label for="logo">Logo</label>
      <input type="file" @change="onLogoChange" />
      <div><img v-if="computedLogo" :src="computedLogo" alt="Logo" class="logo-preview" /></div>
      <button class="update-btn" @click="updateLogo">Update</button>
      <p v-if="logoMessage" class="message">{{ logoMessage }}</p>
    </div>

    <div class="form-group">
      <label for="footer">Footer Description</label>
      <input v-model="localFooter" type="text" />
    </div>

    <div class="form-group">
      <label for="address">Address</label>
      <input v-model="localAddress" type="text" />
    </div>

    <div class="form-group">
      <label for="phone">Phone</label>
      <input v-model="localPhone" type="text" />
    </div>

    <div class="form-group">
      <label for="gmail">Gmail</label>
      <input v-model="localGmail" type="text" />
    </div>

    <div class="form-group">
      <label for="facebook">Facebook</label>
      <input v-model="localFacebook" type="text" />
    </div>

    <div class="form-group">
      <label for="instagram">Instagram</label>
      <input v-model="localInstagram" type="text" />
    </div>

    <div class="form-group">
      <label for="tiktok">TikTok</label>
      <input v-model="localTiktok" type="text" />
    </div>

    <button class="update-btn" @click="updateGeneralCustomize">Update</button>
    <p v-if="generalMessage" class="message">{{ generalMessage }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      // Store values locally to make them reactive
      localFooter: '',
      localAddress: '',
      localPhone: '',
      localGmail: '',
      localFacebook: '',
      localInstagram: '',
      localTiktok: '',
      logoFile: null,
      logoMessage: '',
      generalMessage: '',
    };
  },
  computed: {
    computedLogo() {
      return this.$store.getters['backendGeneralCustomize/getGeneralCustomize']('logo');
    },
    computedFooter() {
      return this.$store.getters['backendGeneralCustomize/getGeneralCustomize']('footer');
    },
    computedAddress() {
      return this.$store.getters['backendGeneralCustomize/getGeneralCustomize']('address');
    },
    computedPhone() {
      return this.$store.getters['backendGeneralCustomize/getGeneralCustomize']('phone');
    },
    computedGmail() {
      return this.$store.getters['backendGeneralCustomize/getGeneralCustomize']('gmail');
    },
    computedFacebook() {
      return this.$store.getters['backendGeneralCustomize/getGeneralCustomize']('facebook');
    },
    computedInstagram() {
      return this.$store.getters['backendGeneralCustomize/getGeneralCustomize']('instagram');
    },
    computedTiktok() {
      return this.$store.getters['backendGeneralCustomize/getGeneralCustomize']('tiktok');
    },
  },
  methods: {
    onLogoChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.logoFile = file;
      }
    },
    updateLogo() {
      if (this.logoFile) {
        this.$store.dispatch('backendGeneralCustomize/updateLogo', this.logoFile)
          .then(() => {
            this.logoMessage = 'Logo updated successfully!';
            this.hideMessage('logoMessage');
          })
          .catch(() => {
            this.logoMessage = 'Failed to update the logo.';
            this.hideMessage('logoMessage');
          });
      } else {
        this.logoMessage = 'Please select a logo file.';
        this.hideMessage('logoMessage');
      }
    },
    updateGeneralCustomize() {
      const updatedFields = {};

      if (this.localFooter !== this.computedFooter) {
        updatedFields.footer = this.localFooter;
      }
      if (this.localAddress !== this.computedAddress) {
        updatedFields.address = this.localAddress;
      }
      if (this.localPhone !== this.computedPhone) {
        updatedFields.phone = this.localPhone;
      }
      if (this.localGmail !== this.computedGmail) {
        updatedFields.gmail = this.localGmail;
      }
      if (this.localFacebook !== this.computedFacebook) {
        updatedFields.facebook = this.localFacebook;
      }
      if (this.localInstagram !== this.computedInstagram) {
        updatedFields.instagram = this.localInstagram;
      }
      if (this.localTiktok !== this.computedTiktok) {
        updatedFields.tiktok = this.localTiktok;
      }

      if (Object.keys(updatedFields).length > 0) {
        this.$store.dispatch('backendGeneralCustomize/updateGeneralCustomize', updatedFields)
          .then(() => {
            this.generalMessage = 'General customization updated successfully!';
            this.hideMessage('generalMessage');
          })
          .catch(() => {
            this.generalMessage = 'Failed to update general customization.';
            this.hideMessage('generalMessage');
          });
      } else {
        this.generalMessage = 'No changes detected.';
        this.hideMessage('generalMessage');
      }
    },
    hideMessage(messageType) {
      setTimeout(() => {
        this[messageType] = '';
      }, 3000); // Hide the message after 3 seconds
    },
  },
  created() {
    this.$store.dispatch('backendGeneralCustomize/fetchGeneralCustomizes')
      .then(() => {
        this.localFooter = this.computedFooter;
        this.localAddress = this.computedAddress;
        this.localPhone = this.computedPhone;
        this.localGmail = this.computedGmail;
        this.localFacebook = this.computedFacebook;
        this.localInstagram = this.computedInstagram;
        this.localTiktok = this.computedTiktok;
      });
  },
};
</script>


<style scoped>
/* General Styles */
.general-customize {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  margin: 0 auto;
}

.page-title {
  font-size: 24px;
  font-weight: 600;
  color: #333;
  margin-bottom: 20px;
  text-align: center;
}

.form-group {
  margin-bottom: 15px;
}

label {
  font-weight: 500;
  color: #444;
  display: block;
  margin-bottom: 8px;
}

input {
  width: 100%;
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 6px;
  box-sizing: border-box;
}

input[type="file"] {
  padding: 10px;
}

input:focus {
  border-color: #1E90FF;
  outline: none;
}

input[type="file"]:focus {
  border-color: #1E90FF;
}

.logo-preview {
  width:50px !important;
  height:50px !important;
  border-radius: 6px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.update-btn {
  background-color: #1E90FF; /* Gold color */
  color: white;
  font-size: 18px;
  font-weight: 600;
  padding: 12px 25px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
  width: 20%;
  margin-top: 20px;
}

.update-btn:hover {
  background-color: #187bcd; /* Darker gold on hover */
  transform: scale(1.05);
}

.update-btn:active {
  background-color: #b37e1e; /* Even darker gold on click */
}

.message {
  font-size: 14px;
  color: #fff;
  background-color:green;
  margin-top: 10px;
  text-align: center;
}
</style>
