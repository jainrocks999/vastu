<script>
    'use strict';

    window.trans = window.trans || {};

    window.trans.discount = {
        select_type_of_discount: '{{ trans('plugins/course::course-discount.select_type_of_discount') }}',
        coupon_code: '{{ trans('plugins/course::course-discount.coupon_code') }}',
        promotion: '{{ trans('plugins/course::course-discount.promotion') }}',
        create_discount_promotion: '{{ trans('plugins/course::course-discount.create_discount_promotion') }}',
        create_coupon_code: '{{ trans('plugins/course::course-discount.create_coupon_code') }}',
        generate_coupon_code: '{{ trans('plugins/course::course-discount.generate_coupon_code') }}',
        enter_promotion_name: '{{ trans('plugins/course::course-discount.enter_promotion_name') }}',
        customers_will_enter_this_coupon_code_when_they_checkout: '{{ trans('plugins/course::course-discount.customers_will_enter_this_coupon_code_when_they_checkout') }}',
        can_be_used_with_promotion: '{{ trans('plugins/course::course-discount.can_be_used_with_promotion') }}',
        can_be_used_with_flash_sale: '{{ trans('plugins/course::course-discount.can_be_used_with_flash_sale') }}',
        can_be_used_with_flash_sale_help: '{{ trans('plugins/course::course-discount.can_be_used_with_flash_sale_help') }}',
        unlimited_coupon: '{{ trans('plugins/course::course-discount.unlimited_coupon') }}',
        enter_number: '{{ trans('plugins/course::course-discount.enter_number') }}',
        apply_via_url: '{{ trans('plugins/course::course-discount.apply_via_url') }}',
        apply_via_url_description: '{{ trans('plugins/course::course-discount.apply_via_url_description') }}',
        display_at_checkout: '{{ trans('plugins/course::course-discount.display_at_checkout') }}',
        display_at_checkout_description: '{{ trans('plugins/course::course-discount.display_at_checkout_description') }}',
        description: '{{ trans('plugins/course::course-discount.description') }}',
        description_placeholder: '{{ trans('plugins/course::course-discount.description_placeholder') }}',
        coupon_type: '{{ trans('plugins/course::course-discount.coupon_type') }}',
        percentage_discount: '{{ trans('plugins/course::course-discount.percentage_discount') }}',
        free_shipping: '{{ trans('plugins/course::course-discount.free_shipping') }}',
        same_price: '{{ trans('plugins/course::course-discount.same_price') }}',
        apply_for: '{{ trans('plugins/course::course-discount.apply_for') }}',
        all_orders: '{{ trans('plugins/course::course-discount.all_orders') }}',
        order_amount_from: '{{ trans('plugins/course::course-discount.order_amount_from') }}',
        course_collection: '{{ trans('plugins/course::course-discount.course_collection') }}',
        course_category: '{{ trans('plugins/course::course-discount.course_category') }}',
        course: '{{ trans('plugins/course::course-discount.course') }}',
        customer: '{{ trans('plugins/course::course-discount.customer') }}',
        variant: '{{ trans('plugins/course::course-discount.variant') }}',
        once_per_customer: '{{ trans('plugins/course::course-discount.once_per_customer') }}',
        search_course: '{{ trans('plugins/course::course-discount.search_course') }}',
        no_courses_found: '{{ trans('plugins/course::course-discount.no_courses_found') }}',
        search_customer: '{{ trans('plugins/course::course-discount.search_customer') }}',
        no_customer_found: '{{ trans('plugins/course::course-discount.no_customer_found') }}',
        one_time_per_order: '{{ trans('plugins/course::course-discount.one_time_per_order') }}',
        one_time_per_course_in_cart: '{{ trans('plugins/course::course-discount.one_time_per_course_in_cart') }}',
        number_of_courses: '{{ trans('plugins/course::course-discount.number_of_courses') }}',
        selected_courses: '{{ trans('plugins/course::course-discount.selected_courses') }}',
        selected_customers: '{{ trans('plugins/course::course-discount.selected_customers') }}',
        time: '{{ trans('plugins/course::course-discount.time') }}',
        start_date: '{{ trans('plugins/course::course-discount.start_date') }}',
        end_date: '{{ trans('plugins/course::course-discount.end_date') }}',
        never_expired: '{{ trans('plugins/course::course-discount.never_expired') }}',
        save: '{{ trans('plugins/course::course-discount.save') }}',
        discount: '{{ trans('plugins/course::course-discount.discount') }}',
        when_shipping_fee_less_than: '{{ trans('plugins/course::course-discount.when_shipping_fee_less_than') }}',
        is: '{{ trans('plugins/course::course-discount.is') }}',
    }

    $(document).ready(function() {
        $(document).on('click', 'body', function(e) {
            let container = $('.box-search-advance');

            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.find('.panel').addClass('hidden');
            }
        });
    });
</script>
