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

use Botble\Base\Forms\Fields\TimePickerField;      // for time picker field
use Botble\Base\Forms\FieldOptions\RepeaterFieldOption;
use Botble\Base\Forms\Fields\RepeaterField;

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

use Botble\Course\Models\Trainer;

use Carbon\Carbon;
class TrainerForm extends FormAbstract
{
    public function setup(): void
    {
        

        $this
            ->model(Trainer::class)
            ->setValidatorClass(CourseRequest::class)   
            ->setFormOption('files', true)

            
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/course::trainer.title'))
                    ->required()
            )
            ->add(
                'subtitle',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/course::trainer.subtitle'))
                ->required()
            )
            
         
                 
            ->add(
                'image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(trans('plugins/course::trainer.image'))
            )
           
            ->add(
                'description',
                EditorField::class,
                EditorFieldOption::make()
                ->label(trans('plugins/course::trainer.description'))
                ->required()
            )
            
          
          
          
          
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');


            //     // checkbox for home page layout
            // ->add(
            //     'home_page_layout',
            //     MultiCheckListField::class,
            //     MultiChecklistFieldOption::make()
            //         ->label(trans('plugins/course::trainer.home_page_layout'))
            //         ->choices(['1' => 'Home Page Layout'])
            //         ->required()
            // );

           
            
          
   
    }
}
