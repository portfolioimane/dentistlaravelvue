<template>
  <div class="email-settings-container">
    <h2 class="settings-title">Email Settings</h2>

    <!-- Reminder Note -->
    <div class="reminder-note">
      <p><strong>Note:</strong> Don't forget to save your changes by clicking the "Save" button after making updates!</p>
    </div>

    <!-- Email Notification Settings Section -->
    <div class="settings-section">
      <h3 class="section-title">Email Settings</h3>
      <form @submit.prevent="updateEmail">
        <div class="form-group">
          <label for="emailEnabled" class="form-label">Enable Email</label>
          <input
            v-model="localEmailEnabled"
            type="checkbox"
            id="emailEnabled"
            class="checkbox-input"
            :checked="localEmailEnabled"
          />
        </div>

        <!-- Show Mail Fields only if Email Notifications are enabled -->
        <div v-if="localEmailEnabled">
          <div class="form-group">
            <label for="mailProvider" class="form-label">Mail Provider</label>
            <input
              v-model="localMailProvider"
              type="text"
              id="mailProvider"
              class="form-control"
              placeholder="Enter Mail Provider (e.g., Mailgun, SendGrid)"
            />
          </div>
          <div class="form-group">
            <label for="mailHost" class="form-label">Mail Host</label>
            <input
              v-model="localMailHost"
              type="text"
              id="mailHost"
              class="form-control"
              placeholder="Enter Mail Host"
            />
          </div>
          <div class="form-group">
            <label for="mailPort" class="form-label">Mail Port</label>
            <input
              v-model="localMailPort"
              type="number"
              id="mailPort"
              class="form-control"
              placeholder="Enter Mail Port"
            />
          </div>
          <div class="form-group">
            <label for="mailUsername" class="form-label">Mail Username</label>
            <input
              v-model="localMailUsername"
              type="text"
              id="mailUsername"
              class="form-control"
              placeholder="Enter Mail Username"
            />
          </div>
          <div class="form-group">
            <label for="mailPassword" class="form-label">Mail Password</label>
            <input
              v-model="localMailPassword"
              type="text"
              id="mailPassword"
              class="form-control"
              placeholder="Enter Mail Password"
            />
          </div>
          <div class="form-group">
            <label for="mailEncryption" class="form-label">Mail Encryption</label>
            <input
              v-model="localMailEncryption"
              type="text"
              id="mailEncryption"
              class="form-control"
              placeholder="Enter Mail Encryption (e.g., TLS)"
            />
          </div>
          <div class="form-group">
            <label for="mailFromAddress" class="form-label">From Address</label>
            <input
              v-model="localMailFromAddress"
              type="email"
              id="mailFromAddress"
              class="form-control"
              placeholder="Enter From Address"
            />
          </div>
          <div class="form-group">
            <label for="mailFromName" class="form-label">From Name</label>
            <input
              v-model="localMailFromName"
              type="text"
              id="mailFromName"
              class="form-control"
              placeholder="Enter From Name"
            />
          </div>
        </div>

        <div class="form-action">
          <button type="submit" class="btn btn-update">Save Email Settings</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Success/Error Modal -->
  <div v-if="message" class="modal-overlay" @click="closeModal">
    <div class="modal-content" @click.stop>
      <div :class="['modal-header', message.type]">
        <h3>{{ message.type === 'success' ? 'Success' : 'Error' }}</h3>
      </div>
      <div class="modal-body">
        <p>{{ message.text }}</p>
      </div>
      <div class="modal-footer">
        <button @click="closeModal" class="btn btn-closing">Close</button>
      </div>
    </div>
  </div>
</template>


<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  data() {
    return {
      message: null,
      localEmailEnabled: false,
      localMailProvider: '',
      localMailHost: '',
      localMailPort: '',
      localMailUsername: '',
      localMailPassword: '',
      localMailEncryption: '',
      localMailFromAddress: '',
      localMailFromName: '',
    };
  },
  computed: {
    ...mapGetters('emailSetting', [
      'isEmailEnabled',
      'mailProvider',
      'mailHost',
      'mailPort',
      'mailUsername',
      'mailPassword',
      'mailEncryption',
      'mailFromAddress',
      'mailFromName',
    ]),
  },
  methods: {
    ...mapActions('emailSetting', [
      'updateEmailSettings',
      'fetchEmailSettings',
    ]),

    async updateEmail() {
      try {
        await this.updateEmailSettings({
          enabled: this.localEmailEnabled,
          provider: this.localMailProvider,
          host: this.localMailHost,
          port: this.localMailPort,
          username: this.localMailUsername,
          password: this.localMailPassword,
          encryption: this.localMailEncryption,
          from_address: this.localMailFromAddress,
          from_name: this.localMailFromName,
        });
        this.showMessage('Email settings updated successfully!', 'success');
      } catch (error) {
        this.showMessage('Error updating email settings.', 'error');
      }
    },

    showMessage(text, type) {
      this.message = { text, type };
      setTimeout(() => {
        this.message = null;
      }, 5000);
    },

    closeModal() {
      this.message = null;
    },
  },
mounted() {
  // Fetch email settings when the component is mounted
  this.fetchEmailSettings()
    .then(() => {

          console.log('Computed Email Settings:', {
        isEmailEnabled: this.isEmailEnabled,
        mailProvider: this.mailProvider,
        mailHost: this.mailHost,
        mailPort: this.mailPort,
        mailUsername: this.mailUsername,
        mailPassword: this.mailPassword,
        mailEncryption: this.mailEncryption,
        mailFromAddress: this.mailFromAddress,
        mailFromName: this.mailFromName,
      });

      // Initialize the form fields with the fetched values
      this.localEmailEnabled = this.isEmailEnabled;
      this.localMailProvider = this.mailProvider || '';
      this.localMailHost = this.mailHost || '';
      this.localMailPort = this.mailPort || '';
      this.localMailUsername = this.mailUsername || '';
      this.localMailPassword = this.mailPassword || '';
      this.localMailEncryption = this.mailEncryption || '';
      this.localMailFromAddress = this.mailFromAddress || '';
      this.localMailFromName = this.mailFromName || '';

      // Log the email settings
      console.log('Email Settings:', {
        localEmailEnabled: this.localEmailEnabled,
        localMailProvider: this.localMailProvider,
        localMailHost: this.localMailHost,
        localMailPort: this.localMailPort,
        localMailUsername: this.localMailUsername,
        localMailPassword: this.localMailPassword,
        localMailEncryption: this.localMailEncryption,
        localMailFromAddress: this.localMailFromAddress,
        localMailFromName: this.localMailFromName,
      });
    })
    .catch((error) => {
      console.error('Error fetching email settings:', error);
    });
},

};
</script>

<style scoped>
/* Styling for the email settings container */
.email-settings-container {
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  max-width: 800px;
  margin: 0 auto;
}

.settings-title {
  text-align: center;
  color: #333;
  font-size: 24px;
  margin-bottom: 20px;
}

.settings-section {
  margin-bottom: 30px;
}

.section-title {
  font-size: 20px;
  color: #333;
  border-bottom: 2px solid #ddd;
  padding-bottom: 8px;
  margin-bottom: 15px;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  font-size: 16px;
  color: #333;
}

.form-control {
  width: 100%;
  padding: 12px;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-size: 14px;
  transition: border-color 0.3s ease;
}

.form-control:focus {
  border-color: #1E90FF;
}

.checkbox-input {
  width: auto;
  height: 20px;
  margin-right: 10px;
}

.btn-update {
  background-color: #1E90FF;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.btn-update:hover {
  background-color: #187bcd;
}

.reminder-note {
  margin-top: 20px;
  border-radius: 5px;
  text-align: center;
  font-size: 14px;
  color: #333;
  background-color: orange;
}

/* Modal Styling */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: 400px;
  width: 100%;
}

.modal-header {
  font-size: 18px;
  font-weight: bold;
}

.modal-header.success {
  color: #28a745;
}

.modal-header.error {
  color: #dc3545;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
}

.btn-closing {
  padding: 10px 20px;
  font-size: 16px;
  text-align: center;
  background: none;
  border: none;
  width: auto;
  background-color: #1F2A44 !important;
  color: #fff;
}

.btn-closing:hover {
  background-color: #0056b3;
}

</style>
