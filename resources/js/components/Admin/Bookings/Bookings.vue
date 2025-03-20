<template>
  <div class="bookings">
    <h1>Manage Bookings</h1>

    <!-- Action Buttons for CSV Download and Print -->
    <div class="action-buttons">
      <button class="btn download-btn" @click="downloadCSV">Download CSV</button>
      <button class="btn print-btn" @click="printPage">Print</button>
    </div>

    <!-- Table Container -->
    <div class="table-container">
      <table class="bookings-table">
        <thead>
          <tr>
            <th>Service</th>
            <th>Date</th>
            <th>Slot Time</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="booking in bookings" :key="booking.id">
            <td>{{ booking.service.name }}</td>
            <td>{{ booking.date }}</td>
            <td>{{ booking.start_time }} - {{ booking.end_time }}</td>
            <td>{{ booking.name }}</td>
            <td>{{ booking.phone }}</td>
            <td>{{ booking.payment_method }}</td>
            <td>
              <select v-model="booking.status" @change="updateBookingStatus(booking)" class="status-dropdown">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </td>
            <td>
              <button class="btn btn-golden" @click="viewBookingDetails(booking.id)">View</button>
              <button class="btn delete-btn" @click="openDeleteModal(booking.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Are you sure you want to delete this booking?</h3>
        <div class="modal-actions">
          <button class="btn danger" @click="deleteBooking">Yes, Delete</button>
          <button class="btn primary" @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      bookings: [],
      showDeleteModal: false,
      bookingToDelete: null,
    };
  },
  methods: {
    // Fetch all bookings from the backend
    fetchBookings() {
      this.$store.dispatch("backendBookings/fetchBookings")
        .then(() => {
          this.bookings = this.$store.getters["backendBookings/bookings"];
        })
        .catch((error) => {
          console.error("Error fetching bookings:", error);
        });
    },

    // View details of a specific booking
    viewBookingDetails(bookingId) {
      this.$router.push({ name: 'BackendBookingDetails', params: { id: bookingId } });
    },

    // Update the booking status when the dropdown changes
    updateBookingStatus(booking) {
      this.$store.dispatch("backendBookings/updateBooking", {
        bookingId: booking.id,
        status: booking.status,
      })
        .then(() => {
          console.log('Booking status updated');
        })
        .catch((error) => {
          console.error('Error updating booking status:', error);
        });
    },

    // Open the delete confirmation modal
    openDeleteModal(bookingId) {
      this.bookingToDelete = bookingId;
      this.showDeleteModal = true;
    },

    // Close the delete confirmation modal
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.bookingToDelete = null;
    },

    // Delete a booking
    deleteBooking() {
      this.$store.dispatch("backendBookings/deleteBooking", this.bookingToDelete)
        .then(() => {
          this.bookings = this.bookings.filter((booking) => booking.id !== this.bookingToDelete);
          this.closeDeleteModal();
        })
        .catch((error) => {
          console.error("Error deleting booking:", error);
        });
    },

    // Download the bookings as a CSV
    downloadCSV() {
      const headers = ['Service', 'Date', 'Slot Time', 'Customer', 'Phone', 'Payment', 'Status'];
      const rows = this.bookings.map(booking => [
        booking.service.name,
        booking.date,
        `${booking.start_time} - ${booking.end_time}`,
        booking.name,
        booking.phone,
        booking.payment_method,
        booking.status,
      ]);

      let csvContent = "data:text/csv;charset=utf-8," + headers.join(",") + "\n";
      rows.forEach(row => {
        csvContent += row.join(",") + "\n";
      });

      const encodedUri = encodeURI(csvContent);
      const link = document.createElement("a");
      link.setAttribute("href", encodedUri);
      link.setAttribute("download", "bookings.csv");
      document.body.appendChild(link);
      link.click();
    },

    // Print only the bookings table
    printPage() {
      const tableContent = document.querySelector('.bookings-table').outerHTML;
      const printWindow = window.open('', '', 'width=900,height=700');
      printWindow.document.write(`
        <html>
          <head>
            <title>Print Bookings</title>
            <style>
              body {
                font-family: Arial, sans-serif;
                padding: 20px;
              }
              table {
                width: 100%;
                border-collapse: collapse;
              }
              th, td {
                padding: 12px;
                border: 1px solid #ddd;
                text-align: center;
              }
              th {
                background-color: #f4f4f4;
                font-weight: bold;
              }
            </style>
          </head>
          <body>
            ${tableContent}
          </body>
        </html>
      `);
      printWindow.document.close();
      printWindow.print();
      printWindow.close();
    },
  },
  mounted() {
    this.fetchBookings();
  },
};
</script>

<style scoped>
/* Page container */
.bookings {
  padding: 30px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  font-size: 0.75rem;
}

/* Title */
h1 {
  color: #1E90FF;
  margin-bottom: 20px;
  text-align: center;
  font-size: 1.5rem;
  font-weight: bold;
}

/* Action Buttons */
.action-buttons {
  margin-bottom: 20px;
  display: flex;
  justify-content: space-between;
}

.btn {
  padding: 6px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
  font-size: 0.9rem;
}

.download-btn {
  background-color: #28a745;
  color: white;
}

.download-btn:hover {
  background-color: #218838;
}

.print-btn {
  background-color: #17a2b8;
  color: white;
}

.print-btn:hover {
  background-color: #138496;
}

/* Table container */
.table-container {
  width: 100%;
  overflow: visible;
}

/* Table styling */
.bookings-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  font-size: 0.9rem;
}

.bookings-table th,
.bookings-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
  white-space: nowrap;
}

.bookings-table th {
  background-color: #1E90FF;
  color: white;
  font-weight: bold;
}

.bookings-table td {
  min-width: 100px;
}

.bookings-table tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

.bookings-table tbody tr:hover {
  background-color: #e6f7ff;
}

/* Status dropdown */
.status-dropdown {
  padding: 8px;
  font-size: 14px;
  cursor: pointer;
  border-radius: 4px;
  border: 1px solid #ddd;
}

.status-dropdown:focus {
  outline: none;
  border-color: #007bff;
}

/* Modal overlay */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
  text-align: center;
}

.modal-actions {
  margin-top: 20px;
  display: flex;
  justify-content: space-between;
}

/* Button styles for the modal */
.btn.danger {
  background-color: #e74c3c;
  color: white;
}

.btn.primary {
  background-color: #007bff;
  color: white;
}

.btn:hover {
  opacity: 0.9;
}

.delete-btn {
  background-color: #e74c3c;
  color: white;
  padding: 6px 12px;
}

.delete-btn:hover {
  background-color: #c0392b;
}

.btn-golden {
  background-color: #f39c12;
  color: white;
  padding: 6px 12px;
}

.btn-golden:hover {
  background-color: #e67e22;
}
</style>
