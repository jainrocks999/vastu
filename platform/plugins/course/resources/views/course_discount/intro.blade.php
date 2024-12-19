@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <x-core::card>
        <div class="page page-center" style="min-height: calc(100vh - 25rem)">
            <div class="container container-tight py-4">
                <div class="empty">
                    <div class="empty-img">
                        <x-core::icon name="ti ti-basket-discount" />
                    </div>
                    <p class="empty-title">{{ trans('plugins/course::course-discount.intro.title') }}</p>
                    <p class="empty-subtitle text-secondary">
                        {{ trans('plugins/course::course-discount.intro.description') }}
                    </p>
                    <div class="empty-action">
                        <x-core::button
                            color="primary"
                            tag="a"
                            :href="route('course_discount.create')"
                        >
                            {{ trans('plugins/course::course-discount.intro.button_text') }}
                        </x-core::button>
                    </div>
                </div>
            </div>
        </div>
    </x-core::card>
@endsection
