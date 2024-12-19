<?php

namespace Botble\Franchise\Forms;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\EditorFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\FieldOptions\MultiChecklistFieldOption;
use Botble\Base\Forms\FieldOptions\RadioFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\MediaImagesField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\RadioField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\TagField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\FormAbstract;
use Botble\Franchise\Http\Requests\FranchiseServiceRequest;
use Botble\Franchise\Enums\FranchiseStatusEnum;
use Botble\Franchise\Models\FranchiseService;
use Botble\Franchise\Models\Customers;
use Botble\Franchise\Models\Services;
use Carbon\Carbon;
class FranchiseServiceForm extends FormAbstract
{
    public function setup(): void
    {
        if ($this->getModel()) {

            /**
             * @var FranchiseService $course
             */
            $franchiseService = $this->getModel();
            $franchiseService = $franchiseService->id;
        }

        $this
        ->model(FranchiseService::class)
        ->setValidatorClass(FranchiseServiceRequest::class)
        // ->template('plugins/franchise::franchise-service.form')
        ->columns()
        ->add('services_name', 
                TextField::class, 
                NameFieldOption::make()
                ->label(trans('plugins/franchise::franchise-service.services_name'))
                ->maxLength(120)
                ->required()
                ->colspan(1))
        ->add('services_price', 
                TextField::class, 
                NameFieldOption::make()
                ->label(trans('plugins/franchise::franchise-service.services_price'))
                ->maxLength(120)
                ->required()
                ->colspan(1)
        )
        ->add(
            'logo',
            MediaImageField::class,
            MediaImageFieldOption::make()
                ->label(trans('plugins/franchise::franchise-service.logo'))
        )
        ->add(
            'short_description',
            TextareaField::class,
            TextareaFieldOption::make()->required()->rows(2)->colspan(2)
        )
        ->add(
            'services_desc',
                TextareaField::class,
                TextareaFieldOption::make()
                ->label(trans('plugins/franchise::franchise-service.services_desc'))
                ->rows(2)
                ->colspan(2)
        )
        ->add('status', 
                SelectField::class, 
                StatusFieldOption::make()
                ->label(trans('plugins/franchise::franchise-service.status'))
                ->choices(FranchiseStatusEnum::labels()))
                ->setBreakFieldPoint('status')

        ->add(
            'home_page_layout',
            MultiCheckListField::class,
            MultiChecklistFieldOption::make()
                ->label(trans('plugins/franchise::franchise-service.home_page_layout'))
                ->choices(['1' => 'Home Page Layout'])
                ->required()
        )
        ->add(
            'draw_menu',
            MultiCheckListField::class,
            MultiChecklistFieldOption::make()
                ->label(trans('plugins/franchise::franchise-service.draw_menu'))
                ->choices(['1' => 'Drow Menu'])
                ->required()
        )
        ->add(
            'premium_services',
            MultiCheckListField::class,
            MultiChecklistFieldOption::make()
                ->label(trans('plugins/franchise::franchise-service.premium_services'))
                ->choices(['1' => 'Premium Services'])
                ->required()
        );
    }
}
