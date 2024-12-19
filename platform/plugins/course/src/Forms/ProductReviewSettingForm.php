<?php

namespace Botble\Course\Forms\Settings;

use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Course\Facades\CourseHelper;
use Botble\Course\Http\Requests\Settings\CourseReviewSettingRequest;
use Botble\Setting\Forms\SettingForm;

class CourseReviewSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/course::setting.course_review.name'))
            ->setSectionDescription(trans('plugins/course::setting.course_review.description'))
            ->setValidatorClass(CourseReviewSettingRequest::class)
            ->add('review_enabled', 'onOffCheckbox', [
                'label' => trans('plugins/course::setting.course_review.form.enable_review'),
                'value' => CourseHelper::isReviewEnabled(),
                'wrapper' => [
                    'class' => 'mb-0',
                ],
                'attr' => [
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '.review-settings',
                ],
            ])
            ->add('open_fieldset_review_settings', 'html', [
                'html' => sprintf(
                    '<fieldset class="form-fieldset mt-3 review-settings" style="display: %s;" data-bb-value="1">',
                    CourseHelper::isReviewEnabled() ? 'block' : 'none'
                ),
            ])
            ->add('review_max_file_size', 'number', [
                'label' => trans('plugins/course::setting.course_review.form.review.max_file_size'),
                'value' => CourseHelper::reviewMaxFileSize(),
                'attr' => [
                    'min' => 1,
                    'max' => 1024,
                ],
            ])
            ->add('review_max_file_number', 'number', [
                'label' => trans('plugins/course::setting.course_review.form.review.max_file_number'),
                'value' => CourseHelper::reviewMaxFileNumber(),
                'attr' => [
                    'min' => 1,
                    'max' => 100,
                ],
            ])
            ->add('only_allow_customers_purchased_to_review', 'onOffCheckbox', [
                'label' => trans('plugins/course::setting.course_review.form.only_allow_customers_purchased_to_review'),
                'value' => CourseHelper::onlyAllowCustomersPurchasedToReview(),
            ])
            ->add(
                'review_need_to_be_approved',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/course::setting.course_review.form.review_need_to_be_approved'))
                    ->value(get_course_setting('review_need_to_be_approved')),
            )
            ->add(
                'show_customer_full_name',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/course::setting.course_review.form.show_customer_full_name'))
                    ->value(get_course_setting('show_customer_full_name', true))
                    ->helperText(trans('plugins/course::setting.course_review.form.show_customer_full_name_help'))
            )
            ->add('close_fieldset_review_settings', 'html', ['html' => '</fieldset>']);
    }
}
