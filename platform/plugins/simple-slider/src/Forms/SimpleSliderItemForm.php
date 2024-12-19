<?php

namespace Botble\SimpleSlider\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\SimpleSlider\Http\Requests\SimpleSliderItemRequest;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Botble\Course\Models\CourseCategory;
use Botble\Course\Models\Course;

class SimpleSliderItemForm extends FormAbstract
{
    
    public function setup(): void
    {
        $bannerRedirection = [
            'remedies' => 'Remedies',
            'remedies_category' => 'Remedies Category',
            'courses' => 'Courses',
            'course_category' => 'Course Category',
            'franchises' => 'Franchises',
            'franchises_services' => 'Franchises Services',
        ];

        $this
            ->model(SimpleSliderItem::class)
            ->setValidatorClass(SimpleSliderItemRequest::class)
            ->contentOnly()
            ->add('simple_slider_id', 'hidden', [
                'value' => $this->getRequest()->input('simple_slider_id'),
            ])
            // ->add('title', TextField::class, [
            //     'label' => trans('core/base::forms.title'),
            //     'attr' => [
            //         'data-counter' => 120,
            //     ],
            // ])
            ->add(
                'banner_redirection',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/simple-slider::simple-slider.banner_redirection'))
                    ->choices(['' => trans('plugins/simple-slider::simple-slider.banner_redirection_select')] + $bannerRedirection)
                    ->addAttribute('id', 'banner_redirection')
                    ->addAttribute('class', 'form-control')
                    ->addAttribute('card-body-class', 'p-0')
                    ->required()
            )
            
            ->add(
                'banner_redirection_item',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/simple-slider::simple-slider.banner_redirection_item'))
                    ->choices(['' => trans('plugins/simple-slider::simple-slider.banner_redirection_item_select')])
                    ->addAttribute('id', 'banner_redirection_item')
                    ->addAttribute('class', 'form-control')
                    ->addAttribute('card-body-class', 'p-0')
                    ->required()
            )
            
            ->add('link', TextField::class, [
                'label' => trans('core/base::forms.link'),
                'attr' => [
                    'placeholder' => 'https://',
                    'data-counter' => 120,
                ],
            ])
            // ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('image', MediaImageField::class, MediaImageFieldOption::make()->required());
    }
}

