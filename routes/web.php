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

//Route::get('/', function () {
//    return view('index');
//});
//
//Route::get('/frontend_pricing.php', function() {
//    return view('frontend_pricing');
//});
//
//Route::get('/bd_dashboard', function() {
//    return view('bd_dashboard');
//});

Auth::routes();
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/interactions', 'InteractionsController@index');

Route::get('/interactions/{id}', 'InteractionsController@edit');
Route::post('/interactions/like/{id}', 'InteractionsController@toggleLike');
Route::post('/interactions/comment/{id}', 'InteractionsController@toggleComment');
Route::post('/interactions/follow/{id}', 'InteractionsController@toggleFollow');
Route::post('/interactions/unfollow/{id}', 'InteractionsController@toggleUnfollow');
Route::post('/interactions/niche/{id}', 'InteractionsController@toggleNiche');

Route::post('/interactions/add/comment/{id}', 'InteractionsController@saveComment');
Route::post('/interactions/add/username/{id}', 'InteractionsController@saveUsername');
Route::post('/interactions/add/hashtag/{id}', 'InteractionsController@saveHashtag');

Route::post('/interactions/delete/comment/{id}', 'InteractionsController@deleteComment');
Route::post('/interactions/delete/username/{id}', 'InteractionsController@deleteTargetUsername');
Route::post('/interactions/delete/hashtag/{id}', 'InteractionsController@deleteTargetHashtag');

Route::post('/interactions/save/advancedfollowsettings/{id}', 'InteractionsController@saveAdvancedFollowSettings');

Route::post('/instagram-profile/add', 'InstagramProfileController@store');

Route::post('/profile/ig/add', 'InstagramProfileController@create');
Route::post('/profile/ig/remove/{id}', 'InstagramProfileController@delete');
Route::post('/profile/ig/checkpoint', 'InstagramProfileController@clearCheckpoint');
Route::post('/profile/ig/changepassword', 'InstagramProfileController@changePassword');

Route::get('/dm', 'DirectMessageController@index');


Route::post('/dm/thread/create', 'DirectThreadController@create');
Route::post('/dm/thread/retrieve', 'DirectThreadController@retrieve');
Route::post('/dm/thread/update', 'DirectThreadController@update');
Route::post('/dm/thread/delete', 'DirectThreadController@delete');
Route::get('/dm/thread/test', 'DirectThreadController@test');

Route::get('/dm/templates/{id}', 'DirectMessageTemplatesController@index');

Route::post('/dm/templates/save/greeting/{id}', 'DirectMessageTemplatesController@saveGreetingTemplate');
Route::post('/dm/templates/save/followup/{id}', 'DirectMessageTemplatesController@saveFollowupTemplate');
Route::post('/dm/templates/save/followupdelay/{id}', 'DirectMessageTemplatesController@toggleAutoDmDelay');
Route::post('/dm/templates/save/autodm/{id}', 'DirectMessageTemplatesController@toggleAutoDm');

Route::post('/legacy/instagram-profile/add', 'LegacyInstagramProfileController@create');

Route::get('/post-scheduling', 'PostSchedulingController@index');

Route::get('/post-scheduling/schedule/{id}', 'PostSchedulingController@gallery');
Route::post('/post-scheduling/schedule/{id}', 'PostSchedulingController@schedule');

Route::post('/post-scheduling/add', 'PostSchedulingController@add');
Route::post('/post-scheduling/delete', 'PostSchedulingController@delete');
Route::post('/post-scheduling/get/{id}', 'PostSchedulingController@get');

Route::post('stripe/webhook','WebhookController@handleWebhook');
Route::get('/affiliate', 'AffiliateController@index');
Route::post('/affiliate/save/paypal/{id}', 'AffiliateController@savePaypalEmail');
Route::post('/affiliate/save/pixel', 'AffiliateController@savePixel');

Route::post('/upgrade/{plan}', 'PaymentController@upgrade');
Route::get('/payment', 'PaymentController@index');

Route::get('/training/{type}', 'TrainingVideoController@index');
Route::get('/engagement-group', 'EngagementGroupController@index');
Route::get('/engagement-group/{id}', 'EngagementGroupController@profile');
Route::post('engagement-group/schedule/{media_id}', 'EngagementGroupController@schedule');

Route::get('/settings', 'SettingsController@index');
Route::post('/settings/subscription/cancel/{subscription_id}', 'SettingsController@cancelSubscription');
Route::post('/settings/invoice/pay/{invoice_id}', 'SettingsController@attemptInvoice');

Route::post('/settings/cards/update', 'SettingsController@updateCreditCard');

#Route::get('/faq', 'FaqController@index');
Route::get('/faq', 'FaqController@topic');
Route::get('/faq/{topic}', 'FaqController@listQuestions');

Route::get('/post-scheduling/logs/{id}', 'PostSchedulingController@log');

Route::get('/dm/logs/{id}', 'DirectMessageLogsController@index');
Route::post('/dm/logs/cancel/{id}', 'DirectMessageLogsController@cancel');
Route::post('/dm/logs/clear/{insta_id}', 'DirectMessageLogsController@cancelAllPendingJobs');

Route::post('/webhooks/paypal', 'PaypalWebhookController@listen');

#Route::get('/subscribe/paypal', 'PaypalController@paypalRedirect');
Route::get('/subscribe/paypal/premium', 'PaypalController@paypalRedirectPremium');
Route::get('/subscribe/paypal/pro', 'PaypalController@paypalRedirectPro');
Route::get('/subscribe/paypal/business', 'PaypalController@paypalRedirectBusiness');
Route::get('/subscribe/paypal/mastermind', 'PaypalController@paypalRedirectMastermind');


#Route::get('/subscribe/paypal/return', 'PaypalController@paypalReturn');
Route::get('/subscribe/paypal/return/premium', 'PaypalController@paypalReturnPremium');
Route::get('/subscribe/paypal/return/pro', 'PaypalController@paypalReturnPro');
Route::get('/subscribe/paypal/return/business', 'PaypalController@paypalReturnBusiness');
Route::get('/subscribe/paypal/return/mastermind', 'PaypalController@paypalReturnMastermind');

Route::get('/new/subscribe/paypal/return/premium', 'PaymentController@paypalReturnPremium');
Route::get('/new/subscribe/paypal/return/pro', 'PaymentController@paypalReturnPro');

Route::get('/vsl/signup', 'ReferrerController@redirect');
Route::post('/vsl/signup/cc', 'PaymentController@processCreditCardPayment');
Route::post('/vsl/signup/paypal', 'PaymentController@processPaypalPayment');

Route::get('/welcome', 'OptInController@optin');

Route::post('/funnel_webhooks/test', 'FunnelWebhookController@test');
Route::post('/clickfunnel/contact/created', 'FunnelWebhookController@contactCreated');
Route::post('/clickfunnel/contact/updated', 'FunnelWebhookController@contactUpdated');
Route::post('/clickfunnel/purchase/created', 'FunnelWebhookController@purchaseCreated');
Route::post('/clickfunnel/purchase/updated', 'FunnelWebhookController@purchaseUpdated');

Route::post('/clickfunnel/freetrial/new', 'FunnelWebhookController@freeTrialCustomerCreated');

Route::post('/clickfunnel/sales/new', 'FunnelWebhookController@salesCustomerCreated');
Route::post('/clickfunnel/sales/purchase', 'FunnelWebhookController@salesNewPurchase');

Route::get('/admin', 'AdminController@index');
Route::post('/admin/upgrade', 'AdminController@upgradeUserTier');
Route::post('/admin/getstripedetails', 'AdminController@getStripeDetails');
Route::post('/admin/interaction/like', 'AdminController@runInteractionLike');
Route::post('/admin/interaction/comment', 'AdminController@runInteractionComment');

Route::post('/stripe/charge/refunded', 'StripeWebhookController@chargeRefunded');
Route::post('/stripe/invoice/paymentfailed', 'StripeWebhookController@invoicePaymentFailed');

Route::get('/dm/thread', 'DirectThreadController@index');
Route::get('/dm/thread/{id}', 'DirectThreadController@show');

Route::post('/paypal/subscription/cancel', 'SettingsController@cancelPaypalAgreement');

Route::get('/funnels/ebook', 'FunnelsController@show');
Route::get('/funnels/test', 'FunnelsController@test');

Route::get('/braintree', 'BraintreeController@payment');

/**
 * Routes for creation of Paypal Subscription plans.
 */
#Route::get('/paypal/plan/create/premium', 'PaypalController@create_plan_premium');
#Route::get('/paypal/plan/create/pro', 'PaypalController@create_plan_pro');
#Route::get('/paypal/plan/create/business', 'PaypalController@create_plan_business');
#Route::get('/paypal/plan/create/mastermind', 'PaypalController@create_plan_mastermind');
#Route::get('/paypal/plan/list', 'PaypalController@listPlans');
