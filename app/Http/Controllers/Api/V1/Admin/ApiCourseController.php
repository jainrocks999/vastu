<?php
namespace App\Http\Controllers\Api\V1\Admin;
use Botble\ACL\Traits\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;  
use Botble\Course\Models\Course;
use Botble\Course\Models\CourseCategory;
use Botble\ACL\Models\User;
use Botble\ACL\Models\Role;
use Botble\Ecommerce\Models\Customer;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class ApiCourseController extends Controller
{
    
    //Course List
    public function fetchCourse(Request $request) {
        try {
             $validator = Validator::make($request->all(), [
                'slug' => 'required|string|max:255', 
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $courses = CourseCategory::where('slug',$request->slug)->first();
                if(isset($courses) && !empty($courses)){
                    $courses = Course::where('course_category_id',$courses->id)->get();
                    if(!empty($courses)){
                        $response = ['status'=>200,'data'=>$courses,'msg'=>"Course lists."];
                        return response($response, 200);
                    }else{
                        $response = ['status'=>400,'msg'=>"Course list not found."];
                        return response($response, 200);
                    }
                }
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    
    
    //Course List
    public function fetchCourseDetails(Request $request) {
        try {
             $validator = Validator::make($request->all(), [
                'course_id' => 'required|integer|max:255', 
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{ 
                $courses = Course::where('id',$request->course_id)->first();
                if(!empty($courses)){
                    $response = ['status'=>200,'data'=>$courses,'msg'=>"Course services lists."];
                    return response($response, 200);
                }else{
                    $response = ['status'=>400,'msg'=>"Course services list not found."];
                    return response($response, 200);
                }
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    
    

}