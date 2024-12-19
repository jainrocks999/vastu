<!-- @extends(BaseHelper::getAdminMasterLayoutTemplate()) -->

@section('content')
    <x-plugins-course::intro
        :title="trans('plugins/course::course-reviews.intro.title')"
        :subtitle="trans('plugins/course::course-reviews.intro.description')"
        :action-url="route('course_review.create')"
        :action-label="trans('plugins/course::course-reviews.create_review')"
    >
        <x-slot:icon>
            <img
                src="{{ asset('vendor/core/plugins/course/images/empty-customer.png') }}"
                alt="image"
            >
        </x-slot:icon>
    </x-plugins-course::intro>
@stop
