@extends(FranchiseHelper::viewPath('vendor-dashboard.layouts.master'))

@section('content')
    {!! Form::open(['id' => 'franchise-vendor-discount']) !!}
    <div id="main-discount">
        <discount-vendor-component
            currency="{{ get_application_currency()->symbol }}"
            generate-url={{ route('franchise.vendor.discounts.generate-coupon') }}
            cancel-url={{ route('franchise.vendor.discounts.index') }}
        >
        </discount-vendor-component>
    </div>
    {!! Form::close() !!}
@stop

@push('pre-footer')
    <script>
        'use strict';

        window.trans = window.trans || {};

        window.trans.discount = JSON.parse('{!! addslashes(json_encode(trans('plugins/ecommerce::discount'))) !!}');

        window.trans.enums = {
            'typeOptions': {!! json_encode(FranchiseHelper::discountTypes()) !!}
        };
    </script>

    {!! $jsValidation !!}
@endpush
