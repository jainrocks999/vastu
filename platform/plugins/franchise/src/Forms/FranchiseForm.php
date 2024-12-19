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
use Botble\Franchise\Http\Requests\FranchiseRequest;
use Botble\Franchise\Enums\FranchiseStatusEnum;
use Botble\Franchise\Models\Franchise;
use Botble\Franchise\Models\Customers;
use Botble\Franchise\Models\Services;
use Carbon\Carbon;
class FranchiseForm extends FormAbstract
{
    public function setup(): void
    {
        $country = ['india'=>'india'];
        $state = ['madhya pardesh'=>'madhya pardesh'];
        $city = ['indore'=>'indore'];

        $fields3 = [
            'male' => 'Male',
            'female' => 'Female',
        ];

        $fields4 = [
            'yes' => 'Yes',
            'no' => 'No',
        ];

        $confirmed = [
            'yes' => 'Yes',
            'no' => 'No',
        ];

        $year = [
            '1' => '1 year',
            '2' => '2 year',
            '3' => '3 year',
            '4' => '4 year',
            '5' => '5 year',
            '6' => '6 year',
            '7' => '7 year',
            '8' => '8 year',
            '9' => '9 year',
            '10' => '10 year',
            '11' => '11 year',
            '12' => '12 year',
            '13' => '13 year',
            '14' => '14 year',
            '15' => '15 year',
            '16' => '16 year',
            '17' => '17 year',
            '18' => '18 year',
            '19' => '19 year',
            '20' => '20 year'
        ];

        $level = [
            'Level 1' => 'Level 1',
            'Level 2' => 'Level 2',
            'Level 3' => 'Level 3',
            'Level 4' => 'Level 4',
            'Level 5' => 'Level 5',
        ];

        $appstatus = [
            'Pending'=>'Pending',
            'Reject'=>'Reject',
            'Accept'=>'Accept'
        ];

        $services = [
            '1' => '1',
            '2' => '2',
            '3' => '3',
        ];
     
        $email = null;
        $dob = null;
        $phone = null;
        $gender = null;
        $avatar = null;


        if ($this->getModel()) {

            /**
             * @var Franchise $course
             */
            $franchise = $this->getModel();
            $franchiseId = $franchise->id;
            $email  = $franchise->customer->email;
            $dob = $franchise->customer->dob;
            $phone = $franchise->customer->phone;
            $gender = $franchise->customer->gender;
            $avatar = $franchise->customer->avatar;
        }

        $this
        ->model(Franchise::class)
        ->setValidatorClass(FranchiseRequest::class)
       // ->template('plugins/franchise::franchise.form')
        ->columns()
        ->add('franchise_name', 
                TextField::class, 
                NameFieldOption::make()
                ->label(trans('plugins/franchise::franchise.franchise_name'))
                ->maxLength(120)
                ->required()
                ->colspan(1))
        ->add('email', 
                TextField::class, 
                EmailFieldOption::make()
                ->defaultValue($email)
                ->required()
                ->colspan(1))
        ->add(
            'phone',
            TextField::class,
            TextFieldOption::make()
                ->label(trans('plugins/franchise::franchise.phone'))
                ->placeholder(trans('plugins/franchise::franchise.phone_placeholder'))
                ->maxLength(15)
                ->defaultValue($phone)
                ->required()
                ->colspan(1)
        )
        ->add(
            'dob',  
            DatePickerField::class,
            DatePickerFieldOption::make()
                ->label(trans('plugins/franchise::franchise.dob'))
                ->defaultValue($dob)
                ->colspan(1)
        )
        ->add(
            'gender',
            RadioField::class,
            RadioFieldOption::make()
            ->label(trans('plugins/franchise::franchise.gender'))
                ->choices($fields3)
                ->required()
                ->colspan(1)
        )
        ->add(
            'is_change_password',
            OnOffField::class,
            OnOffFieldOption::make()
                ->label(trans('plugins/franchise::franchise.change_password'))
                ->attributes([
                    'data-bb-toggle' => 'collapse',
                    'data-bb-target' => '#password-collapse',
                ])
                ->defaultValue(0)
                ->colspan(1)
        )
        ->add(
            'password',
            'password',
            TextFieldOption::make()
                ->required()
                ->maxLength(60)
                ->collapsible('is_change_password', 1, ! $this->getModel()->exists || $this->getModel()->is_change_password)
                ->colspan(1)
        )
        ->add(
            'password_confirmation',
            'password',
            TextFieldOption::make()
                ->required()
                ->maxLength(60)
                ->collapsible('is_change_password', 1, ! $this->getModel()->exists || $this->getModel()->is_change_password)
                ->colspan(1)
        )
        ->add('current_location', 
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/franchise::franchise.current_location'))
                ->maxLength(120)
                ->required()
                ->colspan(1))
        ->add(
            'country',
            SelectField::class,
            SelectFieldOption::make()
                ->label(trans('plugins/franchise::franchise.country'))
                ->choices(['' => 'Select country'] + $country)
                ->addAttribute('card-body-class', 'p-0')
                ->required()->colspan(1)
        )
        ->add(
            'state',
            SelectField::class,
            SelectFieldOption::make()
                ->label(trans('plugins/franchise::franchise.state'))
                ->choices(['' => 'select state'] + $state)
                ->addAttribute('card-body-class', 'p-0')
                ->required()->colspan(1)
        )
        ->add(
            'city',
            SelectField::class,
            SelectFieldOption::make()
                ->label(trans('plugins/franchise::franchise.city'))
                ->choices(['' => 'select city'] + $city)
                ->addAttribute('card-body-class', 'p-0')
                ->required()->colspan(1)
        )
        ->add('pincode', 
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/franchise::franchise.pincode'))
                ->maxLength(120)
                ->colspan(1)
        )
        ->add(
            'service_id',
            SelectField::class,
            SelectFieldOption::make()
                ->label(trans('plugins/franchise::franchise.service_id'))
                ->choices(['' => 'select services'] + $services)
                ->addAttribute('card-body-class', 'p-0')
                ->required()->colspan(1)
        )
        ->add(
            'certificate',
            RadioField::class,
            RadioFieldOption::make()
                ->label(trans('plugins/franchise::franchise.certificate'))
                ->choices($fields4)
                ->required()
                ->colspan(1)
        )
        ->add(
            'certificate_docs',
            MediaImageField::class,
            MediaImageFieldOption::make()
                ->label(trans('plugins/franchise::franchise.certificate_docs'))
        )
        ->add('experience_field', 
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/franchise::franchise.experience_field'))
                ->colspan(1)
        )
        ->add(
            'experience_of_year',
            SelectField::class,
            SelectFieldOption::make()
                ->label(trans('plugins/franchise::franchise.experience_of_year'))
                ->choices(['' => 'Select year'] + $year)
                ->addAttribute('card-body-class', 'p-0')
                ->required()->colspan(1)
        )
        ->add('specializations', 
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/franchise::franchise.specializations'))
                ->required()
                ->colspan(1)
        )
        ->add('language', 
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/franchise::franchise.language'))
                ->required()
                ->colspan(1)
        )
        ->add(
            'level',
            SelectField::class,
            SelectFieldOption::make()
                ->label(trans('plugins/franchise::franchise.level'))
                ->choices(['' => 'Select level'] + $level)
                ->addAttribute('card-body-class', 'p-0')
                ->required()->colspan(1)
        )
        ->add('charges', 
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/franchise::franchise.charges'))
                ->required()
                ->colspan(1)
        )
        ->add(
            'short_description',
            TextareaField::class,
            TextareaFieldOption::make()
                ->label(trans('plugins/franchise::franchise.short_description'))
                ->rows(2)
                ->colspan(2)
        )
        ->add(
            'content',
            TextareaField::class,
            TextareaFieldOption::make()
                ->label(trans('plugins/franchise::franchise.content'))
                ->rows(2)
                ->colspan(2)
        )
        ->add('status', 
                SelectField::class, 
                StatusFieldOption::make()
                ->label(trans('plugins/franchise::franchise.status'))
                ->choices(FranchiseStatusEnum::labels()))
                ->setBreakFieldPoint('status')
        ->add(
            'application_req_date	',  
            DatePickerField::class,
            DatePickerFieldOption::make()
                ->label(trans('plugins/franchise::franchise.application_req_date'))
                ->required()
        )
        ->add(
            'application_status',  
            SelectField::class,
            SelectFieldOption::make()
            ->label(trans('plugins/franchise::franchise.application_status'))
            ->choices(['' => 'Select status'] + $appstatus)
            ->addAttribute('card-body-class', 'p-0')
            ->required()
        )
        ->add(
            'avatar',
            MediaImageField::class,
            MediaImageFieldOption::make()
                ->label(trans('plugins/franchise::franchise.avatar'))
                ->label('avatar')
                ->defaultValue($avatar)
        );
        // ->add(
        //     'logo',
        //     MediaImageField::class,
        //     MediaImageFieldOption::make()
        //         ->label(trans('plugins/franchise::franchise.logo'))
        //         ->label('Logo')
        // );
    }
}
