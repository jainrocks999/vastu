<?php

namespace Botble\Orders\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Course\Forms\CourseOrderForm;
use Botble\Course\Http\Requests\CourseRequest;
use Botble\Course\Http\Requests\CourseOrderRequest;
use Botble\Course\Models\CourseOrder;
use Botble\Course\Models\Course;
use Botble\Course\Models\CourseDiscount;
use Botble\Course\Models\RelatedCourse;
use Botble\Ecommerce\Models\Customer;
use Botble\Course\Tables\CourseOrderTable;
use Botble\Course\Facades\CourseHelper;
use Botble\Course\Traits\CourseActionsTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;

class CourseOrderController extends BaseController
{
    use CourseActionsTrait;

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/course::course-order.name'), route('course_order.index'));
    }

    public function index(CourseOrderTable $table)
    {
        $this->pageTitle('Course order list');
        return $table->renderTable();
    }

    public function course_details(Request $request){
        if ($request->isMethod('GET') && $request->ajax()) {
            $courseDetails = Course::with('category')->where('id',$request['table'])->findOrFail($request->query('table'));
            return $this
                ->httpResponse()
                ->setData(view('plugins/course::courses.partials.course-order-table.table', compact('courseDetails'))->render());
        }
    }


    public function course_order_details(Request $request){
        if ($request->isMethod('GET') && $request->ajax()) {
            $courseOrderDetails = CourseOrder::where('id',$request->course_order_id)->findOrFail($request->course_order_id);
            $courseDetails = Course::where('id',$courseOrderDetails->course_id)->findOrFail($courseOrderDetails->course_id);
            $customers = DB::table('ec_customers')->where('id',$courseOrderDetails->user_id)->first();
            return $this
                ->httpResponse()
                ->setData(view('plugins/course::courses.partials.course-order-table.course_order_table', compact('customers','courseDetails','courseOrderDetails'))->render());
        }
    }
    


    public function create()
    {
        $this->pageTitle(trans('plugins/course::course-order.create'));

        return CourseOrderForm::create()->renderForm();
    }

    public function store(CourseOrderRequest $request)
    {
        if(empty($request->input('course_id'))){
            return $this
            ->httpResponse()
            ->withUpdatedErrorMessage();
        }
        if(isset($request->coupon_code) && !empty($request->coupon_code)){
            $coupons = CourseHelper::couponCodeChecking(Auth::id(), $request->coupon_code);
            if($coupons){
                $coupon_desc = $coupons['coupon_desc'];
            }else{
                $coupon_desc = '-';
            }
        }

        $customers = Customer::where('id',$request->user_id)->first();
        $courses = Course::with('category')->where('id',$request->input('course_id'))->first();
        if(isset($request->total) && $request->total == 0 || $request->total < 0){
            $total = $request->sub_amount;
        }else if(isset($request->total) && $request->total != 0 || $request->total > 0){
            $total = $request->total;
        }

        $param = [
            'code' => '#0000'.rand(2, 6),
            'user_id' => $customers->id,
            'user_name' => isset($customers->name) ? $customers->name : null,
            'course_id' => $courses->id,
            'course_title' => isset($courses->title) ? $courses->title : '',
            'course_description' => $courses->description1,
            'course_category' => $courses->category->name,
            'status' => $request->status,
            'amount' => isset($request->sub_amount) ? $request->sub_amount : '0:00',
            'tax_amount' => isset($request->tax_amount) ? $request->tax_amount : '0:00',
            'coupon_code' => $request->coupon_code,
            'discount_description' => isset($coupon_desc) ? $coupon_desc : '',
            'discount_amount' => isset($request->discount_amt) ? $request->discount_amt : '0.00',
            'sub_total' => isset($request->sub_amount) ? $request->sub_amount : '0:00',
            'total' => isset($total) ? $total : $total,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'payment_transaction_id' => isset($request->payment_transaction_id) ? $request->payment_transaction_id : '0:00',
            'start_date' => $courses->start_date,
            'end_date' => $courses->end_date,
            'created_at' => date('Y-m-d H:i:s'),
            'update_at' => null
        ];
        $response = CourseOrder::create($param);

        //Discount coupoun code update  
        if(isset($response->id) && !empty($response->coupon_code)){
            $courseDiscount = CourseDiscount::where('code',$request->coupon_code)->first();
            if($courseDiscount){
                $usedCoupon = $courseDiscount->total_used + 1;
                CourseDiscount::where('id',$courseDiscount->id)->where('code',$request->coupon_code)->update(['total_used'=>$usedCoupon]);
            }
        }

        //Customer update
        if(isset($response->id) && !empty($response->id)){
            $customers = Customer::where('id',Auth::id())->first();
            Customer::where('id',$customers->id)->update([
                'is_vendor' => 1
            ]);
        }
        return $this
            ->httpResponse()
            ->setPreviousRoute('course_order.index')
            ->setNextRoute('course_order.edit', $response->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CourseOrder $courseOrder)
    {

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => 'course order']));
        return CourseOrderForm::createFromModel($courseOrder)->renderForm();
    }

    public function update(CourseOrderRequest $request)
    {
        if(empty($request->input('course_id'))){
            return $this
            ->httpResponse()
            ->withUpdatedErrorMessage();
        }
        if(isset($request->coupon_code) && !empty($request->coupon_code)){
            $coupons = CourseHelper::couponCodeChecking(Auth::id(), $request->coupon_code);
            if($coupons){
                $coupon_desc = $coupons['coupon_desc'];
                $param['discount_description'] =  $coupon_desc;
            }
        }

        $courses = Course::with('category')->where('id',$request->input('course_id'))->first();
        $param = [
            'course_id' => $courses->id,
            'status' => $request->status,
            'coupon_code' => isset($request->coupon_code) ? $request->coupon_code : '' ,
            'discount_amount' => isset($request->discount_amt) ? $request->discount_amt : '0.00',
            'sub_total' => isset($request->sub_amount) ? $request->sub_amount : '0:00',
            'total' => isset($request->total) ? $request->total : '0:00',
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'payment_transaction_id' => isset($request->payment_transaction_id) ? $request->payment_transaction_id : '0:00',
            'start_date' => $courses->start_date,
            'end_date' => $courses->end_date,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $response = CourseOrder::where('id',$request->course_order_id)->update($param);
         //Discount coupoun code update  
         if(isset($request->course_order_id) && !empty($request->coupon_code)){
            $courseDiscount = CourseDiscount::where('code',$request->coupon_code)->first();
            $usedCoupon = $courseDiscount->total_used + 1;
            CourseDiscount::where('id',$courseDiscount->id)->where('code',$request->coupon_code)->update(['total_used'=>$usedCoupon]);
        }

        //Customer update
        if(isset($request->course_order_id) && !empty($request->course_order_id)){
            $customers = Customer::where('id',Auth::id())->first();
            Customer::where('id',$customers->id)->update([
                'is_vendor' => 1
            ]);
        }
        return $this
            ->httpResponse()
            ->setPreviousRoute('course_order.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CourseOrder $courseOrder)
    {
        return DeleteResourceAction::make($courseOrder);
    }

    
}
