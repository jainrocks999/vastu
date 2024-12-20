<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Course\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'course-category', 'as' => 'course_category.'], function () {
            Route::resource('', 'CourseCategoryController')->parameters(['' => 'course_category']);

            Route::get('get-list-course-categories-for-select', [
                'as' => 'get-list-course-categories-for-select',
                'uses' => 'CourseCategoryController@getListForSelect',
                'permission' => 'course-categories.index',
            ]);
        });

        Route::resource('trainer', TrainerController::class)
        ->parameters([
            'trainer' => 'trainer'  // Custom parameter name for the model
        ]);

        
        Route::group(['prefix' => 'courses', 'as' => 'course.'], function () {
            Route::resource('', 'CourseController')->parameters(['' => 'course']);
            Route::get('get-relations-box/{id?}', [
                'as' => 'get-relations-boxes',
                'uses' => 'CourseController@getRelationBoxes',
                'permission' => 'courses.settings',
            ]);

         

            Route::get('get-list-course-for-select', [
                'as' => 'get-list-course-for-select',
                'uses' => 'CourseController@getListCourseForSelect',
                'permission' => 'course.index',
            ]);

            Route::get('get-all-products-and-variations', [
                'as' => 'get-all-products-and-variations',
                'uses' => 'CourseController@getAllProductAndVariations',
                'permission' => 'course.index',
            ]);

         

        });

        //Course Order
        Route::group(['prefix' => 'courses-order', 'as' => 'course_order.'], function () {
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



        //Course Reviews 
        Route::group(['prefix' => 'courses-review', 'as' => 'course_review.'], function () {
            Route::resource('', 'ReviewCourseController')->parameters(['' => 'course_review']);
            Route::match(['GET', 'POST'], '/', [
                'as' => 'index',
                'uses' => 'ReviewCourseController@index',
                'permission' => 'reviews.index',
            ]);

            Route::get('create', [
                'as' => 'create',
                'uses' => 'ReviewCourseController@create',
                'permission' => 'reviews.create',
            ]);

            Route::post('create', [
                'as' => 'store',
                'uses' => 'ReviewCourseController@store',
                'permission' => 'reviews.create',
            ]);

            Route::get('/ajax-search-customers', [
                'as' => 'ajax-search-customers',
                'uses' => 'ReviewCourseController@ajaxSearchCustomers',
                'permission' => 'reviews.create',
            ]);
           
            Route::get('/ajax-search-courses', [
                'as' => 'ajax-search-courses',
                'uses' => 'ReviewCourseController@ajaxSearchCourses',
                'permission' => 'reviews.create',
            ]);

            Route::get('{review}', [
                'as' => 'show',
                'uses' => 'ReviewCourseController@show',
                'permission' => 'reviews.index',
            ]);

            Route::delete('{review}', [
                'as' => 'destroy',
                'uses' => 'ReviewCourseController@destroy',
                'permission' => 'reviews.destroy',
            ]);

            Route::post('{review}/publish', [
                'as' => 'publish',
                'uses' => 'PublishedCourseReviewController@store',
                'permission' => 'reviews.publish',
            ]);

            Route::post('{review}/unpublish', [
                'as' => 'unpublish',
                'uses' => 'PublishedCourseReviewController@destroy',
                'permission' => 'reviews.publish',
            ]);

            Route::post('{review}/reply', [
                'as' => 'reply',
                'uses' => 'ReviewCourseReplyController@store',
                'permission' => 'reviews.reply',
            ]);

            Route::put('{review}/reply/{reply}', [
                'as' => 'reply.update',
                'uses' => 'ReviewCourseReplyController@update',
                'permission' => 'reviews.reply',
            ]);

            Route::delete('{review}/reply/{reply}', [
                'as' => 'reply.destroy',
                'uses' => 'ReviewCourseReplyController@destroy',
                'permission' => 'reviews.reply',
            ]);
        });
        

        Route::group(['prefix' => 'courses-discount', 'as' => 'course_discount.'], function () {
            Route::resource('', 'CourseDiscountController')->parameters(['' => 'course_discount']);
        });


      
     

        Route::group(['prefix' => 'settings'], function () {
            Route::get('courses', [
                'as' => 'courses.settings',
                'uses' => 'Settings\CourseSettingController@edit',
            ]);


            Route::put('courses', [
                'as' => 'courses.settings.update',
                'uses' => 'Settings\CourseSettingController@update',
                'permission' => 'courses.settings',
            ]);
        });
    });
});
