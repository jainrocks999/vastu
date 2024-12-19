<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Orders\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
            Route::resource('', 'OrderController')->parameters(['' => 'order']);

            Route::get('reorder', [
                'as' => 'reorder',
                'uses' => 'OrderController@getReorder',
                'permission' => 'orders.create',
            ]);

            Route::get('generate-invoice/{order}', [
                'as' => 'generate-invoice',
                'uses' => 'OrderController@getGenerateInvoice',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('confirm', [
                'as' => 'confirm',
                'uses' => 'OrderController@postConfirm',
                'permission' => 'orders.edit',
            ]);

            Route::post('send-order-confirmation-email/{order}', [
                'as' => 'send-order-confirmation-email',
                'uses' => 'OrderController@postResendOrderConfirmationEmail',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('create-shipment/{order}', [
                'as' => 'create-shipment',
                'uses' => 'OrderController@postCreateShipment',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('cancel-shipment/{shipment}', [
                'as' => 'cancel-shipment',
                'uses' => 'OrderController@postCancelShipment',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('update-shipping-address/{address}', [
                'as' => 'update-shipping-address',
                'uses' => 'OrderController@postUpdateShippingAddress',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('update-tax-information/{taxInformation}', [
                'as' => 'update-tax-information',
                'uses' => 'OrderController@postUpdateTaxInformation',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('cancel-order/{order}', [
                'as' => 'cancel',
                'uses' => 'OrderController@postCancelOrder',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::get('print-shipping-order/{order}', [
                'as' => 'print-shipping-order',
                'uses' => 'OrderController@getPrintShippingOrder',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('confirm-payment/{order}', [
                'as' => 'confirm-payment',
                'uses' => 'OrderController@postConfirmPayment',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::get('get-shipment-form/{order}', [
                'as' => 'get-shipment-form',
                'uses' => 'OrderController@getShipmentForm',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('refund/{order}', [
                'as' => 'refund',
                'uses' => 'OrderController@postRefund',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::get('get-available-shipping-methods', [
                'as' => 'get-available-shipping-methods',
                'uses' => 'OrderController@getAvailableShippingMethods',
                'permission' => 'orders.edit',
            ]);

            Route::post('coupon/apply', [
                'as' => 'apply-coupon-when-creating-order',
                'uses' => 'OrderController@postApplyCoupon',
                'permission' => 'orders.create',
            ]);

            Route::post('check-data-before-create-order', [
                'as' => 'check-data-before-create-order',
                'uses' => 'OrderController@checkDataBeforeCreateOrder',
                'permission' => 'orders.create',
            ]);

            Route::get('orders/{order}/generate', [
                'as' => 'invoice.generate',
                'uses' => 'OrderController@generateInvoice',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey('order');

            Route::get('orders/{order}/download-proof', [
                'as' => 'download-proof',
                'uses' => 'OrderController@downloadProof',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey('order');
        });

    
        //Course Order
        Route::group(['prefix' => 'orders-courses-order', 'as' => 'orders.course_order.'], function () {
            Route::resource('', 'CourseOrderController')->parameters(['' => 'course_order']);

            //Corse Details
            Route::get('discount-coupon-code', [
                'as' => 'discount-coupon-code',
                'uses' => 'CourseOrderController@checkCouponCode',
                'permission' => 'course.index',
            ]);

            //Corse Details
            Route::get('get-course-details', [
                'as' => 'get-course-details',
                'uses' => 'CourseOrderController@course_details',
                'permission' => 'course.index',
            ]);


            //Corse Details
            Route::get('get-course-order-details', [
                'as' => 'get-course-order-details',
                'uses' => 'CourseOrderController@course_order_details',
                'permission' => 'course.index',
            ]);

        });

        //Franchise
        Route::group(['prefix' => 'orders-franchise', 'as' => 'orders.franchise.'], function () {
            Route::resource('', 'FranchiseController')->parameters(['' => 'franchise']);
        });

        //Invoices 
        Route::group(['prefix' => 'orders-invoices', 'as' => 'orders.invoice.'], function () {
         
            Route::resource('', 'InvoiceController')->parameters(['' => 'invoice']);

            Route::get('generate-invoice/{invoice}', [
                'as' => 'generate-invoice',
                'uses' => 'InvoiceController@getGenerateInvoice',
                'permission' => 'orders.invoice.edit',
            ])->wherePrimaryKey('invoice');

            Route::get('generate-invoices', [
                'as' => 'generate-invoices',
                'uses' => 'InvoiceController@generateInvoices',
                'permission' => 'orders.invoice.edit',
            ]);
        });

        //Shipment
        Route::group(['prefix' => 'order.shipments', 'as' => 'orders.shipments.'], function () {
            Route::resource('', 'ShipmentController')
                ->parameters(['' => 'shipment'])
                ->except(['create', 'store']);

            Route::group(['permission' => 'ecommerce.shipments.edit'], function () {
                Route::get('shipments/{shipment}/print', [PrintShippingLabelController::class, '__invoke'])
                    ->name('print');

                Route::post('update-status/{shipment}', [
                    'as' => 'update-status',
                    'uses' => 'ShipmentController@postUpdateStatus',
                ])->wherePrimaryKey();

                Route::post('update-cod-status/{shipment}', [   
                    'as' => 'update-cod-status',
                    'uses' => 'ShipmentController@postUpdateCodStatus',
                ])->wherePrimaryKey();
            });
        });


        //Customers
        // Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
        //     Route::resource('', 'CustomerController')->parameters(['' => 'customer']);
        //     // Route::post('reviews/{id}', [
        //     //     'as' => 'ajax.reviews',
        //     //     'uses' => 'CustomerController@ajaxReviews',
        //     //     'permission' => 'customers.edit',
        //     // ])->wherePrimaryKey();
        // });
        // Route::get('settings', [
        //     'as' => 'settings',
        //     'uses' => 'Settings\OrdersSettingController@edit',
        // ]);

        // Route::put('settings', [
        //     'as' => 'settings.update',
        //     'uses' => 'Settings\OrdersSettingController@update',
        //     'permission' => 'orders.settings',
        // ]);
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Theme::registerRoutes(function () {
            Route::get('orders-click/{key}', [
                'as' => 'public.orders-click',
                'uses' => 'PublicController@getOrdersClick',
            ]);

            Route::get('ac-{randomHash}/{ordersKey}', [
                'as' => 'public.orders-click.alternative',
                'uses' => 'PublicController@getOrdersClickAlternative',
            ]);

            Route::get('ac-{randomHash}/{ordersKey}/{size}/{hashName}.jpg', [
                'as' => 'public.orders-click.image',
                'uses' => 'PublicController@getOrdersImage',
            ]);
        });
    }
});
