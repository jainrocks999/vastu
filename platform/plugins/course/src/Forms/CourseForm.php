<?php

namespace Botble\Course\Forms;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;

use Botble\Base\Forms\FieldOptions\EditorFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\MediaImagesField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\TagField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\FormAbstract;
use Botble\Course\Http\Requests\CourseRequest;
use Botble\Course\Models\Course;
use Botble\Course\Models\RelatedCourse;
use Botble\Course\Models\CourseCategory;
use Carbon\Carbon;
class CourseForm extends FormAbstract
{
    public function setup(): void
    {
        $courseCategories = CourseCategory::query()->pluck('name','id')->all();
        $courses = Course::query()->pluck('title','id')->all();
        $courseId = null;
        $selectedCategories = [];
        $tags = null;
        $totalCourseVariations = 0;
        $selectedCourses = [];
        $fields = [
            'Hindi' => 'Hindi',
            'English' => 'English',
        ];

        $fields2 = [
            'free' => 'Free',
            'paid' => 'Paid',
        ];

        $fields3 = [
            '1' => 'Yes',
            '0' => 'No',
        ];

        if ($this->getModel()) {

            /**
             * @var Course $course
             */
            $course = $this->getModel();

            $courseId = $course->id;

            $selectedCategories = $course->category()->pluck('id')->all();

            $courses = Course::where('id','!=',$courseId)->pluck('title','id')->all();
            $selectedCourses = RelatedCourse::where('from_course_id',$courseId)->pluck('to_course_id')->toArray();
          //  $tags = $course->tags()->pluck('name')->implode(',');
        }

        $this
            ->model(Course::class)
            ->setValidatorClass(CourseRequest::class)   
            ->setFormOption('files', true)
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/course::course.title'))
                    ->required()
            )
            ->add(
                'code',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/course::course.code'))
                ->required()
            )
            ->add(
                'label1',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/course::course.label1'))
                ->required()
            )
            ->add(
                'description1',
                EditorField::class,
                EditorFieldOption::make()
                ->label(trans('plugins/course::course.description1'))
                ->required()
            )
            ->add(
                'label2',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/course::course.label2'))
            )
            ->add(
                'description2',
                EditorField::class,
                EditorFieldOption::make()
                ->label(trans('plugins/course::course.description2'))
            )
            ->add(
                'label3',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/course::course.label3'))
            )
            ->add(
                'description3',
                EditorField::class,
                EditorFieldOption::make()
                ->label(trans('plugins/course::course.description3'))
            )
            ->add(
                'label4',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/course::course.label4'))
            )
            ->add(
                'description4',
                EditorField::class,
                EditorFieldOption::make()
                ->label(trans('plugins/course::course.description4'))
            )
            ->add(
                'image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(trans('plugins/course::course.image'))
            )
            ->add(
                'price',
                NumberField::class,
                 NumberFieldOption::make()
                    ->label(trans('plugins/course::course.price'))
                    ->required()
            )
             ->add(
                'student_price',
                NumberField::class,
                 NumberFieldOption::make()
                    ->label(trans('plugins/course::course.student_price'))
                    ->required()
            )
            ->add(
                'franchise_price',
                NumberField::class,
                 NumberFieldOption::make()
                    ->label(trans('plugins/course::course.franchise_price'))
                    ->required()
            )
             ->add(
                'sale_price',
                NumberField::class,
                 NumberFieldOption::make()
                    ->label(trans('plugins/course::course.sale_price'))
                    ->required()
            )
            ->add(
                'instructor',
                TextField::Class,
                TextFieldOption::make()
                ->label(trans('plugins/course::course.instructor'))
            )
         
            ->add(
                'prerequisites',
                TextField::Class,   
                TextFieldOption::make()
                ->label(trans('plugins/course::course.prerequisites'))
            )
           
            ->add(
                'start_date',
                DatePickerField::Class,
                DatePickerFieldOption::make()
                ->label(trans('plugins/course::course.start_date'))
                ->required()
            )
            ->add(
                'end_date',
                DatePickerField::class,
                DatePickerFieldOption::make()
                ->label(trans('plugins/course::course.end_date'))
            )
            ->add(
                'certification',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/course::course.certification'))
                    ->choices($fields3)
            )
            ->add('related_course[]', MultiCheckListField::class, [
                'label' => trans('plugins/course::course.related_course'),
                'choices' => $courses,
                'value' => old('related_course', $selectedCourses),
            ])

            // ->add(
            //     'related_course[]',
            //     MultiCheckListField::class,
            //     MultiChecklistFieldOption::make()
            //         ->label(trans('plugins/course::course.related_course'))
            //         ->choices($courses)
            //         // ->value($selectedCourses) 
            //         ->addAttribute('card-body-class', 'p-0')
            //         ->searchable()
            //         ->required()
            // )
            // ->add(
            //     'discounts',
            //     NumberField::Class,
            //     NumberFieldOption::make()
            //     ->label(trans('plugins/course::course.discounts'))
            //     ->required()
            // )
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status')
            ->add(
                'type',
                RadioField::class,
                RadioFieldOption::make()
                    ->label(trans('plugins/course::course.type'))
                    ->choices($fields2)
                    ->required()
            )
            ->add(
                'course_category_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/course::course.course_category_id'))
                    ->choices(['' => trans('plugins/course::course.select_category')] + $courseCategories)
                    ->addAttribute('card-body-class', 'p-0')
                    ->required()
            )
            // ->add(
            //     'enrollment_type',
            //     SelectField::Class,
            //     SelectFieldOption::make()
            //     ->label(trans('plugins/course::course.enrollment_type'))
            //     ->choices(['' => trans('plugins/course::course.enrollment_type')] + ['open'=>'Open','restricted'=>'Restricted'])
             
            // )
            // ->add(
            //     'enrollment_limit',
            //     NumberField::Class,
            //     NumberFieldOption::make()
            //     ->label(trans('plugins/course::course.enrollment_limit'))
            
            // )
            ->add(
                'tax',
                TextField::Class,   
                TextFieldOption::make()
                ->label(trans('plugins/course::course.tax'))
            )
            ->add(
                'tax_type',
                SelectField::Class,
                SelectFieldOption::make()
                ->label(trans('plugins/course::course.tax_type'))
                ->choices(['' => trans('plugins/course::course.tax_type')] + ['amount'=>'Amount','percentage'=>'Percentage'])
             
            )
            ->add(
                'tax_amount',
                NumberField::Class,
                NumberFieldOption::make()
                ->label(trans('plugins/course::course.tax_amount'))
            )
            ->add(
                'language',
                MultiCheckListField::class,
                MultiChecklistFieldOption::make()
                    ->label(trans('plugins/course::course.language'))
                    ->choices($fields)
                    ->required()
            )
            // ->add('tags', TagField::class, [
            //     'label' => trans('plugins/course::course.tags'),
            //     'value' => $tags,
            //     'attr' => [
            //         'placeholder' => trans('plugins/course::course.tags'),
            //         'data-url' => route('course-tag.all'),
            //     ],
            // ])
            ->add(
                'keywords',
                TextField::Class,
                TextFieldOption::make()
                ->label(trans('plugins/course::course.keywords'))
            );
    }
}
