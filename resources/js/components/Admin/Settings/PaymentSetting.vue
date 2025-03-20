<template>
  <div class="payment-settings-container">
    <h2 class="settings-title">Payment Settings</h2>
    
    <!-- Reminder Note -->
    <div class="reminder-note">
      <p><strong>Note:</strong> Don't forget to save your changes by clicking the "Save" button after making updates!</p>
    </div>
    <!-- Stripe Settings Section -->
    <div class="settings-section">
      <h3 class="section-title">Stripe Settings</h3>
      <form @submit.prevent="updateStripeSettings">
        <div class="form-group">
          <label for="stripeEnabled" class="form-label">Enable Stripe</label>
          <input
            v-model="localStripeEnabled"
            type="checkbox"
            id="stripeEnabled"
            class="checkbox-input"
            :checked="localStripeEnabled"
          />
        </div>

        <!-- Show Stripe Fields only if Stripe is enabled -->
        <div v-if="localStripeEnabled">
               <!-- Add Mode Selection for Stripe -->
          <div class="form-group">
            <label for="stripeMode" class="form-label">Stripe Mode</label>
            <select v-model="localStripeMode" id="stripeMode" class="form-control">
              <option value="sandbox">Sandbox</option>
              <option value="live">Live</option>
            </select>
          </div>
          <div class="form-group">
            <label for="stripePublicKey" class="form-label">Stripe Public Key</label>
            <input
              v-model="localStripePublicKey"
              type="text"
              id="stripePublicKey"
              class="form-control"
              placeholder="Enter Stripe Public Key"
              required
            />
          </div>
          <div class="form-group">
            <label for="stripeSecretKey" class="form-label">Stripe Secret Key</label>
            <input
              v-model="localStripeSecretKey"
              type="text"
              id="stripeSecretKey"
              class="form-control"
              placeholder="Enter Stripe Secret Key"
              required
            />
          </div>
   
        </div>

        <div class="form-action">
          <button type="submit" class="btn btn-update">Save Stripe Settings</button>
        </div>
      </form>
    </div>

    <!-- PayPal Settings Section -->
    <div class="settings-section">
      <h3 class="section-title">PayPal Settings</h3>
      <form @submit.prevent="updatePaypalSettings">
        <div class="form-group">
          <label for="paypalEnabled" class="form-label">Enable PayPal</label>
          <input
            v-model="localPaypalEnabled"
            type="checkbox"
            id="paypalEnabled"
            class="checkbox-input"
            :checked="localPaypalEnabled"
          />
        </div>

        <!-- Show PayPal Fields only if PayPal is enabled -->
        <div v-if="localPaypalEnabled">
          <div class="form-group">
                    <!-- Add Mode Selection for PayPal -->
          <div class="form-group">
            <label for="paypalMode" class="form-label">PayPal Mode</label>
            <select v-model="localPaypalMode" id="paypalMode" class="form-control">
              <option value="sandbox">Sandbox</option>
              <option value="live">Live</option>
            </select>
          </div>
            <label for="paypalClientId" class="form-label">PayPal Client ID</label>
            <input
              v-model="localPaypalClientId"
              type="text"
              id="paypalClientId"
              class="form-control"
              placeholder="Enter PayPal Client ID"
              required
            />
          </div>
          <div class="form-group">
            <label for="paypalSecretKey" class="form-label">PayPal Secret Key</label>
            <input
              v-model="localPaypalSecretKey"
              type="text"
              id="paypalSecretKey"
              class="form-control"
              placeholder="Enter PayPal Secret Key"
              required
            />
          </div>

        </div>

        <div class="form-action">
          <button type="submit" class="btn btn-update">Save PayPal Settings</button>
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
      localStripeEnabled: false,
      localStripePublicKey: '',
      localStripeSecretKey: '',
      localStripeMode: 'sandbox',  // Default to sandbox
      localPaypalEnabled: false,
      localPaypalClientId: '',
      localPaypalSecretKey: '',
      localPaypalMode: 'sandbox',  // Default to sandbox
    };
  },
  computed: {
    ...mapGetters('paymentSetting', [
      'isStripeEnabled',
      'stripePublicKey',
      'stripeSecretKey',
      'stripeMode',
      'isPaypalEnabled',
      'paypalClientId',
      'paypalSecretKey',
      'paypalMode',
    ]),
  },
  methods: {
    ...mapActions('paymentSetting', [
      'updatePaymentSettings',
      'fetchPaymentSettings',
    ]),

    async updateStripeSettings() {
      try {
        await this.updatePaymentSettings({
          provider: 'stripe',
          enabled: this.localStripeEnabled,
          public_key: this.localStripePublicKey,
          secret_key: this.localStripeSecretKey,
          mode: this.localStripeMode,  // Send mode along with other data
        });
        this.showMessage('Stripe settings updated successfully!', 'success');
      } catch (error) {
        this.showMessage('Error updating Stripe settings.', 'error');
      }
    },

    async updatePaypalSettings() {
      try {
        await this.updatePaymentSettings({
          provider: 'paypal',
          enabled: this.localPaypalEnabled,
          public_key: this.localPaypalClientId,
          secret_key: this.localPaypalSecretKey,
          mode: this.localPaypalMode,  // Send mode along with other data
        });
        this.showMessage('PayPal settings updated successfully!', 'success');
      } catch (error) {
        this.showMessage('Error updating PayPal settings.', 'error');
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
    // Fetch payment settings when the component is mounted
    this.fetchPaymentSettings()
      .then(() => {
        // Initialize the form fields with the fetched values
        this.localStripeEnabled = this.isStripeEnabled;
        this.localStripePublicKey = this.stripePublicKey || '';
        this.localStripeSecretKey = this.stripeSecretKey || '';
        this.localStripeMode = this.stripeMode || 'sandbox';  // Set the fetched mode
        this.localPaypalEnabled = this.isPaypalEnabled;
        this.localPaypalClientId = this.paypalClientId || '';
        this.localPaypalSecretKey = this.paypalSecretKey || '';
        this.localPaypalMode = this.paypalMode || 'sandbox';  // Set the fetched mode
      })
      .catch((error) => {
        console.error('Error fetching payment settings:', error);
      });
  },
};
</script>


<style scoped>
/* Styling for the payment settings container */
.payment-settings-container {
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

/* Enhanced Styling for Select Dropdown */
select.form-control {
  padding: 12px;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 14px;
  transition: border-color 0.3s ease, background-color 0.3s ease;
}

select.form-control:focus {
  border-color: #1E90FF;
  background-color: #f0f8ff; /* Light blue background on focus */
  outline: none;
}

select.form-control option {
  padding: 10px;
}

/* Styling for the payment settings buttons */
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
