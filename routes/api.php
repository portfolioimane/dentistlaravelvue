<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Full Controller Path Imports
use App\Http\Controllers\Api\Customer\AuthController;
use App\Http\Controllers\Api\Customer\KeysController;
 

use App\Http\Controllers\Api\Customer\ReviewController;

use App\Http\Controllers\Api\Customer\ResetPasswordController;

use App\Http\Controllers\Api\Backend\PaymentSettingController as BackendPaymentSettingController;

use App\Http\Controllers\Api\Backend\EmailSettingController as BackendEmailSettingController;

use App\Http\Controllers\Api\Backend\SmsSettingController as BackendSmsSettingController;

use App\Http\Controllers\Api\Backend\HomePageHeaderController as BackendHomePageHeaderController;

use App\Http\Controllers\Api\Backend\PatientHistoriesController;


use App\Http\Controllers\Api\Backend\UsersController;

use App\Http\Controllers\Api\Backend\GeneralCustomizeController;

// routes/api.php

use App\Http\Controllers\Api\Backend\BuisnessHoursController;

use App\Http\Controllers\Api\Backend\CustomersController as BackendCustomersController;


use App\Http\Controllers\Api\Backend\ContactController as BackendContactController;
use App\Http\Controllers\Api\Backend\ServicesController as BackendServicesController;

use App\Http\Controllers\Api\Backend\LessonsController as BackendLessonsController;
use App\Http\Controllers\Api\Backend\ReviewController as BackendReviewController; 



use App\Http\Controllers\Api\Customer\CutomizeController;

use App\Http\Controllers\Api\Customer\ContactController;

use App\Http\Controllers\Api\Customer\BookingController;

use App\Http\Controllers\Api\Backend\BookingsController as BackendBookingsController;


use App\Http\Controllers\Frontend\ServiceController;
Route::get('/services/featured', [ServiceController::class, 'getFeaturedServices']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/services/{serviceId}/reviews', [ReviewController::class, 'index']);
    Route::post('/services/{serviceId}/reviews', [ReviewController::class, 'store']);
});


Route::get('reviews/latest-featured', [ReviewController::class, 'latestFeaturedReviews']);


Route::get('services', [ServiceController::class, 'index']);
Route::middleware('auth:sanctum')->get('services/{service}', [ServiceController::class, 'show']); // Fetch service by ID

// Lessons Routes
// Route for the first lesson (no authentication required)




Route::post('/contact', [ContactController::class, 'store']);

Route::get('/general-customizes', [CutomizeController::class, 'index']);



// routes/api.php




Route::get('/homepage-header', [BackendHomePageHeaderController::class, 'getHeader']);

Route::post('/password/email', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('password/reset', [ResetPasswordController::class, 'reset']);
// Authentication Routes




Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);
// Public Auth Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


// Order Routes
Route::middleware('auth:sanctum')->prefix('bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::post('/create', [BookingController::class, 'create']);
    Route::get('/{id}', [BookingController::class, 'show']);
    Route::post('/create-stripe-payment', [BookingController::class, 'createStripePayment']);
    Route::post('/confirm-paypal-payment', [BookingController::class, 'confirmPaypalPayment']);






});



 
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/stripe/public-key', [KeysController::class, 'getStripePublicKey']);
    Route::get('/paypal/public-key', [KeysController::class, 'getPaypalPublicKey']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user', [AuthController::class, 'updateProfile']);
    Route::get('/mybookings', [BookingController::class, 'myBookings']);
    Route::get('/available-slots', [BookingController::class, 'getAvailableSlots']); // Get available time slots


});   


Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::apiResource('patients', BackendCustomersController::class);

      Route::get('/patients/{patientId}/histories', [PatientHistoriesController::class, 'index']);
    
    // Fetch a specific patient history by ID
    Route::get('/patient-histories/{historyId}', [PatientHistoriesController::class, 'getHistory']);
    
    // Add a new history entry for a patient
    Route::post('/patients/{patientId}/histories', [PatientHistoriesController::class, 'store']);
    
    // Update a patient history entry
    Route::put('/patient-histories/{historyId}', [PatientHistoriesController::class, 'update']);
    
    // Delete a patient history entry
    Route::delete('/patient-histories/{historyId}', [PatientHistoriesController::class, 'destroy']);

Route::get('/buisnesshours', [BuisnessHoursController::class, 'getBuisnessHours']);
Route::post('/buisnesshours', [BuisnessHoursController::class, 'updateBuisnessHours']);


Route::get('/bookings', [BackendBookingsController::class, 'index']); // Get all enrollments
Route::delete('/bookings/{bookingId}', [BackendBookingsController::class, 'delete']); // Get all enrollments


// Get details of a specific enrollment
Route::get('/bookings/{bookingId}', [BackendBookingsController::class, 'show']);

// Update the status of an enrollment
Route::put('/bookings/{bookingId}/status', [BackendBookingsController::class, 'updateStatus']);



    Route::apiResource('services', BackendServicesController::class);


        
    Route::put('/services/{serviceId}/toggle-featured', [BackendServicesController::class, 'toggleFeatured']);

    Route::get('/contact-messages', [BackendContactController::class, 'index']);
   


Route::prefix('users')->group(function () {
    Route::get('/', [UsersController::class, 'index']);
    Route::get('/current-user', [UsersController::class, 'currentUser']);
    Route::get('/customers', [UsersController::class, 'customers']);
    Route::post('/', [UsersController::class, 'store']);
    Route::put('/{user}', [UsersController::class, 'update']);
    Route::delete('/{user}', [UsersController::class, 'destroy']);
});


    
        Route::prefix('customize')->group(function () {
        Route::put('/homepage-header', [BackendHomePageHeaderController::class, 'update']);
        Route::get('/homepage-header', [BackendHomePageHeaderController::class, 'getHeader']);
       
        });


  Route::prefix('paymentsetting')->group(function () {
    Route::put('/update', [BackendPaymentSettingController::class, 'update']); // Update payment provider settings
    Route::get('/', [BackendPaymentSettingController::class, 'getSettings']); // Fetch payment provider settings
});

    Route::prefix('emailsettings')->group(function () {
    Route::put('/update', [BackendEmailSettingController::class, 'update']); // Update payment provider settings
    Route::get('/', [BackendEmailSettingController::class, 'getSettings']); // Fetch payment provider settings
});

    Route::prefix('smssettings')->group(function () {
    Route::put('/update', [BackendSmsSettingController::class, 'update']); // Update payment provider settings
    Route::get('/', [BackendSmsSettingController::class, 'getSettings']); // Fetch payment provider settings
});


Route::prefix('general-customizes')->group(function () {
    Route::get('/', [GeneralCustomizeController::class, 'index']);
    Route::post('/upload-logo', [GeneralCustomizeController::class, 'uploadLogo']);
    Route::post('/update-or-create', [GeneralCustomizeController::class, 'updateOrCreate']);

});

      Route::prefix('reviews')->group(function () {
    Route::get('/', [BackendReviewController::class, 'index']);
    Route::put('/{id}', [BackendReviewController::class, 'update']);
    Route::put('/{id}/feature', [BackendReviewController::class, 'feature']);
    Route::delete('/{id}', [BackendReviewController::class, 'destroy']);
    Route::post('/', [BackendReviewController::class, 'store']);
      });



});







