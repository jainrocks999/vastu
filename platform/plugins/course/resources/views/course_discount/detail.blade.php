<div @class(['discount', 'is-discount-disabled' => $item->isExpired()])>
    @if ($item->isExpired())
        <span class="discount-expired show">{{ trans('plugins/course::course-discount.expired') }}</span>
    @endif
    <div class="discount-inner">
        <p class="discount-code">
            @if ($isCoupon)
                <span class="text-uppercase">{{ trans('plugins/course::course-discount.coupon_code') }}</span>:
                <b>{{ $item->code }}</b>
                <x-core::copy :copyableState="$item->code" class="text-white"/>
            @else
                <span class="text-uppercase">{{ trans('plugins/course::course-discount.discount_promotion') }}</span>:
                {{ $item->title }}
            @endif
        </p>
        <p class="discount-desc">
            {!! BaseHelper::clean(get_course_discount_description($item)) !!}
        </p>
        @if ($isCoupon)
            <p @class(['discount-desc' => $item->isExpired(), 'discount-text-color' => ! $item->isExpired()])>
                ({{ trans('plugins/course::course-discount.coupon_code') }}
                <strong>
                    @if ($item->can_use_with_promotion)
                        {{ trans('plugins/course::course-discount.can') }}
                    @else
                        {{ trans('plugins/course::course-discount.cannot') }}
                    @endif
                </strong>
                {{ trans('plugins/course::course-discount.use_with_promotion') }}).
            </p>
        @endif
    </div>
</div>
