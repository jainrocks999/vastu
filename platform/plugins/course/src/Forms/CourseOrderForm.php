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
use Botble\Base\Forms\MetaBox;
use Botble\Course\Http\Requests\CourseOrderRequest;
use Botble\Course\Http\Requests\CourseRequest;
use Botble\Course\Models\Course;
use Botble\Course\Models\CourseOrder;
use Botble\Course\Models\Customer;
use Botble\Course\Models\RelatedCourse;
use Botble\Course\Models\CourseCategory;
use Carbon\Carbon;
class CourseOrderForm extends FormAbstract
{
    public function setup(): void
    {
       
        $courseCategories = CourseCategory::query()->pluck('name','id')->all();
        $courses = Course::query()->pluck('title','id')->all();
        $customer = Customer::query()->pluck('name', 'id')->toArray();
        $courseId = null;
        $courseOrdersId = null;
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

        $paymentMethod = [
            'cod' => 'Cash on delivery (COD)',
            'bank_transfer' => 'Bank transfer',
            'stripe' => 'Stripe',
            'paypal' => 'PayPal',
            'razorpay' => 'Razorpay',
            'paystack' => 'Paystack',
            'mollie' => 'Mollie',
            'sslcommerz' => 'SslCommerz'
        ];

        $paymentStatus = [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'refunding' => 'Refunding',
            'refunded' => 'Refunded',
            'fraud' => 'Fraud',
            'failed' => 'Failed'
        ];

        
        $orderStatus = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'canceled' => 'Canceled',
        ];

        $formStatus = 'create';
        if ($this->getModel()) {
            $formStatus = 'edit';
            $courseOrders = $this->getModel();
            $courseOrdersId = $courseOrders->id;
            $courseId = $courseOrders->course_id;
            $selectedCategories = $courseOrders->category()->pluck('id')->all();
            $courses = Course::where('id','!=',$courseId)->pluck('title','id')->all();
            $selectedCourses = RelatedCourse::where('from_course_id',$courseId)->pluck('to_course_id')->toArray();
            $customer = Customer::pluck('name','id')->toArray();
        }
      
        $this
            ->model(CourseOrder::class)
            ->setValidatorClass(CourseOrderRequest::class)   
            ->setFormOption('files', true)
            ->addMetaBox(
                MetaBox::make('product-order-table')
                    ->title(!empty($model->id) ? '' : trans('Course Name'))
                    ->hasTable()
                    ->attributes(['class' => 'product-order-table', 'id'=>'course-order-tbl_id'])
                    ->headerActionContent(
                        view('plugins/course::courses.partials.course-order-table.header', [
                            'model' => $this->getModel(),
                            'tables' => Course::query()->pluck('title', 'id'),
                        ])->render()
                    )
                    ->content(
                            view('plugins/course::courses.partials.course-order-table.content', [
                                'model' => $this->getModel(),
                                'getTableUrl' => route('course_order.get-course-details', $courseId)
                            ])->render()
                    )
            )

            ->add(
                'user_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/course::course-order.user_name'))
                    ->choices(['' => trans('plugins/course::course-order.user_name')] + $customer)
                    ->addAttribute('card-body-class', 'p-0')
                    ->required()
            )   
            ->add('status', 
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/course::course-order.select_status'))
                    ->choices(['' => trans('plugins/course::course-order.status')] + $orderStatus)
                    ->addAttribute('card-body-class', 'p-0')
                    ->required())  
            ->setBreakFieldPoint('status')
            ->add(
                'payment_method',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/course::course-order.payment_method'))
                    ->choices(['' => trans('plugins/course::course-order.select_payment_method')] + $paymentMethod)
                    ->addAttribute('card-body-class', 'p-0')
                    ->required()
            )   
            ->add(
                'payment_status',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/course::course-order.payment_status'))
                    ->choices(['' => trans('plugins/course::course-order.payment_status')] + $paymentStatus)
                    ->addAttribute('card-body-class', 'p-0')
                    ->required()
            )
            ->add(
                'payment_transaction_id',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/course::course-order.transaction_id'))
                ->addAttribute('card-body-class', 'p-0')
                ->required()
            );
    }
}
