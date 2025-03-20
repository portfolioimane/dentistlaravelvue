<template>
   <div class="container mt-5 checkout-container">
    <h2 class="text-center mb-4">Checkout</h2>

    <div class="row">
      <div class="col-md-6">
        <h4 class="mb-4">Customer Information</h4>
        <form @submit.prevent="submitBooking">
          <div v-if="errorMessage" class="alert alert-danger">{{ errorMessage }}</div>
          <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>

          <!-- Customer information form fields -->
      <div class="mb-3">
  <label for="date">Date</label>
  <input 
    type="date" 
    v-model="form.date" 
    class="form-control" 
    :min="todayDate" 
    @change="fetchAvailableSlots" 
    required 
  />
</div>

          <div class="mb-3">
            <label for="time_slot">Available Time Slots</label>
            <select 
              v-model="form.selectedSlot" 
              class="form-control" 
              required
            >
              <option value="" disabled>Select a time slot</option>
              <option v-for="slot in availableSlots" :key="slot.start_time" :value="slot.start_time">
                {{ slot.start_time }} - {{ slot.end_time }}
              </option>
            </select>
          </div>

          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" v-model="form.name" class="form-control" required />
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" v-model="form.email" class="form-control" required />
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" id="phone" v-model="form.phone" class="form-control" required />
          </div>

          <!-- Payment method selection -->
          <h4 class="mb-4">Select Payment Method</h4>
          <div class="form-check">
            <input
              type="radio"
              id="cod"
              v-model="form.payment_method"
              value="pay_on_site"
              class="form-check-input"
              required
            />
            <label class="form-check-label" for="cod">Cash</label>
          </div>
          <div class="form-check" v-if="isStripeEnabled">
            <input type="radio" id="stripe" v-model="form.payment_method" value="stripe" class="form-check-input" />
            <label class="form-check-label" for="stripe">
              <i class="fas fa-credit-card"></i> Credit Card
            </label>
          </div>
          <div v-if="form.payment_method === 'stripe'" class="mb-3">
            <div id="cardElement" class="form-control"></div>
          </div>
          <div class="form-check" v-if="isPaypalEnabled">
            <input
              type="radio"
              id="paypal"
              v-model="form.payment_method"
              value="paypal"
              class="form-check-input"
            />
            <label class="form-check-label" for="paypal">PayPal</label>
          </div>

          <!-- PayPal Button -->
          <div v-show="form.payment_method === 'paypal'" class="paypal-button-container">
            <div id="paypal-button-container" v-if="isFormValid"></div>
            <div v-else class="alert alert-warning">Please complete all fields before proceeding with PayPal.</div>
          </div>

          <!-- Submit button with loading state, but only show it if PayPal is NOT selected -->
          <button
            v-if="form.payment_method !== 'paypal'"
            type="submit"
            class="btn btn-golden mt-3 w-100"
            :disabled="!isFormValid || loading"
          >
            <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Book Now
          </button>
        </form>
      </div>

      <div class="col-md-6">
  <h4 class="mb-4 text-center">Service Summary</h4>

  <ul class="list-group">
    <li v-if="selectedService" class="list-group-item">
      <div class="service-image-container text-center mb-4">
        <img v-if="selectedService" :src="selectedService.image" alt="Service Image" class="img-fluid service-image" />
      </div>
      <span>{{ selectedService.title }}</span>
      <span class="float-end">{{ selectedService.cost }} DH</span>
    </li>
  </ul>

  <h5 class="text-start mt-4">Date: <span>{{ form.date }}</span></h5>
  
  <!-- Show selected time slot -->
  <h5 class="text-start">Time Slot: 
    <span>
      {{ form.selectedSlot }} - 
      {{ availableSlots.find(slot => slot.start_time === form.selectedSlot)?.end_time }}
    </span>
  </h5>

  <!-- Display Booking Fee -->
  <h5 class="text-start mt-3">
    Booking Fee (50 DH): <span class="text-danger">50 DH</span>
  </h5>

  <!-- Explanation of Booking Fee -->
<p class="text-center text-muted">
  <strong>Online Payment (50 DH):</strong> Your slot is reserved, and you'll receive a reminder one day before. The fee is deducted from the total cost.  <br><br>
  <strong>Pay On-Site:</strong> Your booking is pending. Call at least one day before to confirm.
</p>




</div>

    </div>
  </div>
</template>


<script>
import { mapActions, mapGetters } from "vuex";
import { useRoute } from "vue-router";
import { loadStripe } from "@stripe/stripe-js";


export default {
  data() {
    return {
      form: {
        date: '',
        start_time: '',
        selectedSlot: '', 
        end_time: '',
        name: '',
        email: '',
        phone: '',
        payment_method: 'cash_on_delivery',
        todayDate: '', // Store today's date
      },
      errorMessage: null,
      successMessage: null,
      loading: false,
    };
  },
  computed: {
      ...mapGetters('booking', ['availableSlots']),

    ...mapGetters("keys", ["getStripePublicKey", "getPaypalPublicKey"]), // Namespaced getter

    ...mapGetters("services", ["selectedService"]),
    isFormValid() {
      return this.form.name && this.form.email && this.form.payment_method;
    },
    isStripeEnabled() {
      return this.$store.getters["keys/isStripeEnabled"];
    },
    isPaypalEnabled() {
      return this.$store.getters["keys/isPaypalEnabled"];
    },
    user() {
      return this.$store.getters['auth/user']; // Get logged-in user data
    },
  },
    created() {
    this.setTodayDate();
  },
  methods: {
      ...mapActions('booking', ['fetchAvailableSlots']),

    ...mapActions("keys", ["fetchStripePublicKey", "fetchPaypalPublicKey"]), // Namespaced action

    ...mapActions("services", ["fetchServiceById"]),

 setTodayDate() {
      const today = new Date();
      // Set today's date in YYYY-MM-DD format
      this.todayDate = today.toISOString().split('T')[0];
    },

getBookingData() {
  const selectedTime = this.availableSlots.find(slot => slot.start_time === this.form.selectedSlot);
  
  if (selectedTime) {
    this.start_time = selectedTime.start_time;
    this.end_time = selectedTime.end_time;
  }

  return {
    date: this.form.date,
    name: this.form.name || this.user.name,
    email: this.form.email || this.user.email,
    phone: this.form.phone || this.user.phone,
    payment_method: this.form.payment_method,
    total: this.selectedService.cost,
    service_id: this.$route.params.serviceId,
    start_time: this.start_time,
    end_time: this.end_time
  };
},


  fetchAvailableSlots() {
  console.log('fetchAvailableSlots triggered');
  this.$store.dispatch('booking/fetchAvailableSlots', {
    date: this.form.date,
    service_id: this.$route.params.serviceId,
  });
},


async submitBooking() {
    if (!this.isFormValid) {
        this.errorMessage = "Please complete all fields and ensure your cart is not empty.";
        return;
    }

    // Set loading state to true
    this.loading = true;
    const bookingData = this.getBookingData(); // Use the new method

    try {
        if (this.form.payment_method === 'stripe') {
        const bookingFeeInMAD = 50; // The booking fee in MAD
const conversionRate = 0.1; // Conversion rate from MAD to USD

// Convert the booking fee to USD
const bookingFeeInUSD = bookingFeeInMAD * conversionRate;

// Dispatch the Stripe payment creation action with the booking fee
const { clientSecret } = await this.$store.dispatch('booking/createStripePayment', bookingFeeInUSD);


            if (!clientSecret) throw new Error("Payment initialization failed.");

            const { error, paymentIntent } = await this.stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: this.cardElement,
                    billing_details: {
                        name: this.form.name,
                        email: this.form.email,
                    },
                },
            });

            if (error) throw error;

            if (paymentIntent.status === 'succeeded') {
                await this.submitBookingAfterPayment(bookingData); // Call the reusable order submission method
            } else {
                throw new Error("Payment not successful.");
            }
        } else {
            await this.submitBookingAfterPayment(bookingData); // Call the reusable order submission method
        }

    } catch (error) {
        this.errorMessage = error.message || "Failed to place order.";
    } finally {
        this.loading = false;
    }
},



       async submitBookingAfterPayment(bookingData) {
    try {
      const response = await this.$store.dispatch('booking/submitBooking', bookingData);
      const bookingId = response.id;


   const serviceId = this.$route.params.serviceId;
      this.form = {
       date:'',
        start_time: '', 
        end_time: '',
        name: '',
        email: '',
        phone: '',
        payment_method: 'cash_on_delivery',
      };
      this.$router.push('/customerdashboard/mybookings');

   

    } catch (error) {
      console.error('Error submitting order:', error);
    }
  },

      fillUserData() {
      if (this.user) {
        this.form.name = this.user.name || '';
        this.form.email = this.user.email || '';
        this.form.phone = this.user.phone || '';
      }
    },
    async initializeStripe() {
      // If a Stripe element already exists, destroy it before reinitializing
      if (this.cardElement) {
        this.cardElement.unmount();
        this.cardElement = null;
      }
      await this.fetchStripePublicKey();
      const stripePublicKey = this.getStripePublicKey;
       console.log('stripekey',stripePublicKey);
      if (!stripePublicKey) {
        console.error("Stripe public key is not available");
        return;
      }

      const stripePromise = loadStripe(stripePublicKey);
      this.stripe = await stripePromise;
      this.elements = this.stripe.elements();
      this.cardElement = this.elements.create('card');
      this.cardElement.mount('#cardElement');
    },


loadPayPalScript() {
  if (!this.paypalLoaded) {
    // Fetch PayPal public key from Vuex store asynchronously
    this.fetchPaypalPublicKey().then(() => {
      const clientId = this.getPaypalPublicKey;
      if (!clientId) {
        console.error('PayPal public key is not available');
        return;
      }

      const script = document.createElement('script');
      script.src = `https://www.paypal.com/sdk/js?client-id=${clientId}&currency=USD`;
      script.async = true;
      script.onload = () => {
        this.paypalLoaded = true;
        this.renderPayPalButton();
      };
      document.body.appendChild(script);
    }).catch(error => {
      console.error('Error fetching PayPal public key:', error);
    });
  } else {
    this.renderPayPalButton();
  }
},



renderPayPalButton() {

  
  // If validation passes, render the PayPal button
  const interval = setInterval(() => {
    const container = document.getElementById('paypal-button-container');
    if (container) {
      clearInterval(interval);
      container.innerHTML = ''; // Clear previous button rendering

      window.paypal.Buttons({
createOrder: (data, actions) => {
  // Example conversion rate (1 MAD = 0.1 USD)
  const conversionRate = 0.1;
  
  // The booking fee in MAD (50 DH)
  const bookingFeeInMAD = 50;  
  
  // Convert the booking fee from MAD to USD
  const bookingFeeInUSD = bookingFeeInMAD * conversionRate;

  // Create the order in PayPal with the converted USD booking fee amount
  return actions.order.create({
    purchase_units: [
      {
        amount: {
          value: bookingFeeInUSD.toFixed(2), // Send the booking fee in USD with two decimals
        },
      },
    ],
  });
},


        onApprove: (data, actions) => {
          return actions.order.capture().then(async (details) => {
            const paypalOrderId = data.orderID;
            try {
              const response = await this.$store.dispatch('booking/confirmPayPalPayment', paypalOrderId);
              if (response.status === 'COMPLETED') {
                const bookingData = this.getBookingData();
                await this.submitBookingAfterPayment(bookingData);
              } else {
                this.errorMessage = 'Payment not completed.';
              }
            } catch (error) {
              this.errorMessage = 'Failed to confirm PayPal payment.';
            }
          });
        },
        onError: (err) => {
          this.errorMessage = 'An error occurred with PayPal.';
        },
      }).render('#paypal-button-container');
    }
  }, 100);
},



  },
watch: {
  'form.payment_method': function (newValue) {
    if (newValue === 'stripe') {
      this.initializeStripe();
    } else if (newValue === 'paypal') {
      this.loadPayPalScript();
    }
  },
   isFormValid(newValue) {
      // Watch for changes in form validity and show/hide PayPal button
      if (newValue && this.form.payment_method === 'paypal') {
        this.loadPayPalScript();
      }
    },

        $route(to, from) {
      const serviceId = to.params.serviceId;
      if (serviceId) {
        this.fetchServiceById(serviceId); // Fetch service based on the new serviceId
      }
    },
},

  mounted() {
   const serviceId = this.$route.params.serviceId;
    if (serviceId) {
      this.fetchServiceById(serviceId); // Fetch service on component mount
    }
    if (this.form.payment_method === 'stripe') {
      this.initializeStripe();
    }
    if (this.form.payment_method === 'paypal') {
        this.loadPayPalScript();
    }
        this.fetchStripePublicKey();
        this.fetchPaypalPublicKey();

    this.$store.dispatch('auth/checkAuth').then(() => {
      this.fillUserData();
    });
  },
};
</script>
<style scoped>
.checkout-container {
  background-color: #f8f9fa;
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.service-image {
  width: 100px;
  height:100px;
  height: auto;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.checkout-container h2 {
  font-size: 28px;
  color: #333;
  font-weight: 600;
}

.checkout-container h4 {
  font-size: 20px;
  color: #495057;
  font-weight: 500;
}

.checkout-container .form-label {
  font-size: 14px;
  color: #6c757d;
}

.checkout-container .form-control {
  border-radius: 4px;
  border: 1px solid #ced4da;
  padding: 10px;
  font-size: 16px;
  width: 100%;
}

.checkout-container .form-control:focus {
  border-color: #495057;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.checkout-container .alert {
  margin-bottom: 20px;
}

.checkout-container .form-check {
  margin-bottom: 15px;
}

.checkout-container .form-check-label {
  font-size: 16px;
  color: #495057;
}

.checkout-container .paypal-button-container {
  margin-top: 20px;
}

.checkout-container .btn-golden {
  background-color: #f39c12;
  color: white;
  border: none;
  padding: 12px;
  font-size: 18px;
  font-weight: 600;
  border-radius: 6px;
}

.checkout-container .btn-golden:hover {
  background-color: #e67e22;
}

.checkout-container .btn-golden:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.checkout-container .col-md-6 {
  padding: 20px;
}

.checkout-container .col-md-6 h4 {
  font-size: 22px;
  font-weight: 600;
  color: #333;
}

.checkout-container .list-group-item {
  display: flex;
  justify-content: space-between;
  font-size: 16px;
  padding: 12px;
  border: 1px solid #e9ecef;
  margin-bottom: 10px;
}

.checkout-container .list-group-item span {
  font-weight: 600;
}

.checkout-container .service-summary {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.checkout-container .service-summary img {
  width: 100%;
  height: auto;
  border-radius: 8px;
  margin-bottom: 20px;
}

.checkout-container .service-summary .total-cost {
  font-size: 20px;
  font-weight: 600;
  color: #333;
  text-align: right;
}

.checkout-container .service-summary .total-cost span {
  color: #f39c12;
}

@media (max-width: 768px) {
  .checkout-container {
    padding: 30px 15px;
  }

  .checkout-container .col-md-6 {
    padding: 10px;
  }

  .checkout-container .service-summary {
    padding: 15px;
  }

  .checkout-container h2 {
    font-size: 24px;
  }

  .checkout-container h4 {
    font-size: 18px;
  }

  .checkout-container .btn-golden {
    font-size: 16px;
    padding: 10px;
  }
}
</style>
