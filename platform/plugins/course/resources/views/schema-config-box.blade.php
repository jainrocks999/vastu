<div class="course-schema-items">
    {!! Form::repeater('course_schema_config', $value, [
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_title'),
            'required' => true,
            'attributes' => [
                'name' => 'course_title',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_code'),
            'required' => true,
            'attributes' => [
                'name' => 'course_code',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_desc'),
            'required' => true,
            'attributes' => [
                'name' => 'course_desc',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_image'),
            'required' => true,
            'attributes' => [
                'name' => 'course_image',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_video'),
            'required' => true,
            'attributes' => [
                'name' => 'course_video',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_docs'),
            'required' => true,
            'attributes' => [
                'name' => 'course_docs',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_type'),
            'required' => true,
            'attributes' => [
                'name' => 'course_type',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_price'),
            'required' => true,
            'attributes' => [
                'name' => 'course_price',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_instructor'),
            'required' => true,
            'attributes' => [
                'name' => 'course_instructor',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_start_date'),
            'required' => true,
            'attributes' => [
                'name' => 'course_start_date',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_end_date'),
            'required' => true,
            'attributes' => [
                'name' => 'course_end_date',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_language'),
            'required' => true,
            'attributes' => [
                'name' => 'course_language',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_prerequisites'),
            'required' => true,
            'attributes' => [
                'name' => 'course_prerequisites',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_enrollment_limit'),
            'required' => true,
            'attributes' => [
                'name' => 'course_enrollment_limit',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_enrollment_type'),
            'required' => true,
            'attributes' => [
                'name' => 'course_enrollment_type',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_certification'),
            'required' => true,
            'attributes' => [
                'name' => 'course_certification',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_discounts'),
            'required' => true,
            'attributes' => [
                'name' => 'course_discounts',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_tags'),
            'required' => true,
            'attributes' => [
                'name' => 'course_tags',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_keywords'),
            'required' => true,
            'attributes' => [
                'name' => 'course_keywords',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
        [
            'type' => 'text',
            'label' => trans('plugins/course::course.course_category_id'),
            'required' => true,
            'attributes' => [
                'name' => 'course_category_id',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'data-counter' => 1000,
                    'rows' => 1,
                ],
            ],
        ],
    ]) !!}
</div>

<div class="d-inline">
    <span>{{ trans('plugins/course::course.or') }}</span>
    <a href="javascript:void(0)" data-bb-toggle="select-from-existing">
        {{ trans('plugins/course::course.select_from_existing') }}
    </a>
</div>

<div class="existing-course-schema-items mt-2" @style(['display: none' => empty($selectedCourses) || ! $courses])>
    @if($courses)
        {{ Form::multiChecklist('selected_existing_courses[]', $selectedCourses, $courses, [], false, false, true) }}
    @else
        <p class="text-muted mb-0">
            {!! BaseHelper::clean(trans(
                'plugins/course::course.no_existing',
                ['link' => Html::link(route('course.create'), trans('plugins/course::course.courses_menu_name'), ['target' => '_blank'])])
            ) !!}
        </p>
    @endif
</div>
