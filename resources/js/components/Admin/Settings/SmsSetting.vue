<template>
  <div class="sms-settings-container">
    <h2 class="settings-title">SMS Settings</h2>

    <!-- Reminder Note -->
    <div class="reminder-note">
      <p><strong>Note:</strong> Don't forget to save your changes by clicking the "Save" button after making updates!</p>
    </div>

    <!-- SMS Notification Settings Section -->
    <div class="settings-section">
      <h3 class="section-title">SMS Settings</h3>
      <form @submit.prevent="updateSms">
        <div class="form-group">
          <label for="smsEnabled" class="form-label">Enable SMS</label>
          <input
            v-model="localSmsEnabled"
            type="checkbox"
            id="smsEnabled"
            class="checkbox-input"
            :checked="localSmsEnabled"
          />
        </div>

        <!-- Show SMS Fields only if SMS Notifications are enabled -->
        <div v-if="localSmsEnabled">
          <div class="form-group">
            <label for="smsProvider" class="form-label">SMS Provider</label>
            <input
              v-model="localSmsProvider"
              type="text"
              id="smsProvider"
              class="form-control"
              placeholder="Enter SMS Provider (e.g., Twilio)"
            />
          </div>
          <div class="form-group">
            <label for="smsSid" class="form-label">Twilio SID</label>
            <input
              v-model="localSmsSid"
              type="text"
              id="smsSid"
              class="form-control"
              placeholder="Enter Twilio SID"
            />
          </div>
          <div class="form-group">
            <label for="smsAuthToken" class="form-label">Twilio Auth Token</label>
            <input
              v-model="localSmsAuthToken"
              type="text"
              id="smsAuthToken"
              class="form-control"
              placeholder="Enter Twilio Auth Token"
            />
          </div>
          <div class="form-group">
            <label for="smsPhoneNumber" class="form-label">Twilio Phone Number</label>
            <input
              v-model="localSmsPhoneNumber"
              type="text"
              id="smsPhoneNumber"
              class="form-control"
              placeholder="Enter Twilio Phone Number"
            />
          </div>
        </div>

        <div class="form-action">
          <button type="submit" class="btn btn-update">Save SMS Settings</button>
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
      localSmsEnabled: false,
      localSmsProvider: 'twilio',
      localSmsSid: '',
      localSmsAuthToken: '',
      localSmsPhoneNumber: '',
    };
  },
  computed: {
    ...mapGetters('smsSetting', [
      'isSmsEnabled',
      'smsProvider',
      'smsSid',
      'smsAuthToken',
      'smsPhoneNumber',
    ]),
  },
  methods: {
    ...mapActions('smsSetting', [
      'updateSmsSettings',
      'fetchSmsSettings',
    ]),

    async updateSms() {
      try {
        await this.updateSmsSettings({
          enabled: this.localSmsEnabled,
          provider: this.localSmsProvider,
          sid: this.localSmsSid,
          auth_token: this.localSmsAuthToken,
          phone_number: this.localSmsPhoneNumber,
        });
        this.showMessage('SMS settings updated successfully!', 'success');
      } catch (error) {
        this.showMessage('Error updating SMS settings.', 'error');
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
    this.fetchSmsSettings()
      .then(() => {
        this.localSmsEnabled = this.isSmsEnabled;
        this.localSmsProvider = this.smsProvider || 'twilio';
        this.localSmsSid = this.smsSid || '';
        this.localSmsAuthToken = this.smsAuthToken || '';
        this.localSmsPhoneNumber = this.smsPhoneNumber || '';
      })
      .catch((error) => {
        console.error('Error fetching SMS settings:', error);
      });
  },
};
</script>

<style scoped>
/* Styling for the SMS settings container */
.sms-settings-container {
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
