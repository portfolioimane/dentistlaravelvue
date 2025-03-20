<template>
  <div class="buisness-hours">
    <h1>Update Business Hours</h1>

    <!-- Success message -->
    <div v-if="successMessage" class="success-message">{{ successMessage }}</div>

    <form @submit.prevent="submitForm" class="buisness-hours-form">
      <div v-for="(day, index) in days" :key="index" class="form-group">
        <label :for="day">{{ formatDayName(day) }}:</label>
        
        <div class="time-fields">
          <input 
            type="time" 
            :name="day + '_open_time'" 
            v-model="buisnessHours[day].open_time" 
            :required="isRequired(day)"
            placeholder="HH:mm"
          />
          <input 
            type="time" 
            :name="day + '_close_time'" 
            v-model="buisnessHours[day].close_time" 
            :required="isRequired(day)"
            placeholder="HH:mm"
          />
        </div>
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Update Business Hours</button>
      </div>
    </form>
  </div>
</template>


<script>
export default {
  data() {
    return {
      days: ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
      buisnessHours: {
        monday: { open_time: '', close_time: '' },
        tuesday: { open_time: '', close_time: '' },
        wednesday: { open_time: '', close_time: '' },
        thursday: { open_time: '', close_time: '' },
        friday: { open_time: '', close_time: '' },
        saturday: { open_time: '', close_time: '' },
        sunday: { open_time: null, close_time: null }, // Sunday is closed or not defined
      },
      successMessage: '', // Property for success message
    };
  },
  computed: {
    // Access the business hours state from Vuex or initialize
    buisnessHoursState() {
      return this.$store.getters['buisnessHours/buisnessHours'] || this.buisnessHours;
    },
  },
  methods: {
    async fetchBuisnessHours() {
      try {
        await this.$store.dispatch('buisnessHours/fetchBuisnessHours');
        
        // Format the fetched business hours to match the input format (HH:mm)
        const formattedHours = {};
        this.buisnessHoursState.forEach((item) => {
          formattedHours[item.day] = {
            open_time: this.formatTime(item.open_time),
            close_time: this.formatTime(item.close_time),
          };
        });

        // Update the business hours object with the formatted data
        this.buisnessHours = { ...formattedHours };
      } catch (error) {
        console.error('Error fetching business hours:', error);
      }
    },

    // Format time to HH:mm, or return null if it's invalid
    formatTime(time) {
      if (time === null) return null; // If time is null, return null
      const timeParts = time.split(':');
      return `${timeParts[0]}:${timeParts[1]}`; // Return HH:mm format
    },

    async submitForm() {
      try {
        // Send updated business hours data to the backend
        await this.$store.dispatch('buisnessHours/updateBuisnessHours', this.buisnessHours);
        
        // Display the success message
        this.successMessage = 'Business hours updated successfully!';
        
        // Clear the success message after a few seconds
        setTimeout(() => {
          this.successMessage = '';
        }, 5000);
      } catch (error) {
        console.error('Error updating business hours:', error);
      }
    },

    formatDayName(day) {
      const dayNames = {
        monday: 'Monday',
        tuesday: 'Tuesday',
        wednesday: 'Wednesday',
        thursday: 'Thursday',
        friday: 'Friday',
        saturday: 'Saturday',
        sunday: 'Sunday',
      };
      return dayNames[day];
    },

    isRequired(day) {
      // Check if a day has open and close times set; if not, it's considered closed
      return this.buisnessHours[day].open_time && this.buisnessHours[day].close_time;
    },
  },
  created() {
    this.fetchBuisnessHours();
  },
};
</script>


<style scoped>
.buisness-hours {
  margin: 1.5rem;
  max-width: 600px;
  font-family: Arial, sans-serif;
}

h1 {
  color: #1E90FF;
  font-size: 1.6rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-align: center;
}

.buisness-hours-form {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 1rem;
}

label {
  font-weight: bold;
  color: #555;
}

.time-fields {
  display: flex;
  gap: 10px;
}

.time-fields input {
  padding: 0.5rem;
  font-size: 1rem;
  width: 150px; /* Increased width for better visibility of AM/PM */
  text-align: center;
}

.button-group {
  margin-top: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  background-color: #1E90FF;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
}

.btn:hover {
  background-color: #333;
}

.success-message {
  color: green;
  font-size: 1rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-align: center;
}
</style>
