<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\ApiAuthController;
use App\Http\Controllers\Api\V1\Admin\ApiProductController;
use App\Http\Controllers\Api\V1\Admin\ApiCartController;
use App\Http\Controllers\Api\V1\Admin\ApiAddressController;
use App\Http\Controllers\Api\V1\Admin\ApiOrderController;
use App\Http\Controllers\Api\V1\Admin\ApiFranchiseController;
use App\Http\Controllers\Api\V1\Admin\ApiCourseController;
use App\Http\Controllers\Api\V1\Admin\ApiMasterController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {
    Route::get('/welcome', function(){
        return response()->json(['message' => 'Welcome to our API']);
    });

    //Login & register, 
    Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
    Route::post('/sign-up', [ApiAuthController::class, 'userRegister'])->name('userRegister.api');
    Route::post('/otp-send', [ApiAuthController::class, 'otpSend'])->name('otpSend.api');
});

//Protected Routes
// Route::group(['prefix' => 'v1', 'as' => 'api.', 'middleware' => ['jwt.verify']], function () {
Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {
    
    //Master Controller
    Route::get('/home-slider', [ApiMasterController::class, 'homeSlider'])->name('home-slider.api');
    Route::get('/draw-menu', [ApiMasterController::class, 'drawMenu'])->name('draw-menu.api');

    Route::get('/profile-list', [ApiAuthController::class, 'userProfileList'])->name('profile-list.api');
    Route::post('/profile-update', [ApiAuthController::class, 'profileUpdate'])->name('profile-update.api');

    //Product Api Routes 
    Route::get('/products-search', [ApiProductController::class, 'ajaxSearchProducts'])->name('ajaxSearchProducts.api');
    Route::get('/remedies', [ApiProductController::class, 'remedies'])->name('remedies.api');
    Route::get('/remedies-by-product', [ApiProductController::class, 'remediesByProduct'])->name('remediesByProduct.api');
    Route::get('/products-collection', [ApiProductController::class, 'fetchProductCollection'])->name('fetchProductCollection.api');
     
    Route::get('/product-brand', [ApiProductController::class, 'productBrand'])->name('product-brand.api');
    Route::get('/brand-by-product', [ApiProductController::class, 'brandByProduct'])->name('brandByProduct.api');
    
    Route::get('/products', [ApiProductController::class, 'fetchProduct'])->name('products.api');
   
    Route::get('/fetch-single-product', [ApiProductController::class, 'fetchSingleProduct'])->name('fetchSingleProduct.api');
    Route::get('/wishlist-product', [ApiProductController::class, 'fetchWishlistProduct'])->name('fetchWishlistProduct.api');
    Route::post('/wishlist-product-add', [ApiProductController::class, 'postWishlistProduct'])->name('postWishlistProduct.api');
 
    //Shipment 
    Route::get('/fetch-shipment-method', [ApiOrderController::class, 'getAvailableShippingMethods'])->name('getAvailableShippingMethods.api');
    Route::post('/create-shipment', [ApiOrderController::class, 'postCreateShipment'])->name('postCreateShipment.api');
    
    //Franchise Lists
    Route::get('/fetch-franchise-services', [ApiFranchiseController::class, 'fetchFranchiseServices'])->name('fetchFranchiseServices.api');
    Route::get('/fetch-courses', [ApiCourseController::class, 'fetchCourse'])->name('fetchCourse.api');
    Route::get('/fetch-courses-details', [ApiCourseController::class, 'fetchCourseDetails'])->name('fetchCourseDetails.api');
    
});

    Route::group(['prefix' => 'v1', 'as' => 'api.', 'middleware' => ['jwt.verify']], function () {
        //Cart Api
        Route::get('/cart', [ApiCartController::class, 'getCart'])->name('getCart.api');
        Route::get('/get-cart-data', [ApiCartController::class, 'getCartData'])->name('getCartData.api');
        Route::post('/add-to-cart', [ApiCartController::class, 'storeCart'])->name('storeCart.api');
        Route::post('/update-to-cart', [ApiCartController::class, 'updateCart'])->name('updateCart.api');
        Route::get('/remove-to cart', [ApiCartController::class, 'removeCartItem'])->name('removeCartItem.api');
        Route::get('/destroy-to-cart', [ApiCartController::class, 'destroyCart'])->name('destroyCart.api');

        //Customer Address
        Route::get('/fetch-customer-address', [ApiAddressController::class, 'fetchAddress'])->name('fetchAddress.api');
        Route::post('/create-customer-address', [ApiAddressController::class, 'storeCustomerAddress'])->name('storeCustomerAddress.api');
        Route::post('/update-customer-address', [ApiAddressController::class, 'updateCustomerAddress'])->name('updateCustomerAddress.api');
        Route::post('/delete-customer-address', [ApiAddressController::class, 'deleteCustomerAddress'])->name('deleteCustomerAddress.api');
        Route::get('/reviews-customer-product', [ApiAddressController::class, 'getProductReviews'])->name('getProductReviews.api');

        //Order 
        Route::get('/fetch-order', [ApiOrderController::class, 'fetchOrder'])->name('fetchOrder.api');
        Route::get('/fetch-order-details', [ApiOrderController::class, 'fetchOrderDetails'])->name('fetchOrderDetails.api');
        Route::post('/create-customer-order', [ApiOrderController::class, 'createOrder'])->name('createOrder.api');
        Route::post('/cancel-customer-order', [ApiOrderController::class, 'cancelOrder'])->name('cancelOrder.api');
    });