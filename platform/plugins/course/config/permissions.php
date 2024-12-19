<?php

return [
    [
        'name' => 'COURSE',
        'flag' => 'plugin.course',
    ],
    [
        'name' => 'COURSE',
        'flag' => 'course.index',
        'parent_flag' => 'plugin.course',
    ],
    [
        'name' => 'Create',
        'flag' => 'course.create',
        'parent_flag' => 'course.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'course.edit',
        'parent_flag' => 'course.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'course.destroy',
        'parent_flag' => 'course.index',
    ],
    [
        'name' => 'COURSE Categories',
        'flag' => 'course_category.index',
        'parent_flag' => 'plugin.course',
    ],
    [
        'name' => 'Create',
        'flag' => 'course_category.create',
        'parent_flag' => 'course_category.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'course_category.edit',
        'parent_flag' => 'course_category.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'course_category.destroy',
        'parent_flag' => 'course_category.index',
    ],
    [
        'name' => 'COURSE DISCOUNT',
        'flag' => 'course_discount.index',
        'parent_flag' => 'plugin.course',
    ],
    [
        'name' => 'Create',
        'flag' => 'course_discount.create',
        'parent_flag' => 'course_discount.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'course_discount.edit',
        'parent_flag' => 'course_discount.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'course_discount.destroy',
        'parent_flag' => 'course_discount.index',
    ],
    [
        'name' => 'COURSE ORDER',
        'flag' => 'course_order.index',
        'parent_flag' => 'plugin.course',
    ],
    [
        'name' => 'Create',
        'flag' => 'course_order.create',
        'parent_flag' => 'course_order.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'course_order.edit',
        'parent_flag' => 'course_order.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'course_order.destroy',
        'parent_flag' => 'course_order.index',
    ],
    [
        'name' => 'COURSE REVIEWS',
        'flag' => 'course_review.index',
        'parent_flag' => 'plugin.course',
    ],
    [
        'name' => 'Create',
        'flag' => 'course_review.create',
        'parent_flag' => 'course_review.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'course_review.edit',
        'parent_flag' => 'course_review.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'course_review.destroy',
        'parent_flag' => 'course_review.index',
    ],
    [
        'name' => 'COURSE',
        'flag' => 'courses.settings',
        'parent_flag' => 'settings.others',
    ],
];
