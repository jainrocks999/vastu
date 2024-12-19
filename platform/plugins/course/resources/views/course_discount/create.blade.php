@extends(BaseHelper::getAdminMasterLayoutTemplate())
@push('header')
    @include('plugins/course::course_discount.partials.trans')
@endpush

@section('content')
    <x-core::form>
        <course-discount-component
            currency="{{ get_application_currency()->symbol }}"
        ></course-discount-component>
    </x-core::form>
@stop

@push('footer')
    {!! $jsValidation !!}
@endpush
