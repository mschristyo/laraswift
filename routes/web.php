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
// Application Backup-ROUTES
Route::post('/backup-file',[
  'uses' => 'Settings\SettingsController@backupFiles',
  'as' => 'backup-files'
]);

Route::post('/backup-db',[
  'uses' => 'Settings\SettingsController@backupDb',
  'as' => 'backup-db'
]);

Route::post('/backup-download/{name}/{ext}',[
  'uses' => 'Settings\SettingsController@downloadBackup',
  'as' => 'backup-download'
]);

Route::post('/backup-delete/{name}/{ext}',[
  'uses' => 'Settings\SettingsController@deleteBackup',
  'as' => 'backup-delete'
]);

Route::get('/logout', [
    'uses' => '\App\Http\Controllers\Auth\LoginController@logout'
]);

  Route::post('/verify-2fa',[
    'as' => 'verify-2fa',
    'uses' => 'Profile\ProfileController@verify'
  ]);

	Route::post('/activate-2fa',[
		'uses' => 'Profile\ProfileController@activate',
		'as' => 'activate-2fa'
	]);

	Route::post('/enable-2fa',[
		'uses' => 'Profile\ProfileController@enable',
		'as' => 'enable-2fa'
	]);

	Route::post('/disable-2fa',[
		'uses' => 'Profile\ProfileController@disable',
		'as' => 'disable-2fa'
	]);

	Route::get('/2fa/instruction',[
		'uses' => 'Profile\ProfileController@instruction',
	]);


	Route::get('/',[
		'as'=> 'home',
		'uses'=> 'Dashboard\DashboardController@index',
	])->middleware('auth');

  /*
  | Stripe Subscription Routes
  */
	Route::get('/subscription',[
		'as'=> '/subscription',
		'uses'=> 'SubscriptionController@index',
	]);

	Route::get('/subscription/subscribe',[
		'as'=> '/subscription/subscribe',
		'uses'=> 'SubscriptionController@notSubscribed',
	]);

	Route::get('/subscription/stripe/{plan_id}',[
		'as'=> '/subscription/stripe',
		'uses'=> 'SubscriptionController@stripeCheckout',
	]);

	Route::post('/subscription/stripe/subscribe',[
		'as'=> '/subscription/stripe/subscribe',
		'uses'=> 'SubscriptionController@stripeSubscribe',
	]);

  Route::get('/subscription-invoice/{invoiceId}',[
    'uses' => 'SubscriptionController@stripeInvoice',
  ]);

  Route::get('/subscription-cancel/{subscriptionId}',[
    'uses' => 'SubscriptionController@cancelSubscription',
  ]);
	/*
	| Stripe Subscription Routes
	*/

  /*
  | Premium Content Routes
  */
  Route::resource('/premium-content','PremiumContent\PremiumContentController')
  ->middleware('premium');
  /*
  | Premium Content Routes
  */

	/*
	|  Activitylog Route
	*/
	Route::resource('activity-log','Activitylog\ActivitylogController');
  /*
  |  Activitylog Route
  */


	/*
	| Profile Routes
	*/

  Route::resource('profile','Profile\ProfileController');

	Route::get('update-avatar/{id}',[
		'as' => 'update-avatar',
		'uses'=>'Profile\ProfileController@showAvatar'
	]);

	Route::post('update-avatar/{id}','Profile\ProfileController@updateAvatar');

	Route::post('update-profile-login/{id}',[
		'uses'=>'Profile\ProfileController@updateLogin',
		'as' => 'update-login',
	]);

/*
| Profile Routes
*/

// Socialite Authentication Route
Route::get('login/{provider}','Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

#####################################ADMIN MANAGED SECTION##########################################
// Users Route
	Route::resource('user','UserController');
	Route::post('update-user-login/{id}',[
    'as' => 'user-login',
	'uses'=>'UserController@userLogin']);
	Route::get('user/{id}/activity-log/',[
    'as' => 'user-activitlog',
	'uses'=>'UserController@userActivityLog']);
  // Users Route


// Roles Route
	Route::resource('role','Role\RoleController');
	Route::post('/role-permission/{id}',[
		'as' => 'roles_permit',
		'uses' => 'Role\RoleController@assignPermission',
	]);
// Roles Route


// Permission Route
	Route::resource('permission','Permission\PermissionController');
  // Permission Route


// Payment Gateway Route
          Route::get('/subscription/plan',[
              'as' => '/subscription/plan',
              'uses' => 'PaymentGateway\PaymentGatewayController@viewStripePlans',
          ]);

          Route::get('/subscription/plan/create',[
              'as' => '/subscription/plan/create',
              'uses' => 'PaymentGateway\PaymentGatewayController@createStripePlan',
          ]);

          Route::post('/subscription/plan/create',[
              'uses' => 'PaymentGateway\PaymentGatewayController@storeStripePlan',
          ]);

          Route::get('/stripe/plan/edit/{plan_id}',[
              'as' => '/stripe/plan/edit',
              'uses' => 'PaymentGateway\PaymentGatewayController@editStripePlan',
          ]);

          Route::post('/stripe/plan/edit/{id}/{plan_id}',[
              'uses' => 'PaymentGateway\PaymentGatewayController@updateStripePlan',
          ]);

          Route::post('/stripe/plan/delete/{id}',[
            'as' => '/stripe/plan/delete',
            'uses' => 'PaymentGateway\PaymentGatewayController@deleteStripePlan',
          ]);

          Route::get('/subscribed-users',[
            'uses' => 'PaymentGateway\PaymentGatewayController@manageSubscribedUser',
          ]);

          Route::get('/user-subscription-invoice/{invoiceId}',[
            'uses' => 'PaymentGateway\PaymentGatewayController@subscriptionInvoice',
          ]);

          Route::get('/user-subscription-cancel/{subscription_id}',[
            'uses' => 'PaymentGateway\PaymentGatewayController@cancelSub',
          ]);

          Route::get('/subscription-income',[
            'uses' => 'PaymentGateway\PaymentGatewayController@manageIncome',
          ]);

          Route::get('/checkout-sample',[
            'uses' => 'PaymentGateway\PaymentGatewayController@checkoutSamples',
          ]);

          Route::get('/checkout-sample/article',[
            'uses' => 'PaymentGateway\PaymentGatewayController@download',
          ]);

          Route::get('/checkout-sample/response/{session_id}',[
            'uses' => 'PaymentGateway\PaymentGatewayController@responseCheckout',
            'as' => '/checkout-sample/response'
          ]);

// Payment Gateway Route

      	Route::resource('settings','Settings\SettingsController');

      	Route::post('settings/app-name/update',[
      		'as' => 'settings/app-name/update',
      		'uses' => 'Settings\SettingsController@appNameUpdate',
      	]);
      	Route::post('settings/app-logo/update',[
      		'as' => 'settings/app-logo/update',
      		'uses' => 'Settings\SettingsController@appLogoUpdate',
      	]);

      	Route::post('settings/app-theme/update',[
      		'as' => 'settings/app-theme/update',
      		'uses' => 'Settings\SettingsController@appThemeUpdate',
      	]);

      	Route::post('settings/stripe-payment/update',[
      		'as' => 'settings/stripe-payment/update',
      		'uses' => 'Settings\SettingsController@stripePaymentUpdate',
      	]);

        Route::post('settings/auth-settings/update',[
      		'as' => 'settings/auth-settings/update',
      		'uses' => 'Settings\SettingsController@authSettingsUpdate',
      	]);

        // Premium Content
        Route::resource('/article','Article\ArticleController');
        Route::post('/article-image','Article\ArticleController@articleImageUpload');
        Route::resource('/category-article','Article\ArticleCategoryController');
#####################################ADMIN MANAGED SECTION##########################################

#####################################CRUD GENERATOR ROUTES##########################################
      Route::get('/crud-builder', [
        'as' => 'crud-builder',
        'uses' => '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@builder'
      ])->middleware('auth','web','role:admin','2fa');

      Route::get('/field-template',[
        'as' => 'field-template',
        'uses' => '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@fieldTemplate'
      ])->middleware('auth','web','role:admin','2fa');

      Route::post('/generator-builder/generate', [
        'as' => 'generator-builder/generate',
        'uses' => '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generate'
      ])->middleware('auth','web','role:admin','2fa');

      Route::post('/generator-builder/rollback', [
        'as' => 'generator-builder/rollback',
        'uses' => '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@rollback'
      ])->middleware('auth','web','role:admin','2fa');

      Route::post('/model-check', [
        'as' => 'model-check',
        'uses' => 'CRUDController@checkModel'
      ]);

#####################################CRUD GENERATOR ROUTES##########################################

#####################################WEBHOOK ROUTE##########################################
Route::stripeWebhooks('stripe-webhook');
#####################################WEBHOOK ROUTE##########################################

Route::impersonate();
Auth::routes(['verify' => true]);
