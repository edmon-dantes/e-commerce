<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['middleware' => ['jwt.always.token'], 'prefix' => 'auth'], function () {


/**
 * ADMIN
 * =================================================================
 */
Route::group(['prefix' => 'admin'], function () {
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/auth/signup', [AuthController::class, 'signup'])->name('auth.signup');
    Route::get('/auth/confirm-signup', [AuthController::class, 'confirmSignup'])->name('auth.confirm-signup');
    Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::delete('/cart_items/all', [CartController::class, 'destroy_all'])->name('cart_items.destroy.all');
    Route::resource('/cart_items', CartController::class, [])->names('cart_items')->only(['index', 'store', 'update', 'destroy']);

    Route::group(['middleware' => ['jwt.auth']], function ($router) {
        Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me'); // ->middleware(['permission:users.show']);
        Route::put('/auth/update-me', [AuthController::class, 'updateMe'])->name('auth.update-me'); // ->middleware(['permission:users.edit']);

        Route::delete('/roles/multiple', [RoleController::class, 'destroy_multiple'])->name('roles.destroy.multiple'); // ->middleware(['permission:roles.destroy.multiple']);
        Route::resource('/roles', RoleController::class)->names('roles'); // ->middleware(['permission:roles.index|roles.create|roles.show|roles.edit|roles.destroy']);

        Route::delete('/permissions/multiple', [PermissionController::class, 'destroy_multiple'])->name('permissions.destroy.multiple'); // ->middleware(['permission:permissions.destroy.multiple']);
        Route::resource('/permissions', PermissionController::class)->names('permissions'); // ->middleware(['permission:permissions.index|permissions.create|permissions.show|permissions.edit|permissions.destroy']);

        Route::delete('/users/multiple', [UserController::class, 'destroy_multiple'])->name('users.destroy.multiple'); // ->middleware(['permission:users.destroy.multiple']);
        Route::resource('/users', UserController::class)->names('users'); // ->middleware(['permission:users.index|users.create|users.show|users.edit|users.destroy']);

        Route::delete('/banners/multiple', [BannerController::class, 'destroy_multiple'])->name('banners.destroy.multiple'); // ->middleware(['permission:banners.destroy.multiple']);
        Route::resource('/banners', BannerController::class)->names('banners'); // ->middleware(['permission:banners.index|banners.create|banners.show|banners.edit|banners.destroy']); // ->only(['index', ''])

        Route::delete('/sections/multiple', [SectionController::class, 'destroy_multiple'])->name('sections.destroy.multiple'); // ->middleware(['permission:sections.destroy.multiple']);
        Route::resource('/sections', SectionController::class)->names('sections'); // ->middleware(['permission:sections.index|sections.create|sections.show|sections.edit|sections.destroy']);

        Route::delete('/categories/multiple', [CategoryController::class, 'destroy_multiple'])->name('categories.destroy.multiple'); // ->middleware(['permission:categories.destroy.multiple']);
        Route::resource('/categories', CategoryController::class)->names('categories'); // ->middleware(['permission:categories.index|categories.create|categories.show|categories.edit|categories.destroy']);

        Route::delete('/brands/multiple', [BrandController::class, 'destroy_multiple'])->name('brands.destroy.multiple'); // ->middleware(['permission:brands.destroy.multiple']);
        Route::resource('/brands', BrandController::class)->names('brands'); // ->middleware(['permission:brands.index|brands.create|brands.show|brands.edit|brands.destroy']);

        Route::delete('/products/multiple', [ProductController::class, 'destroy_multiple'])->name('products.destroy.multiple'); // ->middleware(['permission:products.destroy.multiple']);
        Route::resource('/products', ProductController::class)->names('products'); // ->middleware(['permission:products.index|products.create|products.show|products.edit|products.destroy']);
    });
});








/**
 * CLIENT
 * =================================================================
 */
// Route::group(['prefix' => 'client'], function () {

//     Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
//     Route::post('/auth/signup', [AuthController::class, 'signup'])->name('auth.signup');
//     Route::get('/auth/confirm-signup', [AuthController::class, 'confirmSignup'])->name('auth.confirm-signup');
//     Route::group(['middleware' => ['jwt.auth']], function ($router) {
//         Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me')->middleware(['permission:users.show']);
//         Route::put('/auth/update-me', [AuthController::class, 'updateMe'])->name('auth.update-me')->middleware(['permission:users.edit']);
//     });

//     Route::resource('/sections', SectionController::class)->only(['index']);

//     Route::resource('/categories', CategoryController::class)->only(['index']);

//     Route::resource('/brands', BrandController::class)->only(['index']);

//     Route::resource('/products', ProductController::class)->only(['index', 'show']);

    // Route::resource('/cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);
// });










/*
Route::group(['middleware' => [], 'prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/signup', [AuthController::class, 'signup'])->name('auth.signup');
    Route::get('/confirm-signup', [AuthController::class, 'confirmSignup'])->name('auth.confirm-signup');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot-password');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');
    // Route::get('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh')->middleware(['jwt.refresh3']);
    Route::get('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');

    Route::group(['middleware' => ['jwt.auth']], function ($router) {
        Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::put('/update-me', [AuthController::class, 'updateMe'])->name('auth.update-me');

        Route::post('/users/multiple', [UserController::class, 'store_multiple'])->name('users.store.multiple')->middleware(['permission:users.store.multiple']);
        Route::put('/users/multiple', [UserController::class, 'update_multiple'])->name('users.update.multiple')->middleware(['permission:users.update.multiple']);
        Route::delete('/users/multiple', [UserController::class, 'destroy_multiple'])->name('users.destroy.multiple')->middleware(['permission:users.destroy.multiple']);
        Route::resource('/users', UserController::class)->middleware(['permission:users.index|users.create|users.show|users.edit|users.destroy']);

        Route::delete('/sections/multiple', [SectionController::class, 'destroy_multiple'])->name('sections.destroy.multiple')->middleware(['permission:sections.destroy.multiple']);
        Route::resource('/sections', SectionController::class)->middleware(['permission:sections.index|sections.create|sections.show|sections.edit|sections.destroy']);

        Route::delete('/categories/multiple', [CategoryController::class, 'destroy_multiple'])->name('categories.destroy.multiple')->middleware(['permission:categories.destroy.multiple']);
        Route::resource('/categories', CategoryController::class)->middleware(['permission:categories.index|categories.create|categories.show|categories.edit|categories.destroy']);

        Route::delete('/products/multiple', [ProductController::class, 'destroy_multiple'])->name('products.destroy.multiple')->middleware(['permission:products.destroy.multiple']);
        Route::resource('/products', ProductController::class)->middleware(['permission:products.index|products.create|products.show|products.edit|products.destroy']);

        Route::delete('/brands/multiple', [BrandController::class, 'destroy_multiple'])->name('brands.destroy.multiple')->middleware(['permission:brands.destroy.multiple']);
        Route::resource('/brands', BrandController::class)->middleware(['permission:brands.index|brands.create|brands.show|brands.edit|brands.destroy']);

        Route::delete('/banners/multiple', [BannerController::class, 'destroy_multiple'])->name('banners.destroy.multiple')->middleware(['permission:banners.destroy.multiple']);
        Route::resource('/banners', BannerController::class)->middleware(['permission:banners.index|banners.create|banners.show|banners.edit|banners.destroy']);

        Route::resource('/roles', RoleController::class)->middleware(['permission:roles.index|roles.create|roles.store|roles.show|roles.edit|roles.update|roles.destroy']);

        Route::resource('/permissions', PermissionController::class)->middleware(['permission:permissions.index|permissions.create|permissions.store|permissions.show|permissions.edit|permissions.update|permissions.destroy']);
    });

    // Route::resource('/shops', ShopController::class)->middleware(['permission:shops.index|shops.create|shops.store|shops.show|shops.edit|shops.update|shops.destroy']);

    Route::resource('/orders', OrderController::class);

    Route::get('/paypal/execute/{order}', [PaypalController::class, 'execute'])->name('paypal.execute');
    Route::get('/paypal/cancel/{order}', [PaypalController::class, 'cancel'])->name('paypal.cancel');
    // Route::get('/paypal/checkout/{order}', [PaypalController::class, 'getExpressCheckout'])->name('paypal.checkout');
    // Route::get('/paypal/checkout-success/{order}', [PaypalController::class, 'getExpressCheckoutSuccess'])->name('paypal.success');
    // Route::get('/paypal/checkout-cancel/{order}', [PaypalController::class, 'getExpressCheckoutCancel'])->name('paypal.cancel');

    Route::post('/culqui/checkout', [CulquiController::class, 'checkout'])->name('culqui.checkout');
});
*/

// Route::group(['middleware' => ['only_auth'], 'prefix' => 'seller',], function () {
// Route::resource('/orders', OrderController::class);
// Route::post('/orders/delivered/{order}', [OrderController::class, 'markDelivered'])->name('order.delivered');
// });
