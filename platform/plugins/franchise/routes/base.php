<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Franchise\Http\Controllers\VendorBlockedController;
use Botble\Franchise\Http\Controllers\WithdrawalInvoiceController;
use Illuminate\Support\Facades\Route;
use Botble\Franchise\Http\Controllers\FranchiseController;


AdminHelper::registerRoutes(function () {
    Route::group(['namespace' => 'Botble\Franchise\Http\Controllers'], function () {
        
        Route::group(['prefix' => 'franchise', 'as' => 'franchise.'], function () {
            Route::resource('', 'FranchiseController')->parameters(['' => 'franchise']);

          
             
           Route::get('{id}', [
            'as' => 'show',
            'uses' => 'FranchiseController@show',
            'permission' => 'franchise.index',
        ]);
        });
       
      Route::put('franchise/{id}/update-status', [FranchiseController::class, 'updateStatus'])->name('franchise.update-status');
     
        Route::group(['prefix' => 'franchise-services', 'as' => 'franchise_services.'], function () {
            Route::resource('', 'FranchiseServiceController')->parameters(['' => 'franchise_services']);
        });
         

            // Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
            //     Route::resource('', 'Customers\FranchiseCustomerController')->parameters(['' => 'customers']);

            //     Route::group(
            //         ['prefix' => 'addresses', 'as' => 'addresses.', 'permission' => 'customers.edit'],
            //         function () {
            //             Route::resource('', 'AddressController')->parameters(['' => 'address'])->except(['index']);
            //         }
            //     );

            //     Route::get('get-list-customers-for-select', [
            //         'as' => 'get-list-customers-for-select',
            //         'uses' => 'Customers\FranchiseCustomerController@getListCustomerForSelect',
            //         'permission' => 'customers.index',
            //     ]);

            //     Route::get('get-list-customers-for-search', [
            //         'as' => 'get-list-customers-for-search',
            //         'uses' => 'Customers\FranchiseCustomerController@getListCustomerForSearch',
            //         'permission' => ['customers.index', 'orders.index'],
            //     ]);

            //     Route::post('update-email/{id}', [
            //         'as' => 'update-email',
            //         'uses' => 'Customers\FranchiseCustomerController@postUpdateEmail',
            //         'permission' => 'customers.edit',
            //     ])->wherePrimaryKey();

            //     Route::get('get-customer-addresses/{id}', [
            //         'as' => 'get-customer-addresses',
            //         'uses' => 'Customers\FranchiseCustomerController@getCustomerAddresses',
            //         'permission' => ['customers.index', 'orders.index'],
            //     ])->wherePrimaryKey();

            //     Route::get('get-customer-order-numbers/{id}', [
            //         'as' => 'get-customer-order-numbers',
            //         'uses' => 'Customers\FranchiseCustomerController@getCustomerOrderNumbers',
            //         'permission' => ['customers.index', 'orders.index'],
            //     ])->wherePrimaryKey();

            //     Route::post('create-customer-when-creating-order', [
            //         'as' => 'create-customer-when-creating-order',
            //         'uses' => 'Customers\FranchiseCustomerController@postCreateCustomerWhenCreatingOrder',
            //         'permission' => ['customers.index', 'orders.index'],
            //     ]);

            //     Route::post('verify-email/{id}', [
            //         'as' => 'verify-email',
            //         'uses' => 'Customers\FranchiseCustomerController@verifyEmail',
            //         'permission' => 'customers.index',
            //     ])->wherePrimaryKey();

            //     Route::post('reviews/{id}', [
            //         'as' => 'ajax.reviews',
            //         'uses' => 'Customers\FranchiseCustomerController@ajaxReviews',
            //         'permission' => 'customers.edit',
            //     ])->wherePrimaryKey();
            // });

            // Route::get('settings', [
            //     'as' => 'settings',
            //     'uses' => 'Settings\FranchiseSettingController@edit',
            // ]);

            // Route::put('settings', [
            //     'as' => 'settings.update',
            //     'uses' => 'Settings\FranchiseSettingController@update',
            //     'permission' => 'franchise.settings',
            // ]);

            // Route::group(['prefix' => 'unverified-vendors', 'as' => 'unverified-vendors.'], function () {
            //     Route::match(['GET', 'POST'], '/', [
            //         'as' => 'index',
            //         'uses' => 'UnverifiedVendorController@index',
            //     ]);

            //     Route::get('view/{id}', [
            //         'as' => 'view',
            //         'uses' => 'UnverifiedVendorController@view',
            //         'permission' => 'franchise.unverified-vendors.edit',
            //     ])->wherePrimaryKey();

            //     Route::post('approve/{id}', [
            //         'as' => 'approve-vendor',
            //         'uses' => 'UnverifiedVendorController@approveVendor',
            //         'permission' => 'franchise.unverified-vendors.edit',
            //     ])->wherePrimaryKey();

            //     Route::post('reject/{id}', [
            //         'as' => 'reject-vendor',
            //         'uses' => 'UnverifiedVendorController@rejectVendor',
            //         'permission' => 'franchise.unverified-vendors.edit',
            //     ])->wherePrimaryKey();

            //     Route::get('download-certificate/{id}', [
            //         'as' => 'download-certificate',
            //         'uses' => 'UnverifiedVendorController@downloadCertificate',
            //         'permission' => 'franchise.unverified-vendors.edit',
            //     ])->wherePrimaryKey();

            //     Route::get('download-government-id/{id}', [
            //         'as' => 'download-government-id',
            //         'uses' => 'UnverifiedVendorController@downloadGovernmentId',
            //         'permission' => 'franchise.unverified-vendors.edit',
            //     ])->wherePrimaryKey();
            // });

            // Route::group(['prefix' => 'vendors', 'as' => 'vendors.'], function () {
            //     Route::match(['GET', 'POST'], '/', [
            //         'as' => 'index',
            //         'uses' => 'VendorController@index',
            //     ]);

            //     Route::group(['permission' => 'franchise.vendors.control'], function () {
            //         Route::post('block/{id}', [VendorBlockedController::class, 'store'])->name('block');
            //         Route::post('unblock/{id}', [VendorBlockedController::class, 'destroy'])->name('unblock');
            //     });
            // });

            // Route::group([
            //     'prefix' => 'reports',
            //     'as' => 'reports.',
            //     'permission' => 'franchise.reports',
            //     'controller' => 'ReportController',
            // ], function () {
            //     Route::get('', [
            //         'as' => 'index',
            //         'uses' => 'index',
            //     ]);

            //     Route::post('store-revenues', [
            //         'as' => 'store-revenues',
            //         'uses' => 'getStoreRevenues',
            //     ]);
            // });
        // });

        // Route::group(['prefix' => 'ecommerce/products', 'as' => 'products.'], function () {
        //     Route::post('approve-product/{id}', [
        //         'as' => 'approve-product',
        //         'uses' => 'ProductController@approveProduct',
        //         'permission' => 'products.edit',
        //     ])->wherePrimaryKey();
        // });
    });
});
