<x-core::card class="mb-3">
    <x-core::card.header>
        <x-core::card.title>
          Course 
        </x-core::card.title>
    </x-core::card.header>

    <x-core::card.body>
        @php
            $courses = $course ? $course->courses : collect();
        @endphp
        <x-plugins-course::box-search-advanced
            type="course"
            :search-target="$dataUrl"
            :shown="$courses->isNotEmpty()"
        >
            <input
                name="related_courses"
                type="hidden"
                value="@if ($courses->isNotEmpty()) {{ implode(',', $course->courses()->allRelatedIds()->all()) }} @endif"
            />

            <x-slot:items>
                @include('plugins/course::courses.partials.selected-courses-list', [
                    'courses' => $courses,
                    'includeVariation' => false,
                ])
            </x-slot:items>
        </x-plugins-course::box-search-advanced>
    </x-core::card.body>
</x-core::card>

<x-core::custom-template id="selected_course_list_template">
    <div class="list-group-item">
        <div class="row align-items-center">
            <div class="col-auto">
                <span
                    class="avatar"
                    style="background-image: url('__image__')"
                ></span>
            </div>
            <div class="col text-truncate">
                <a href="__url__" class="text-body d-block" target="_blank">__name__</a>
                <div class="text-secondary text-truncate">
                    __attributes__
                </div>
            </div>
            <div class="col-auto">
                <a
                    href="javascript:void(0)"
                    data-bb-toggle="course-delete-item"
                    data-bb-target="__id__"
                    class="text-decoration-none list-group-item-actions btn-trigger-remove-selected-course"
                    title="{{ trans('plugins/course::courses.delete') }}"
                >
                    <x-core::icon name="ti ti-x" class="text-secondary" />
                </a>
            </div>
        </div>
    </div>
</x-core::custom-template>