<template>
  <div class="contact-messages">
    <h2>Contact Messages</h2>
    <div v-if="messages.length === 0">
      <p>No contact messages available.</p>
    </div>
    <table v-else class="messages-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Message</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="message in messages" :key="message.id">
          <td>{{ message.name }}</td>
          <td>{{ message.email }}</td>
          <td>
            <div>
              <!-- Truncated message with Read More functionality -->
              <p class="message-text" :class="{'expanded': message.isExpanded}">
                {{ message.isExpanded ? message.message : truncateMessage(message.message) }}
              </p>
              <button v-if="message.message.length > 25" @click="toggleMessage(message)">
                {{ message.isExpanded ? 'Show Less' : 'Read More' }}
              </button>
            </div>
          </td>
          <td>{{ formatDate(message.created_at) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: 'ContactMessages',
  computed: {
    // Accessing messages from Vuex store
    messages() {
      return this.$store.getters['backendContact/getContactMessages'];
    }
  },
  mounted() {
    // Fetch messages when the component is mounted
    this.$store.dispatch('backendContact/fetchMessages');
  },
  methods: {
    // Method to format date in a more readable way
    formatDate(dateString) {
      const options = {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
      };

      const date = new Date(dateString);
      return new Intl.DateTimeFormat('en-US', options).format(date);
    },

    // Method to truncate long messages
    truncateMessage(message) {
      if (message.length > 25) {
        return message.slice(0, 25) + '...';
      }
      return message;
    },

    // Toggle message expansion
    toggleMessage(message) {
      message.isExpanded = !message.isExpanded;
    }
  }
};
</script>

<style scoped>
.contact-messages {
  padding: 30px;
  background-color: #f4f4f4;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

h2 {
  font-size: 2rem;
  color: #333;
  margin-bottom: 20px;
}

.messages-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background-color: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.messages-table th, .messages-table td {
  padding: 12px 15px;
  border: 1px solid #ddd;
  text-align: left;
  font-size: 1rem;
  word-wrap: break-word;
}

.messages-table th {
  background-color: #1E90FF;
  color: white;
  font-weight: bold;
  text-transform: uppercase;
}

.messages-table td {
  background-color: #f9f9f9;
  word-wrap: break-word;
  max-width: 200px; /* Optional, to fix the maximum width of the columns */
}

.messages-table tr:nth-child(even) td {
  background-color: #f1f1f1;
}

button {
  background: none;
  border: none;
  color: #1E90FF;
  cursor: pointer;
  font-size: 1rem;
  text-decoration: underline;
  padding: 5px 10px;
}

button:hover {
  text-decoration: none;
  background-color: #1E90FF;
  color: white;
  border-radius: 5px;
}

button:focus {
  outline: none;
}

.message-text {
  font-size: 1rem;
  color: #333;
  height: 60px; /* Limit the height for truncated messages */
  overflow: hidden;
  display: block; /* Ensure the message behaves like a block element */
  white-space: normal; /* Allow text to wrap within the message */
  transition: height 0.3s ease-in-out; /* Smooth transition for vertical expansion */
  max-width: 200px; /* Limit the width of the message */
}

.message-text.expanded {
  height: auto; /* Allow the height to expand automatically */
  overflow: visible; /* Ensure full message is visible */
}
</style>
