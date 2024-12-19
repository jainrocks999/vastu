@extends(BaseHelper::getAdminMasterLayoutTemplate())

@push('header')
    @include('plugins/course::course_discount.partials.trans')

    {!! JsValidator::formRequest(Botble\Course\Http\Requests\CourseDiscountRequest::class) !!}
@endpush

@section('content')
    <x-core::form>
        <discount-component
            currency="{{ get_application_currency()->symbol }}"
            :discount="{{ $discount }}"
        ></discount-component>
    </x-core::form>
@endsection
