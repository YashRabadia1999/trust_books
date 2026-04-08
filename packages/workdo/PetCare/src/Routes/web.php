<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Workdo\PetCare\Http\Controllers\PetAdoptionController;
use Workdo\PetCare\Http\Controllers\PetAdoptionRequestController;
use Workdo\PetCare\Http\Controllers\PetAdoptionRequestPaymentsController;
use Workdo\PetCare\Http\Controllers\PetAppointmentsController;
use Workdo\PetCare\Http\Controllers\PetCareAboutUsController;
use Workdo\PetCare\Http\Controllers\PetCareAdditionalSettingController;
use Workdo\PetCare\Http\Controllers\PetCareBillingPaymentsController;
use Workdo\PetCare\Http\Controllers\PetCareBrandSettingController;
use Workdo\PetCare\Http\Controllers\PetCareBannerSettingController;
use Workdo\PetCare\Http\Controllers\PetCareContactUsController;
use Workdo\PetCare\Http\Controllers\PetCareDashboardController;
use Workdo\PetCare\Http\Controllers\PetCareFAQController;
use Workdo\PetCare\Http\Controllers\PetCareFaqQuestionAnswerController;
use Workdo\PetCare\Http\Controllers\PetCareFrontendController;
use Workdo\PetCare\Http\Controllers\PetCareReviewController;
use Workdo\PetCare\Http\Controllers\PetCarePackagesPageSettingController;
use Workdo\PetCare\Http\Controllers\PetCareServicesFeaturesProcessController;
use Workdo\PetCare\Http\Controllers\PetGroomingPackagesController;
use Workdo\PetCare\Http\Controllers\PetServiceReviewController;
use Workdo\PetCare\Http\Controllers\PetServicesController;
use Workdo\PetCare\Http\Controllers\PetVaccinesController;
use Workdo\PetCare\Http\Controllers\PetCareSocialLinksController;

Route::middleware(['web'])->group(function () {
    Route::group(['middleware' => ['auth', 'verified', 'PlanModuleCheck:PetCare']], function () {
        Route::prefix('petcare')->group(function () {

            Route::get('pet-care/dashboard', [PetCareDashboardController::class, 'index'])->name('petcare.dashboard');

            // Pet Appointments
            Route::resource('pet-appointments', PetAppointmentsController::class)->names('pet.appointments');
            Route::get('pet-appointments/status-edit/{id}', [PetAppointmentsController::class, 'appointmentStatusEdit'])->name('pet.appointments.status.edit');
            Route::post('pet-appointments/status-update/{id}', [PetAppointmentsController::class, 'appointmentStatusUpdate'])->name('pet.appointments.status.update');
            Route::post('pet-grooming-packages/get/multipul-packages/price', [PetGroomingPackagesController::class, 'getMultipulPackagePrice'])->name('get.multipul.package.price');
            Route::post('pet-services/get/multipul-services/price', [PetGroomingPackagesController::class, 'getMultipulServicePrice'])->name('get.multipul.service.price');

            // Pet Services
            Route::resource('pet-services', PetServicesController::class)->names('pet.services');
            Route::get('pet-services/description/{id}', [PetServicesController::class, 'description'])->name('pet.services.description');
            Route::get('pet-service/features-process-page/{serviceId}', [PetCareServicesFeaturesProcessController::class, 'showFeaturesProcessPage'])->name('show.features.process.page');
            Route::post('pet-service/features/{serviceId}/store', [PetCareServicesFeaturesProcessController::class, 'storeServiceFeatures'])->name('store.service.features');
            Route::post('pet-service/process-steps/{serviceId}/store', [PetCareServicesFeaturesProcessController::class, 'storeServiceProcessSteps'])->name('store.service.process.steps');

            // Pet Vaccines
            Route::resource('pet-vaccines', PetVaccinesController::class)->names('pet.vaccines');
            Route::get('pet-vaccines/description/{id}', [PetVaccinesController::class, 'description'])->name('pet.vaccines.description');

            // Pet Packages
            Route::resource('pet-grooming-packages', PetGroomingPackagesController::class)->names('pet.grooming.packages');
            Route::post('pet-grooming-packages/get-service-price', [PetGroomingPackagesController::class, 'getServicePrice'])->name('get.pet.service.price');
            Route::post('pet-grooming-packages/get-vaccine-price', [PetGroomingPackagesController::class, 'getVaccinePrice'])->name('get.pet.vaccine.price');
            Route::get('pet-grooming-packages/description/{id}', [PetGroomingPackagesController::class, 'description'])->name('pet.groomings.package.description');

            // Pet Billing & Payments
            Route::get('/petcare-billing-payments', [PetCareBillingPaymentsController::class, 'index'])->name('petcare.billing.payments.index');
            Route::get('/petcare-billing-payments/create/{appointmentId}', [PetCareBillingPaymentsController::class, 'create'])->name('petcare.billing.payments.create');
            Route::post('/petcare-billing-payments/data-store', [PetCareBillingPaymentsController::class, 'store'])->name('petcare.billing.payments.store');
            Route::get('/petcare-billing-payments/show/{paymentId}', [PetCareBillingPaymentsController::class, 'show'])->name('petcare.billing.payments.show');
            Route::get('/petcare-billing-payments/show/description/{paymentId}', [PetCareBillingPaymentsController::class, 'description'])->name('petcare.billing.payments.description');

            // Pet Adoption
            Route::resource('pet-adoption', PetAdoptionController::class)->names('pet.adoption');

            // Pet Adoption Request
            Route::get('/pet-adoption/request/manage', [PetAdoptionRequestController::class, 'index'])->name('pet.adoption.request.index');
            Route::get('/pet-adoption/request/create/{addoptionId}', [PetAdoptionRequestController::class, 'create'])->name('pet.adoption.request.create');
            Route::post('/pet-adoption/request/data-store', [PetAdoptionRequestController::class, 'store'])->name('pet.adoption.request.store');
            Route::get('/pet-adoption/request/show/{adoptionRequestId}', [PetAdoptionRequestController::class, 'show'])->name('pet.adoption.request.show');
            Route::get('/pet-adoption/request/edit/{adoptionRequestId}', [PetAdoptionRequestController::class, 'edit'])->name('pet.adoption.request.edit');
            Route::put('/pet-adoption/request/update/{adoptionRequestId}', [PetAdoptionRequestController::class, 'update'])->name('pet.adoption.request.update');
            Route::delete('/pet-adoption/request/delete/{adoptionRequestId}', [PetAdoptionRequestController::class, 'destroy'])->name('pet.adoption.request.destroy');
            Route::get('/pet-adoption/request/status-edit/{adoptionRequestId}', [PetAdoptionRequestController::class, 'adoptionRequestStatusEdit'])->name('pet.adoption.request.status.edit');
            Route::post('/pet-adopton/request/status-update/{adoptionRequestId}', [PetAdoptionRequestController::class, 'adoptionRequestStatusUpdate'])->name('pet.adoption.request.status.update');

            // Pet Adoption Request Payments
            Route::get('/pet-adoption/request/payments', [PetAdoptionRequestPaymentsController::class, 'index'])->name('pet.adoption.request.payments.index');
            Route::get('/pet-adoption/request/payments/create/{adoptionRequestId}', [PetAdoptionRequestPaymentsController::class, 'create'])->name('pet.adoption.request.payments.create');
            Route::post('/pet-adoption/request/payments/data-store', [PetAdoptionRequestPaymentsController::class, 'store'])->name('pet.adoption.request.payments.store');
            Route::get('/pet-adoption/request/payments/show/{paymentId}', [PetAdoptionRequestPaymentsController::class, 'show'])->name('pet.adoption.request.payments.show');
            Route::get('/pet-adoption/request/payments/show/description/{paymentId}', [PetAdoptionRequestPaymentsController::class, 'description'])->name('pet.adoption.request.payments.description');

            // Brand Setting
            Route::resource('pet-care-brand-setting', PetCareBrandSettingController::class)->names('petcare.brand.setting');

            // Banner Setting
            Route::resource('pet-care-banner-setting', PetCareBannerSettingController::class)->names('petcare.banner.setting');

            // Review Setting & Reviews
            Route::resource('pet-care-review', PetCareReviewController::class)->names('petcare.review');
            Route::post('pet-care-review/setting', [PetCareReviewController::class, 'reviewSettingStore'])->name('petcare.review.setting.store');
            Route::get('pet-care-review/review/{reviewId}', [PetCareReviewController::class, 'reviewDetails'])->name('petcare.review.details');
            Route::get('pet-care-review/{reviewId}/action', [PetCareReviewController::class, 'action'])->name('petcare.review.action');
            Route::put('pet-care-review/{reviewId}/action/store', [PetCareReviewController::class, 'changeAction'])->name('petcare.review.change.action');

            // Service Review
            Route::resource('pet-service/review', PetServiceReviewController::class)->names('service.review');
            Route::post('pet-service-review/setting', [PetServiceReviewController::class, 'serviceReviewSettingStore'])->name('service.review.setting.store');
            Route::get('pet-service-review/review/{seriveReviewId}', [PetServiceReviewController::class, 'reviewDetails'])->name('service.review.details');
            Route::get('pet-service-review/{seriveReviewId}/action', [PetServiceReviewController::class, 'action'])->name('service.review.action');
            Route::put('pet-service-review/{seriveReviewId}/action/store', [PetServiceReviewController::class, 'changeAction'])->name('service.review.change.action');

            // Packages Page Setting
            Route::resource('pet-care-packages-page-setting', PetCarePackagesPageSettingController::class)->names('petcare.packages.page.setting');

            // About Us Page 
            Route::resource('pet-care-about-us-page-setting', PetCareAboutUsController::class)->names('petcare.about.us');

            // FAQ Page 
            Route::resource('pet-care-faq-page-setting', PetCareFAQController::class)->names('petcare.faq');
            Route::get('pet-care-faq-page/question-answer-page/{faqId}', [PetCareFaqQuestionAnswerController::class, 'showQuestionAnswerPage'])->name('show.question.answer.page');
            Route::post('pet-care-faq-page/question-answer/{faqId}/store', [PetCareFaqQuestionAnswerController::class, 'storeQuestionAnswer'])->name('store.question.answer');
            Route::post('pet-care-faq-page/have-question/', [PetCareFAQController::class, 'faqSettingStore'])->name('faq.setting.store');

            // Contact Us Page 
            Route::resource('pet-care/contact-us', PetCareContactUsController::class)->names('petcare.contact.us');
            Route::get('pet-care-contact-us/setting/page/', [PetCareContactUsController::class, 'indexContactUsSettingPage'])->name('petcare.contact.us.setting.index');
            Route::post('pet-care-contact-us/setting/store', [PetCareContactUsController::class, 'contactUsSettingStore'])->name('petcare.contact.us.setting.store');            
            Route::get('/pet-care/contact-us/message/show/{contactId}', [PetCareContactUsController::class, 'messageShow'])->name('petcare.contact.us.message.show');

            // Social Links Page 
            Route::resource('pet-care-social-links-setting', PetCareSocialLinksController::class)->names('petcare.social.links');

            // Additional Setting
            Route::resource('pet-care-additional-setting', PetCareAdditionalSettingController::class)->names('petcare.additional.setting');
        });
    });

    Route::prefix('petcare')->group(function () {

        // Home Page
        Route::get('{slug}/home', [PetCareFrontendController::class, 'ShowPetCareFrontendPage'])->name('petcare.frontend');

        // Packages Page
        Route::get('/{slug}/front/packages', [PetCareFrontendController::class, 'ShowPackagesPage'])->name('petcare.frontend.packages.page');

        // Services Page
        Route::get('/{slug}/front/services', [PetCareFrontendController::class, 'ShowServicesPage'])->name('petcare.frontend.services.page');
        Route::get('/{slug}/front/service/details/{serviceId}', [PetCareFrontendController::class, 'ShowServiceDetailsPage'])->name('petcare.frontend.service.details.page');

        // Pet Care Review store
        Route::post('/{slug}/front/review-form/data-store', [PetCareFrontendController::class, 'ReviewFormDataStore'])->name('petcare.frontend.review.store');
        
        // Service Review
        Route::post('/{slug}/front/service/review-form/data-store', [PetCareFrontendController::class, 'ServiceReviewFormDataStore'])->name('service.frontend.review.store');

        // About Us Page        
        Route::get('/{slug}/front/about-us', [PetCareFrontendController::class, 'ShowAboutUsPage'])->name('petcare.frontend.about.us.page');

        // FAQ Page        
        Route::get('/{slug}/front/faq', [PetCareFrontendController::class, 'ShowFAQPage'])->name('petcare.frontend.faq.page');

        // Contact Us Page        
        Route::get('/{slug}/front/contact-us', [PetCareFrontendController::class, 'ShowContactUsPage'])->name('petcare.frontend.contact.us.page');        
        Route::post('/{slug}/front/contact-us-form/data-store', [PetCareFrontendController::class, 'ContactUsFormDataStore'])->name('petcare.frontend.contact.us.store');

        // Adoption Appointment
        Route::get('/{slug}/front/adoption/application-form/{adoptionId}', [PetCareFrontendController::class, 'ShowAdoptionFormPage'])->name('petcare.frontend.adoption.form.page');
        Route::post('/{slug}/front/adoption/application-form/data-store/{adoptionId}', [PetCareFrontendController::class, 'AdoptionFormDataStore'])->name('petcare.frontend.adoption.form.store');

        // Service/Package Booking
        Route::get('/{slug}/front/service-package/appointment-form/{serviceId?}/{packageId?}', [PetCareFrontendController::class, 'ShowAppointmentFormPage'])->name('petcare.frontend.appointment.form.page');
        Route::post('/{slug}/front/packages/get/multipul-packages/price', [PetCareFrontendController::class, 'FrontgetMultipulPackagePrice'])->name('front.get.multipul.package.price');
        Route::post('/{slug}/front/services/get/multipul-services/price', [PetCareFrontendController::class, 'FrontgetMultipulServicePrice'])->name('front.get.multipul.service.price');
        Route::post('/{slug}/front/appointment-form/booking', [PetCareFrontendController::class, 'AppointmentFormDataStore'])->name('petcare.frontend.appointment.booking');
    });
});
