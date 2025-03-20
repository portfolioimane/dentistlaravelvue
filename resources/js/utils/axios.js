import axios from 'axios';

const axiosInstance = axios.create({
  baseURL: process.env.MIX_API_URL || 'http://localhost/api', // Adjust baseURL as needed
  timeout: 20000, // Optional: Set a timeout for requests
  withCredentials: true,  // Since we're using cookies for authentication
});

// Add an interceptor to handle requests and responses
axiosInstance.interceptors.request.use(
  config => {
    // Add Authorization header if user-token is available
    const token = sessionStorage.getItem('user-token');  // Retrieve token from sessionStorage
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`; // Add Bearer token
    }

    // Add session_id header from sessionStorage
    const sessionId = sessionStorage.getItem('session_id');
    if (sessionId) {
      config.headers['X-Session-ID'] = sessionId; // Add session_id as a header
    }

    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

export default axiosInstance;
