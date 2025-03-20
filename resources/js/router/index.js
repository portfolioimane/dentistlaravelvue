import { createRouter, createWebHistory } from 'vue-router';
import store from '../store/index.js';
import ServicesList from '../components/Frontend/Services/ServicesList.vue';
import ServicePage from '../components/Frontend/Services/ServicePage.vue';
import Checkout from '../components/Frontend/Checkout.vue';

// Import the layout
import AppLayout from '../components/Frontend/Layout/AppLayout.vue';
import AdminLayout from '../components/Admin/Layout/AdminLayout.vue'; // Admin Layout

// Import route components
import Login from '../components/Auth/Login.vue';
import Register from '../components/Auth/Register.vue';
import ResetPassword from '../components/Frontend/ResetPassword.vue';
import ForgotPassword from '../components/Frontend/ForgotPassword.vue';
import Profile from '../components/Frontend/Profile.vue';
import Contact from '../components/Frontend/Contact.vue';

import CustomerLayout from '../components/Frontend/CustomerLayout.vue';
import Home from '../components/Frontend/Home.vue';


// Admin Components
import AdminDashboard from '../components/Admin/Dashboard/AdminDashboard.vue';

import BuisnessHours from '../components/Admin/BuisnessHours/BuisnessHours.vue';



import PaymentSetting from '../components/Admin/Settings/PaymentSetting.vue';

import GeneralCustomize from '../components/Admin/Customize/GeneralCustomize.vue';


import HomePageHeader from '../components/Admin/Customize/HomePageHeader.vue';

import Customers from '../components/Admin/Customers/Customers.vue';
import ContactMessages from '../components/Admin/Customers/ContactMessages.vue';

import Services from '../components/Admin/Services/Services.vue';
import AddService from '../components/Admin/Services/AddService.vue';
import EditService from '../components/Admin/Services/EditService.vue';

import ManageLessons from '../components/Admin/Lessons/ManageLessons.vue';

import AddLesson from '../components/Admin/Lessons/AddLesson.vue';
import EditLesson from '../components/Admin/Lessons/EditLesson.vue';

import Bookings from '../components/Admin/Bookings/Bookings.vue';
import BackendBookingDetails from '../components/Admin/Bookings/BookingDetails.vue';

import MyBookings from '../components/Frontend/MyBookings.vue';

import AddReview from '../components/Admin/Review/AddReview.vue';
import ReviewList from '../components/Admin/Review/ReviewList.vue';



const routes = [
  
  {
    path: '/',
    component: AppLayout,
    children: [
       { path: 'servicesList',
         name: 'servicesList',
         component: ServicesList 
       },
       { path: '/service/:serviceId',
        name: 'service',
        component: ServicePage,
        meta: { requiresAuth: true },

       },
 
        {
        path: '/checkout/:serviceId',
        name: 'checkout',
        component: Checkout,
        meta: { requiresAuth: true },
      },
      {
        name: 'Login',
        path: '/login',
        component: Login,
      },
      {
        name: 'Register',
        path: '/register',
        component: Register,
      },
      {
        name: 'ResetPassword',
        path: '/password/reset/:token',
        component: ResetPassword,
        props: true,
      },
      {
        name: 'ForgotPassword',
        path: '/forgotpassword',
        component: ForgotPassword,
        props: true,
      },
      {
        name: 'Home',
        path: '/',
        component: Home,
      },
     
  
      {
        name: 'Contact',
        path: '/contact',
        component: Contact,
      },
      
      {
        path: '/customerdashboard',
        component: CustomerLayout,
        meta: { requiresAuth: true },
        children: [
          {
            name: 'Profile',
            path: 'profile',
            component: Profile,
          },
              {
            name: 'mybookings',
            path: 'mybookings',
            component: MyBookings,
          },
     
          
              
        ],
      },
    ],
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAdmin: true },
    children: [
      {
        name: 'AdminDashboard',
        path: 'dashboard',
        component: AdminDashboard,
      },
            {
        name: 'Services',
        path: 'services',
        component: Services,
      },
      {
        name: 'BuisnessHours',
        path: 'buisnesshours',
        component: BuisnessHours,
      },
       


       {
        name: 'AddReview',
        path: 'reviews/add',
        component: AddReview,
      },
       {
        name: 'Reviews',
        path: 'reviews',
        component: ReviewList,
      },

{
  path: 'bookings',
  name: 'Bookings',
  component: Bookings, 
},
     {
            name: 'BackendBookingDetails',
            path: 'booking/:id',
            component: BackendBookingDetails,
       },


      {
        name: 'AddServices',
        path: 'services/add',
        component: AddService,
      },

          {
        path: 'services/edit/:id',
        name: 'EditService',
        component: EditService,
      },
             {
        path: 'courses/:courseId/lessons/edit/:lessonId',
        name: 'EditLesson',
        component: EditLesson,
      },
     
               {
        name: 'Customers',
        path: 'customers',
        component: Customers,
      },

{
  path: 'contact-messages',
  name: 'ContactMessages',
  component: ContactMessages,
},
      
       {
        name: 'PaymentSetting',
        path: 'paymentsetting',
        component: PaymentSetting,
      },
              {
        name: 'GeneralCustomize',
        path: 'generalcustomize',
        component: GeneralCustomize,
      },
      {
        name: 'HomePageHeader',
        path: 'customize/homepageheader',
        component: HomePageHeader,
      },


           
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  
});


router.beforeEach(async (to, from, next) => {
  const isAuthenticated = store.getters['auth/isAuthenticated'];
  const user = store.getters['auth/user']; // Fetch the user from Vuex store
  const authChecked = store.getters['auth/authChecked']; // Get auth check status

  if (!authChecked) {
    // Wait until the auth check is finished
    return next();
  }

  if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
    next({ path: '/login', query: { redirect: to.fullPath } });
  } else if (to.matched.some(record => record.meta.requiresAdmin)) {
    if (!user || user.role !== 'admin') {
      next({ path: '/' });
    } else {
      next();
    }
  } else {
    next();
  }
});




export default router;

