<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Base\Facades\Html;
use Botble\Course\Enums\DiscountTargetEnum;
use Botble\Course\Enums\DiscountTypeOptionEnum;
use Botble\Course\Models\CourseDiscount;
use Botble\Course\Models\DiscountStudent;
use Botble\Course\Models\DiscountCourse;
use Botble\Course\Models\DiscountProductCollection;
use Botble\Course\Models\Product;

    if (! function_exists('get_course_discount_description')) {
        function get_course_discount_description(CourseDiscount $discount): string
        {
            $type = $discount->type_option;

            $target = $discount->target;

            $value = $discount->value;

            $description = [];

            $isAdmin = auth()->check() && AdminHelper::isInAdmin(true);

            switch ($type) {
                case DiscountTypeOptionEnum::SHIPPING:
                    if ($target) {
                        $description[] = __('Free shipping to <strong>:target</strong>', ['target' => DiscountTargetEnum::getLabel($target)]);
                    } else {
                        $description[] = __('Free shipping for all course');
                    }

                    if ($value) {
                        $description[] = __('when shipping fee less than or equal :amount', ['amount' => format_price($value)]);
                    }

                    break;
                case DiscountTypeOptionEnum::SAME_PRICE:
                    $description[] = __('Same fee :amount', ['amount' => format_price($value)]);
                    switch ($target) {
                        case DiscountTargetEnum::COURSE_COLLECTIONS:
                            $collections = DiscountProductCollection::query()->where('discount_id', $discount->getKey())
                                ->join(
                                    'ec_product_collections',
                                    'ec_product_collections.id',
                                    '=',
                                    'ec_discount_product_collections.product_collection_id'
                                )
                                ->pluck('ec_product_collections.name')
                                ->all();

                            $description[] = __('for all product in collection :collections', ['collections' => implode(', ', $collections)]);

                            break;
                        default:
                            $description[] = __('for all courses in order');

                            break;
                    }

                    break;
                default:
                    if ($type === DiscountTypeOptionEnum::PERCENTAGE) {
                        $description[] = __('Discount :percentage%', ['percentage' => $value]);
                    } else {
                        $description[] = __('Discount :amount', ['amount' => format_price($value)]);
                    }

                    switch ($target) {
                        case DiscountTargetEnum::MINIMUM_ORDER_AMOUNT:
                            $description[] = __('for order with amount from :price', ['price' => format_price($discount->min_order_price)]);

                            break;
                        // case DiscountTargetEnum::SPECIFIC_PRODUCT:
                        //     $products = DiscountProduct::query()->where('discount_id', $discount->getKey())
                        //         ->join('ec_products', 'ec_products.id', '=', 'ec_discount_products.product_id')
                        //         ->where('ec_products.is_variation', false)
                        //         ->pluck('ec_products.name', 'ec_products.id')
                        //         ->all();

                        //     $productLinks = [];
                        //     foreach (array_unique($products) as $productId => $productName) {
                        //         if ($isAdmin) {
                        //             $productLinks[] = Html::link(route('products.edit', $productId), $productName, ['target' => '_blank'])->toHtml();
                        //         } else {
                        //             $productLinks[] = $productName;
                        //         }
                        //     }

                        //     $description[] = __('for product(s) :products', ['products' => implode(', ', $productLinks)]);

                        //     break;
                        // case DiscountTargetEnum::CUSTOMER:
                        //     $customers = DiscountCustomer::query()->where('discount_id', $discount->getKey())
                        //         ->join('ec_customers', 'ec_customers.id', '=', 'ec_discount_customers.customer_id')
                        //         ->pluck('ec_customers.name', 'ec_customers.id')
                        //         ->all();

                        //     $customerLinks = [];
                        //     foreach (array_unique($customers) as $customerId => $customerName) {
                        //         if ($isAdmin) {
                        //             $customerLinks[] = Html::link(route('customers.edit', $customerId), $customerName, ['target' => '_blank'])->toHtml();
                        //         } else {
                        //             $customerLinks[] = $customerName;
                        //         }
                        //     }

                        //     $description[] = __('for customer(s) :customers', ['customers' => implode(', ', $customerLinks)]);

                        //     break;
                        // case DiscountTargetEnum::COURSE_COLLECTIONS:
                        //     $collections = DiscountProductCollection::query()->where('discount_id', $discount->getKey())
                        //         ->join(
                        //             'ec_product_collections',
                        //             'ec_product_collections.id',
                        //             '=',
                        //             'ec_discount_product_collections.product_collection_id'
                        //         )
                        //         ->pluck('ec_product_collections.name', 'ec_product_collections.id')
                        //         ->all();

                        //     $collectionLinks = [];
                        //     foreach (array_unique($collections) as $collectionId => $collectionName) {
                        //         if ($isAdmin) {
                        //             $collectionLinks[] = Html::link(route('product-collections.edit', $collectionId), $collectionName, ['target' => '_blank'])->toHtml();
                        //         } else {
                        //             $collectionLinks[] = $collectionName;
                        //         }
                        //     }

                        //     $description[] = __('for all courses in collection :collections', ['collections' => implode(', ', $collectionLinks)]);

                        //     break;
                        // case DiscountTargetEnum::COURSE_VARIANT:
                        //     $products = Product::query()
                        //         ->join('ec_discount_products', 'ec_discount_products.product_id', '=', 'ec_products.id')
                        //         ->where('discount_id', $discount->getKey())
                        //         ->where('is_variation', true)
                        //         ->with('variationInfo.configurableProduct')
                        //         ->get();

                        //     $productLinks = [];
                        //     foreach ($products as $variant) {
                        //         if ($isAdmin) {
                        //             $productLinks[] = Html::link(route('products.edit', $variant->originalProduct->getKey()), $variant->originalProduct->name . ' ' . $variant->variation_attributes, ['target' => '_blank'])->toHtml();
                        //         } else {
                        //             $productLinks[] = $variant->originalProduct->name . ' ' . $variant->variation_attributes;
                        //         }
                        //     }

                        //     $description[] = __('for product variant(s) :variants', ['variants' => implode(', ', $productLinks)]);

                        //     break;
                        case DiscountTargetEnum::ONCE_PER_CUSTOMER:
                            $description[] = __('limited to use coupon code per student. This coupon can only be used once per student!');
                            break;
                        case DiscountTargetEnum::COURSE_CATEGORIES:
                            $categories = $discount
                                ->courseCategories()
                                ->pluck('course_category.name', 'course_category.id')
                                ->all();

                            $categoryLinks = [];
                            foreach (array_unique($categories) as $categoryId => $categoryName) {
                                if ($isAdmin) {
                                    $categoryLinks[] = Html::link(route('course-categories.edit', $categoryId), $categoryName, ['target' => '_blank'])->toHtml();
                                } else {
                                    $categoryLinks[] = $categoryName;
                                }
                            }

                            $description[] = __('for all courses in category :categories', ['categories' => implode(', ', $categoryLinks)]);

                            break;
                        default:
                            $description[] = __('for all courses in category');

                            break;
                    }
            }

            return trim(implode(' ', $description));
        }
    }


    if (! function_exists('course_discount_amount')) {
        function course_discount_amount(CourseDiscount $discount): string
        {
            $type = $discount->type_option;
            $target = $discount->target;
            $value = $discount->value;
            $description = [];
            die();
            switch ($type) {
                case DiscountTypeOptionEnum::SHIPPING:
                    if ($target) {
                        $description[] = __('Free shipping to <strong>:target</strong>', ['target' => DiscountTargetEnum::getLabel($target)]);
                    } else {
                        $description[] = __('Free shipping for all course');
                    }

                    if ($value) {
                        $description[] = __('when shipping fee less than or equal :amount', ['amount' => format_price($value)]);
                    }

                    break;
                case DiscountTypeOptionEnum::SAME_PRICE:
                    $description[] = __('Same fee :amount', ['amount' => format_price($value)]);
                    switch ($target) {
                        case DiscountTargetEnum::COURSE_COLLECTIONS:
                            $collections = DiscountProductCollection::query()->where('discount_id', $discount->getKey())
                                ->join(
                                    'ec_product_collections',
                                    'ec_product_collections.id',
                                    '=',
                                    'ec_discount_product_collections.product_collection_id'
                                )
                                ->pluck('ec_product_collections.name')
                                ->all();

                            $description[] = __('for all product in collection :collections', ['collections' => implode(', ', $collections)]);

                            break;
                        default:
                            $description[] = __('for all courses in order');

                            break;
                    }

                    break;
                default:
                    if ($type === DiscountTypeOptionEnum::PERCENTAGE) {
                        $description[] = __('Discount :percentage%', ['percentage' => $value]);
                    } else {
                        $description[] = __('Discount :amount', ['amount' => format_price($value)]);
                    }

                    switch ($target) {
                        case DiscountTargetEnum::MINIMUM_ORDER_AMOUNT:
                            $description[] = __('for order with amount from :price', ['price' => format_price($discount->min_order_price)]);
                            break;
                        case DiscountTargetEnum::ONCE_PER_CUSTOMER:
                            $description[] = __('limited to use coupon code per student. This coupon can only be used once per student!');
                            break;
                        case DiscountTargetEnum::COURSE_CATEGORIES:
                            $categories = $discount
                                ->courseCategories()
                                ->pluck('course_category.name', 'course_category.id')
                                ->all();

                            $categoryLinks = [];
                            foreach (array_unique($categories) as $categoryId => $categoryName) {
                                if ($isAdmin) {
                                    $categoryLinks[] = Html::link(route('course-categories.edit', $categoryId), $categoryName, ['target' => '_blank'])->toHtml();
                                } else {
                                    $categoryLinks[] = $categoryName;
                                }
                            }

                            $description[] = __('for all courses in category :categories', ['categories' => implode(', ', $categoryLinks)]);

                            break;
                        default:
                            $description[] = __('for all courses in category');

                            break;
                    }
            }

            return trim(implode(' ', $description));
        }
    }
