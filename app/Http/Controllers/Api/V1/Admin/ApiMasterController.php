<?php
namespace App\Http\Controllers\Api\V1\Admin;
use Botble\ACL\Traits\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Botble\ACL\Models\User;
use Botble\ACL\Models\Role;
use Botble\Ecommerce\Models\Customer;
use Botble\Franchise\Models\FranchiseService;
use Botble\Course\Models\Course;
use Botble\Course\Models\CourseCategory;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;


use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class ApiMasterController extends Controller
{
    //Home slider
    public function homeSlider(Request $request) {
        try {
            $homeSlider = SimpleSlider::with('sliderItems')->where('key','home-slider')->where('status','published')->get();
            $offerSlider = SimpleSlider::with('sliderItems')->where('key','offer-slider')->where('status','published')->get();
            $services = FranchiseService::where('home_page_layout', '!=', 0)->where('premium_services','=', 0)->where('status','activated')->get();
            $premiumServices = FranchiseService::where('home_page_layout', '!=', 0)->where('premium_services','!=', 0)->where('status','activated')->get();
            $liveCourses = CourseCategory::where('slug','live')->where('status','published')->first();
            $recodedCourses = CourseCategory::where('slug','recorded')->where('status','published')->first();
            $remedies = Product::where('home_page_layout','=', 0)->where('status','published')->get();
            $params = [];
            if(!empty($homeSlider)  ){
                $params['home-slider'] = $homeSlider;
            }
            if(!empty($offerSlider)){
                $params['offer-slider'] = $offerSlider;
            }
            if(!empty($services)){
                $services[0]->color_code = '#DEF2F4';
                $services[1]->color_code = '#F8ECD9';
                $services[2]->color_code = '#E5E2FF';
                $services[3]->color_code = '#F9E4E8';
                $services[4]->color_code = '#E3F5E4';
                $services[5]->color_code = '#CCDFF6';
                $params['services'] = $services;
            }
            if(!empty($premiumServices)){
                $params['premium_services'] = $premiumServices;
            }
            if(isset($liveCourses) && !empty($liveCourses->id)){
                $courses = Course::where('home_page_layout','!=',0)->where('course_category_id',$liveCourses->id)->get();
                $params['live_courses'] = $courses;
            }
            if(isset($recodedCourses) && !empty($recodedCourses->id)){
                $recodedcourses = Course::where('home_page_layout','!=',0)->where('course_category_id',$recodedCourses->id)->get();
                $params['recoded_courses'] = $recodedcourses;
            }
            if(isset($remedies)){
                $params['remedies'] = $remedies;
            }
            $response = ['status'=>200,'data'=>$params,'msg'=>"Home slider lists."];
            return response($response, 200);
        } catch (\Exception $e) {
            dd($e);
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    
    //Home slider
    public function drawMenu(Request $request) {
        try {
            $services = FranchiseService::where('home_page_layout', '!=', 0)->where('premium_services','=', 0)->where('status','activated')->get();
            $params = [];
            if(!empty($services)){
                $services[0]->color_code = '#DEF2F4';
                $services[1]->color_code = '#F8ECD9';
                $services[2]->color_code = '#E5E2FF';
                $services[3]->color_code = '#F9E4E8';
                $services[4]->color_code = '#E3F5E4';
                $services[5]->color_code = '#CCDFF6';
                $params['services'] = $services;
            }
            $response = ['status'=>200,'data'=>$params,'msg'=>"Draw menu lists."];
            return response($response, 200);
        } catch (\Exception $e) {
            dd($e);
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
}
