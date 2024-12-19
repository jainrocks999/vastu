<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Course\Facades\CourseHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(['namespace' => 'Botble\Course\Http\Controllers', 'prefix' => 'course'], function () {
        Route::group(['prefix' => 'course-orders', 'as' => 'course_order.'], function () {
            Route::resource('', 'CourseOrderController')->parameters(['' => 'order']);

            Route::get('reorder', [
                'as' => 'reorder',
                'uses' => 'CourseOrderController@getReorder',
                'permission' => 'orders.create',
            ]);

            Route::get('generate-invoice/{order}', [
                'as' => 'generate-invoice',
                'uses' => 'CourseOrderController@getGenerateInvoice',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('confirm', [
                'as' => 'confirm',
                'uses' => 'CourseOrderController@postConfirm',
                'permission' => 'orders.edit',
            ]);

            Route::post('send-order-confirmation-email/{order}', [
                'as' => 'send-order-confirmation-email',
                'uses' => 'CourseOrderController@postResendOrderConfirmationEmail',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('create-shipment/{order}', [
                'as' => 'create-shipment',
                'uses' => 'CourseOrderController@postCreateShipment',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('cancel-shipment/{shipment}', [
                'as' => 'cancel-shipment',
                'uses' => 'CourseOrderController@postCancelShipment',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('update-shipping-address/{address}', [
                'as' => 'update-shipping-address',
                'uses' => 'CourseOrderController@postUpdateShippingAddress',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('update-tax-information/{taxInformation}', [
                'as' => 'update-tax-information',
                'uses' => 'CourseOrderController@postUpdateTaxInformation',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('cancel-order/{order}', [
                'as' => 'cancel',
                'uses' => 'CourseOrderController@postCancelOrder',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::get('print-shipping-order/{order}', [
                'as' => 'print-shipping-order',
                'uses' => 'CourseOrderController@getPrintShippingOrder',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('confirm-payment/{order}', [
                'as' => 'confirm-payment',
                'uses' => 'CourseOrderController@postConfirmPayment',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::get('get-shipment-form/{order}', [
                'as' => 'get-shipment-form',
                'uses' => 'CourseOrderController@getShipmentForm',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::post('refund/{order}', [
                'as' => 'refund',
                'uses' => 'CourseOrderController@postRefund',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey();

            Route::get('get-available-shipping-methods', [
                'as' => 'get-available-shipping-methods',
                'uses' => 'CourseOrderController@getAvailableShippingMethods',
                'permission' => 'orders.edit',
            ]);

            Route::post('coupon/apply', [
                'as' => 'apply-coupon-when-creating-order',
                'uses' => 'CourseOrderController@postApplyCoupon',
                'permission' => 'orders.create',
            ]);

            Route::post('check-data-before-create-order', [
                'as' => 'check-data-before-create-order',
                'uses' => 'CourseOrderController@checkDataBeforeCreateOrder',
                'permission' => 'orders.create',
            ]);

            Route::get('orders/{order}/generate', [
                'as' => 'invoice.generate',
                'uses' => 'CourseOrderController@generateInvoice',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey('order');

            Route::get('orders/{order}/download-proof', [
                'as' => 'download-proof',
                'uses' => 'CourseOrderController@downloadProof',
                'permission' => 'orders.edit',
            ])->wherePrimaryKey('order');
        });

        Route::group(['prefix' => 'incomplete-orders', 'as' => 'course-orders.'], function () {
            Route::match(['GET', 'POST'], '', [
                'as' => 'incomplete-list',
                'uses' => 'CourseOrderController@getIncompleteList',
                'permission' => 'orders.index',
            ]);

            Route::get('view/{order}', [
                'as' => 'view-incomplete-order',
                'uses' => 'CourseOrderController@getViewIncompleteOrder',
                'permission' => 'orders.index',
            ])->wherePrimaryKey();

            Route::post('mark-as-completed/{order}', [
                'as' => 'mark-as-completed',
                'uses' => 'CourseOrderController@markIncompleteOrderAsCompleted',
                'permission' => 'orders.index',
            ])->wherePrimaryKey();

            Route::post('send-order-recover-email/{order}', [
                'as' => 'send-order-recover-email',
                'uses' => 'CourseOrderController@postSendOrderRecoverEmail',
                'permission' => 'orders.index',
            ])->wherePrimaryKey();
        });

        Route::group(['prefix' => 'order-returns', 'as' => 'order_returns.'], function () {
            Route::resource('', 'OrderReturnController')->parameters(['' => 'order_return'])->except(
                ['create', 'store']
            );
        });
    });
});

Theme::registerRoutes(function () {
    Route::group(['namespace' => 'Botble\Course\Http\Controllers\Fronts'], function () {
        Route::group(
            ['prefix' => sprintf('%s/{token}', CourseHelper::getPageSlug('checkout')), 'as' => 'public.checkout.'],
            function () {
                Route::get('/', [
                    'as' => 'information',
                    'uses' => 'PublicCheckoutController@getCheckout',
                ]);

                Route::post('information', [
                    'as' => 'save-information',
                    'uses' => 'PublicCheckoutController@postSaveInformation',
                ]);

                Route::post('process', [
                    'as' => 'process',
                    'uses' => 'PublicCheckoutController@postCheckout',
                ]);

                Route::get('success', [
                    'as' => 'success',
                    'uses' => 'PublicCheckoutController@getCheckoutSuccess',
                ]);

                Route::get('recover', [
                    'as' => 'recover',
                    'uses' => 'PublicCheckoutController@getCheckoutRecover',
                ]);
            }
        );
    });
});
