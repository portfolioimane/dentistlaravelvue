import { createStore } from 'vuex'; // Updated import
import auth from './modules/auth.js';
import keys from './modules/keys.js';
import paymentSetting from './modules/backend/paymentSetting.js';
import backendHomePageHeader from './modules/backend/HomePageHeader.js';
import backendUsers from './modules/backend/users.js';
import backendGeneralCustomize from './modules/backend/generalCustomize.js';
import generalCustomize from './modules/generalcustomize.js';
import contact from './modules/contact.js';
import backendContact from './modules/backend/contact.js';
import backendServices from './modules/backend/services.js';
import backendLessons from './modules/backend/lessons.js';
import backendBookings from './modules/backend/bookings.js';
import booking from './modules/booking.js';
import reviews from './modules/review.js';
import backendReview from './modules/backend/review.js';
import buisnessHours from './modules/backend/buisnessHours.js';
import services from './modules/services.js';



const store = createStore({
  modules: {
    backendServices,  // Corrected module name
    services,
    auth,
    paymentSetting,
    keys,
    backendHomePageHeader,
    backendUsers,
    backendGeneralCustomize,
    generalCustomize,
    contact,
    backendContact,
    backendLessons,
    backendBookings,
    booking,
    reviews,
    backendReview,
    buisnessHours,
  },
});


export default store;

