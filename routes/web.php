<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\MentorshipsController;

/*
 * Global Routes
 * Routes that are used between both frontend and backend.
 */



// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap']);


Route::get('/sitemap-' .str_slug(config('app.name')) . '/{file?}', 'SitemapController@index');

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    include_route_files(__DIR__ . '/frontend/');
});

/*
 * Backend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'user', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__ . '/backend/');
});

Route::group(['namespace' => 'Backend', 'prefix' => 'user', 'as' => 'admin.', 'middleware' => 'auth'], function () {

//==== Messages Routes =====//
    Route::get('messages', ['uses' => 'MessagesController@index', 'as' => 'messages']);
    Route::post('messages/unread', ['uses' => 'MessagesController@getUnreadMessages', 'as' => 'messages.unread']);
    Route::post('messages/send', ['uses' => 'MessagesController@send', 'as' => 'messages.send']);
    Route::post('messages/reply', ['uses' => 'MessagesController@reply', 'as' => 'messages.reply']);
});


Route::get('category/{category}/blogs', 'BlogController@getByCategory')->name('blogs.category');
Route::get('tag/{tag}/blogs', 'BlogController@getByTag')->name('blogs.tag');
Route::get('blog/{slug?}', 'BlogController@getIndex')->name('blogs.index');
Route::post('blog/{id}/comment', 'BlogController@storeComment')->name('blogs.comment');
Route::get('blog/comment/delete/{id}', 'BlogController@deleteComment')->name('blogs.comment.delete');

Route::get('teachers', 'Frontend\HomeController@getTeachers')->name('teachers.index');
Route::get('teachers/{id}/show', 'Frontend\HomeController@showTeacher')->name('teachers.show');


Route::post('newsletter/subscribe', 'Frontend\HomeController@subscribe')->name('subscribe');

//============Course Routes=================//
Route::get('courses', ['uses' => 'CoursesController@all', 'as' => 'courses.all']);
Route::get('course/{slug}', ['uses' => 'CoursesController@show', 'as' => 'courses.show']);
Route::get('course/{slug}/addreview', ['uses' => 'CoursesController@reviewShow', 'as' => 'courses.review.show']);
//Route::post('course/payment', ['uses' => 'CoursesController@payment', 'as' => 'courses.payment']);
Route::post('course/{course_id}/rating', ['uses' => 'CoursesController@rating', 'as' => 'courses.rating']);
Route::get('category/{category}/courses', ['uses' => 'CoursesController@getByCategory', 'as' => 'courses.category']);
Route::post('courses/{id}/review', ['uses' => 'CoursesController@addReview', 'as' => 'courses.review']);
Route::get('courses/review/{id}/edit', ['uses' => 'CoursesController@editReview', 'as' => 'courses.review.edit']);
Route::post('courses/review/{id}/edit', ['uses' => 'CoursesController@updateReview', 'as' => 'courses.review.update']);
Route::get('courses/review/{id}/delete', ['uses' => 'CoursesController@deleteReview', 'as' => 'courses.review.delete']);

Route::get('reviews', ['uses' => 'CoursesController@allReviews', 'as' => 'courses.reviews.all']);

//============Longterm Program Routes=================//
Route::get('programs', ['uses' => 'ProgramsController@all', 'as' => 'programs.all']);
Route::get('programs/{slug}', ['uses' => 'ProgramsController@show', 'as' => 'programs.show']);
//Route::post('course/payment', ['uses' => 'CoursesController@payment', 'as' => 'courses.payment']);
Route::post('programs/{course_id}/rating', ['uses' => 'ProgramsController@rating', 'as' => 'programs.rating']);
Route::get('category/{category}/programs', ['uses' => 'ProgramsController@getByCategory', 'as' => 'programs.category']);
Route::post('programs/{id}/review', ['uses' => 'ProgramsController@addReview', 'as' => 'programs.review']);
Route::get('programs/review/{id}/edit', ['uses' => 'ProgramsController@editReview', 'as' => 'programs.review.edit']);
Route::post('programs/review/{id}/edit', ['uses' => 'ProgramsController@updateReview', 'as' => 'programs.review.update']);
Route::get('programs/review/{id}/delete', ['uses' => 'ProgramsController@deleteReview', 'as' => 'programs.review.delete']);

//============Store Routes=================//
Route::get('store', ['uses' => 'StoreController@all', 'as' => 'store.all']);
Route::get('store/{slug}', ['uses' => 'StoreController@show', 'as' => 'store.show']);
//Route::post('course/payment', ['uses' => 'CoursesController@payment', 'as' => 'store.payment']);
//Route::post('store/{course_id}/rating', ['uses' => 'StoreController@rating', 'as' => 'store.rating']);
//Route::get('category/{category}/courses', ['uses' => 'StoreController@getByCategory', 'as' => 'store.category']);
//Route::post('store/{id}/review', ['uses' => 'StoreController@addReview', 'as' => 'store.review']);
//Route::get('store/review/{id}/edit', ['uses' => 'StoreController@editReview', 'as' => 'store.review.edit']);
//Route::post('store/review/{id}/edit', ['uses' => 'StoreController@updateReview', 'as' => 'store.review.update']);
//Route::get('store/review/{id}/delete', ['uses' => 'StoreController@deleteReview', 'as' => 'store.review.delete']);

//============Workshop Routes=================//
Route::get('workshops', ['uses' => 'WorkshopsController@all', 'as' => 'workshops.all']);
Route::get('workshop/{slug}', ['uses' => 'WorkshopsController@show', 'as' => 'workshops.show']);
//Route::post('workshop/payment', ['uses' => 'WorkshopsController@payment', 'as' => 'workshops.payment']);
Route::post('workshop/{workshop_id}/rating', ['uses' => 'WorkshopsController@rating', 'as' => 'workshops.rating']);
//Route::get('category/{category}/workshops', ['uses' => 'WorkshopsController@getByCategory', 'as' => 'workshops.category']);
Route::post('workshops/{id}/review', ['uses' => 'WorkshopsController@addReview', 'as' => 'workshops.review']);
Route::get('workshops/review/{id}/edit', ['uses' => 'WorkshopsController@editReview', 'as' => 'workshops.review.edit']);
Route::post('workshops/review/{id}/edit', ['uses' => 'WorkshopsController@updateReview', 'as' => 'workshops.review.update']);
Route::get('workshops/review/{id}/delete', ['uses' => 'WorkshopsController@deleteReview', 'as' => 'workshops.review.delete']);
Route::post('workshops/post/enroll', ['uses' => 'WorkshopsController@paypalPayment', 'as' => 'workshops.enroll.post']);
Route::post('workshops/{id}/enroll', ['uses' => 'WorkshopsController@paypalPayment', 'as' => 'workshops.enroll']);
Route::get('workshops/{id}/enroll', ['uses' => 'WorkshopsController@paypalPayment', 'as' => 'workshops.enroll']);
Route::get('workshops/paypal-payment/status', ['uses' => 'WorkshopsController@getPaymentStatus'])->name('workshops.paypal.status');
Route::get('workshops/enroll/status', ['uses' => 'WorkshopsController@status'])->name('workshops.status');

//============Gifts Routes=================//
Route::get('gifts', ['uses' => 'GiftsController@all', 'as' => 'gifts.all']);
Route::post('gift-purchase', ['uses' => 'CartController@singleCheckoutGifts', 'as' => 'gifts.purchase']);
Route::get('gifts/purchase/status', ['uses' => 'GiftsController@status'])->name('gifts.status');

//============Bundle Routes=================//
Route::get('bundles', ['uses' => 'BundlesController@all', 'as' => 'bundles.all']);
Route::get('bundle/{slug}', ['uses' => 'BundlesController@show', 'as' => 'bundles.show']);
//Route::post('course/payment', ['uses' => 'CoursesController@payment', 'as' => 'courses.payment']);
Route::post('bundle/{bundle_id}/rating', ['uses' => 'BundlesController@rating', 'as' => 'bundles.rating']);
Route::get('category/{category}/bundles', ['uses' => 'BundlesController@getByCategory', 'as' => 'bundles.category']);
Route::post('bundles/{id}/review', ['uses' => 'BundlesController@addReview', 'as' => 'bundles.review']);
Route::get('bundles/review/{id}/edit', ['uses' => 'BundlesController@editReview', 'as' => 'bundles.review.edit']);
Route::post('bundles/review/{id}/edit', ['uses' => 'BundlesController@updateReview', 'as' => 'bundles.review.update']);
Route::get('bundles/review/{id}/delete', ['uses' => 'BundlesController@deleteReview', 'as' => 'bundles.review.delete']);


Route::group(['middleware' => 'auth'], function () {
    Route::get('lesson/{course_id}/{slug}/', ['uses' => 'LessonsController@show', 'as' => 'lessons.show']);
    Route::post('lesson/{slug}/test', ['uses' => 'LessonsController@test', 'as' => 'lessons.test']);
    Route::post('lesson/{slug}/retest', ['uses' => 'LessonsController@retest', 'as' => 'lessons.retest']);
    Route::post('lesson/{id}/comment', ['uses' => 'LessonsController@addComment', 'as' => 'lessons.comment']);
    Route::post('video/progress', 'LessonsController@videoProgress')->name('update.videos.progress');
    Route::post('lesson/progress', 'LessonsController@courseProgress')->name('update.course.progress');
    Route::post('lesson/book-slot','LessonsController@bookSlot')->name('lessons.course.book-slot');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('assignment/{id}/student/', ['uses' => 'AssignmentController@show', 'as' => 'assignment.show']);
    Route::post('assignment/{id}/comment', ['uses' => 'AssignmentController@addComment', 'as' => 'assignment.comment']);
    Route::get('assignment/{id}/comment/delete', ['uses' => 'AssignmentController@deleteComment', 'as' => 'assignment.comment.delete']);
    Route::get('submission/{id}/student', ['uses' => 'AssignmentController@showSubmission', 'as' => 'submission.show']);
    Route::get('submission/{id}/create', ['uses' => 'AssignmentController@createSubmission', 'as' => 'submission.create']);

    // submission
    Route::get('submission/{assignment_id}/{submission_id}/edit', ['uses' => 'AssignmentController@editSubmission', 'as' => 'submission.edit']);
    Route::post('submission/{assignment_id}/{submission_id}/update', ['uses' => 'AssignmentController@updateSubmission', 'as' => 'submission.update']);
    Route::post('submission/{assignment_id}/create', ['uses' => 'AssignmentController@storeSubmission', 'as' => 'submission.store']);
    Route::get('submission/{assignment_id}/{submission_id}/critique/{attachment_id}', ['uses' => 'AssignmentController@showCritiques', 'as' => 'submission.all.critique']);
    Route::post('submission/{assignment_id}/add/{submission_id}/critique/{attachment_id}', ['uses' => 'AssignmentController@addCritique', 'as' => 'submission.critique']);

    //attachment
    Route::get('submission/{assignment_id}/attachment/{submission_id}', ['uses' => 'AssignmentController@createAttachment', 'as' => 'submission.attachment.create']);
    Route::post('submission/{assignment_id}/attachment/{submission_id}/create', ['uses' => 'AssignmentController@storeAttachment', 'as' => 'submission.attachment.store']);
    Route::get('submission/{assignment_id}/attachment/{submission_id}/edit/{id}', ['uses' => 'AssignmentController@editAttachment', 'as' => 'submission.attachment.edit']);
    Route::post('submission/{assignment_id}/attachment/{submission_id}/update/{id}', ['uses' => 'AssignmentController@updateAttachment', 'as' => 'submission.attachment.update']);
    Route::get('submission/{assignment_id}/attachment/{submission_id}/delete/{id}', ['uses' => 'AssignmentController@deleteAttachment', 'as' => 'submission.attachment.delete']);

    //sequence
    Route::get('submission/{assignment_id}/attachment/{submission_id}/sequence', ['uses' => 'AssignmentController@attachmentSequence', 'as' => 'submission.attachment.sequence']);
    Route::post('submission/{assignment_id}/attachment/{submission_id}/sequence/update', ['uses' => 'AssignmentController@updateSequence', 'as' => 'submission.attachment.sequence.update']);


    //TEACHER
    Route::get('student/{id}/assignment/', ['uses' => 'AssignmentController@showWithSubmission', 'as' => 'student.assignment.show']);
    Route::get('student/{assignment_id}/{submission_id}/submission', ['uses' => 'AssignmentController@showStudentSubmission', 'as' => 'student.submission.show']);

    Route::post('student/{assignment_id}/{submission_id}/update', ['uses' => 'AssignmentController@addAttachmentCritique', 'as' => 'student.submission.critique']);

    Route::get('log/{teacher_id}/clear', ['uses' => 'AssignmentController@readAllLog', 'as' => 'log.clear']);

});

// TEACHER
Route::group(['middleware' => 'auth'], function () {

    Route::get('schedule-course/{id}', ['uses' => 'CoursesController@teacherShow', 'as' => 'courses.teacher.show']);
    Route::get('student/{id}/course', ['uses' => 'CoursesController@userCourse', 'as' => 'courses.student.detail']);

});

Route::get('/search', [HomeController::class, 'searchAll'])->name('search');
Route::get('/search-course', [HomeController::class, 'searchCourse'])->name('search-course');
Route::get('/search-bundle', [HomeController::class, 'searchBundle'])->name('search-bundle');
Route::get('/search-blog', [HomeController::class, 'searchBlog'])->name('blogs.search');


Route::get('/faqs', 'Frontend\HomeController@getFaqs')->name('faqs');

Route::get('/how-it-works', [HomeController::class, 'howItWork'])->name('howitwork');

Route::get('/gallery', [HomeController::class, 'showGallery'])->name('gallery');

Route::get('/mentorship', [HomeController::class, 'showMentorship'])->name('mentorship');
Route::get('/mentorship-register', ['uses' => 'CartController@singleCheckoutMentorship', 'as' => 'mentorship.enroll.get']);
Route::post('/mentorship-register', ['uses' => 'CartController@singleCheckoutMentorship', 'as' => 'mentorship.enroll']);

/*=============== Theme blades routes ends ===================*/


Route::get('contact', 'Frontend\ContactController@index')->name('contact');
Route::post('contact/send', 'Frontend\ContactController@send')->name('contact.send');


Route::get('download', ['uses' => 'Frontend\HomeController@getDownload', 'as' => 'download']);

Route::group(['middleware' => 'auth'], function () {
    Route::post('cart/checkout', ['uses' => 'CartController@checkout', 'as' => 'cart.checkout']);
    Route::post('cart/course-checkout', ['uses' => 'CartController@singleCheckout', 'as' => 'cart.singleCheckout']);
    Route::get('cart/course-checkout', ['uses' => 'CartController@singleCheckout', 'as' => 'cart.singleCheckout']);
    Route::post('cart/course-checkout/submit', ['uses' => 'CartController@singleCheckoutSubmit', 'as' => 'cart.singleCheckoutSubmit']);
    //gifts
    Route::post('cart/gift-checkout/submit', ['uses' => 'CartController@singleCheckoutGiftSubmit', 'as' => 'cart.singleCheckoutSubmitGift']);

    Route::post('cart/cart-checkout', ['uses' => 'CartController@index', 'as' => 'cart.cartCheckOut']);
    Route::get('cart/cart-checkout', ['uses' => 'CartController@index', 'as' => 'cart.cartCheckOut']);
    Route::post('cart/add', ['uses' => 'CartController@addToCart', 'as' => 'cart.addToCart']);
    Route::get('cart', ['uses' => 'CartController@index', 'as' => 'cart.index']);
    Route::get('cart/clear', ['uses' => 'CartController@clear', 'as' => 'cart.clear']);
    Route::get('cart/remove', ['uses' => 'CartController@remove', 'as' => 'cart.remove']);
    Route::post('cart/apply-coupon',['uses' => 'CartController@applyCoupon','as'=>'cart.applyCoupon']);
    Route::post('cart/remove-coupon',['uses' => 'CartController@removeCoupon','as'=>'cart.removeCoupon']);
    Route::post('cart/stripe-payment', ['uses' => 'CartController@stripePayment', 'as' => 'cart.stripe.payment']);
    Route::post('cart/paypal-payment', ['uses' => 'CartController@paypalPayment', 'as' => 'cart.paypal.payment']);
    Route::get('cart/paypal-payment/status', ['uses' => 'CartController@getPaymentStatus'])->name('cart.paypal.status');

    //gifts
    Route::get('cart/gift-paypal-payment/status', ['uses' => 'CartController@getGiftsPaymentStatus'])->name('cart.gifts.paypal.status');

    Route::get('status', ['uses' => 'CartController@status'])->name('status');

//    Route::get('status', function () {
//        return view('frontend.cart.status');
//    })->name('status');

//    Route::get('pdf/raw', function () {
//        return view('pdf.index');
//    })->name('status');
    Route::post('pdf', 'Frontend\HomeController@generatePdf')->name('generate.pdf');

    Route::post('cart/offline-payment', ['uses' => 'CartController@offlinePayment', 'as' => 'cart.offline.payment']);
    Route::post('cart/getnow',['uses'=>'CartController@getNow','as' =>'cart.getnow']);
});

//============= Menu  Manager Routes ===============//
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => config('menu.middleware')], function () {
    //Route::get('wmenuindex', array('uses'=>'\Harimayco\Menu\Controllers\MenuController@wmenuindex'));
    Route::post('add-custom-menu', 'MenuController@addcustommenu')->name('haddcustommenu');
    Route::post('delete-item-menu', 'MenuController@deleteitemmenu')->name('hdeleteitemmenu');
    Route::post('delete-menug', 'MenuController@deletemenug')->name('hdeletemenug');
    Route::post('create-new-menu', 'MenuController@createnewmenu')->name('hcreatenewmenu');
    Route::post('generate-menu-control', 'MenuController@generatemenucontrol')->name('hgeneratemenucontrol');
    Route::post('update-item', 'MenuController@updateitem')->name('hupdateitem');
    Route::post('save-custom-menu', 'MenuController@saveCustomMenu')->name('hcustomitem');
    Route::post('change-location', 'MenuController@updateLocation')->name('update-location');
});

Route::get('certificate-verification','Backend\CertificateController@getVerificationForm')->name('frontend.certificates.getVerificationForm');
Route::post('certificate-verification','Backend\CertificateController@verifyCertificate')->name('frontend.certificates.verify');
Route::get('certificates/download', ['uses' => 'Backend\CertificateController@download', 'as' => 'certificates.download']);

Route::post('facebook/delete-data', 'Frontend\HomeController@facebookDeleteData')->name('delete-data');

Route::get('facebook/deletion/{id}', function ($id) {
    return $id.' Not Found';
});

if(config('show_offers') == 1){
    Route::get('offers',['uses' => 'CartController@getOffers', 'as' => 'frontend.offers']);
}

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::get('/{page?}', [HomeController::class, 'index'])->name('index');
});
