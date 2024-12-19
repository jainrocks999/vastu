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
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        try{    
            $validator = Validator::make($request->all(), [
            'phone_no' => 'nullable|numeric|digits:10',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }
            $user = Customer::where('phone', $request->phone_no)
                        ->where('status', 'activated')
                        ->first();                
            if($user) {
                if($user->type == 0){
                    $user_type = 'customers';
                }else if($user->type == 1){
                    $user_type = 'student';
                }else{
                    $user_type = 'franchise';
                }
                
                $otp = mt_rand(100000, 999999);
                $token = JWTAuth::fromUser($user);
                $response = ['status'=>200,
                            'OTP' => $otp,
                            'user_id'=> $user->id,
                            'user_type'=>$user_type,
                            'token'=> $token,
                            'token_type'=>'bearer',
                            'msg'=>"OTP sent to your mobile number."];
                return response($response, 200);
            }else{
                $response = ['status'=>404,'msg'=>"User not found."];
                return response($response, 200);
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }


    //Register otp send 
    public function otpSend(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|numeric|digits:10',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $users = User::where('email',$request->email)->first();
                if(!empty($users) && $users->email == $request->email){
                    $response = ['status'=>400,'msg'=>"User Email already register."];
                    return response($response, 200);
                }else{
                    $customer = Customer::where('phone',$request->phone)->first();
                    if(!empty($customer) && $customer->phone == $request->phone){
                        $response = ['status'=>400,'msg'=>"User already register."];
                        return response($response, 200);
                    }
                    $otp = mt_rand(1000, 9999);
                    $response = ['status'=>200,'OTP'=>$otp,'msg'=>"OTP sent to your mobile number."];
                    return response($response, 200);
                }
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    
    
    //User profiles list  
    public function userProfileList(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $customer = Customer::where('id',$request->user_id)->first();
                if(!empty($customer)){
                    $response = ['status'=>200,'data'=>$customer,'msg'=>"User profiles lists."];
                    return response($response, 200);
                }else{
                    $response = ['status'=>400,'msg'=>"User not found."];
                    return response($response, 200);
                }
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    
    //Register user
    public function userRegister(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'nullable|string|email|max:255',
                'phone' => 'required|numeric|digits:10',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $users = User::where('email',$request->email)->first();
                if(isset($users) && !empty($users)){
                    $response = ['status'=>400,'msg'=>"User Email already register."];
                    return response($response, 200);
                }else{
                    $customer = Customer::where('phone',$request->phone)->first();
                    if(isset($customer) && !empty($customer)){
                        $response = ['status'=>400,'msg'=>"User phone already register."];
                        return response($response, 200);
                    }
                    $role_id = 2;
                    $roles = Role::where('id',$role_id)->first();
                    $pUser = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make('123456'), 
                        'username' => $request->name,
                        'super_user' => 0,
                        'manage_supers' => 0,
                        'permissions' => $roles->permissions
                    ];
                    $users = User::create($pUser);
                    if(!empty($users)){
                        $pRole = [
                        'user_id' => $users['id'], 
                        'role_id' => $role_id,
                        'created_at' => date('y-m-d H:i:s')
                        ];
                        $roles = DB::table('role_users')->insert($pRole);
                        $pCustomer = [
                            'name' => $request->name,
                            'email' => $request->email,
                            'password' => Hash::make('123456'), // Consider using a generated password or asking the user to input one
                            'phone' => $request->phone,
                            'created_at' => date('y-m-d H:i:s'),
                            'status' => 'activated',
                            'is_vendor'=> 0
                        ];
                        $customers = Customer::create($pCustomer);
                        if(isset($customers['name']) && !empty($customers['name'])){
                            $token = JWTAuth::fromUser($customers);
                            $response = ['status'=>200,'token'=>$token,'data'=>$customers,'msg'=>"OTP sent to your mobile number."];
                            return response($response, 200);
                        }else{
                            $response = ['status'=>500,'msg'=>"Something went to wrong"];
                            return response($response, 500);
                        }
                    }else{
                        $response = ['status'=>500,'msg'=>"Something went to wrong"];
                        return response($response, 500);
                    }
                }
            }
        } catch (\Exception $e) {
            dd($e);
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }


    //profile user
    public function profileUpdate(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|max:255', 
                'name' => 'nullable|string|max:255',
                'dob' => 'nullable|string|max:255',
                'phone' => 'nullable|numeric|digits:10',
                'old_password' => 'nullable|min:6',
                'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'min:6'
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                    $customer = Customer::where('id',$request->user_id)->first();
                    if(!empty($customer)){
                        $user = User::where('email',$customer->email)->first();
                        if(!empty($user)){
                            if(isset($request->name) && !empty($request->name)){
                                $user->username = $request->name; 
                            }
                            if(isset($request->old_password) && !empty($request->old_password)){
                                if(Hash::check($request->old_password, $user->password)){
                                    if(isset($request->password) && !empty($request->password)){
                                    $user->password = Hash::make($request->password); 
                                    }
                                }else{
                                    $response = ['status'=>400,'msg'=>"Old password is incorrect."];
                                    return response($response, 200);
                                }
                            }
                            $user->save();
                        }
                        if(isset($request->name) && !empty($request->name)){
                            $customer->name = $request->name; 
                        }
                        if(isset($request->email) && !empty($request->email)){
                            $customer->email = $request->email; 
                        }
                        if(isset($request->password) && !empty($request->password)){
                            if(Hash::check($request->old_password, $customer->password)){
                                if(isset($request->password) && !empty($request->password)){
                                $customer->password = Hash::make($request->password); 
                                }
                            }else{
                                $response = ['status'=>400,'msg'=>"Old password is incorrect."];
                                return response($response, 200);
                            }
                        }
                        if(isset($request->dob) && !empty($request->dob)){
                            $customer->dob = $request->dob; 
                        }
                        if(isset($request->phone) && !empty($request->phone)){
                            $customerPhones = Customer::where('phone',$request->phone)->first();
                            if(isset($customerPhones) && !empty($customerPhones->phone)){
                                $response = ['status'=>200,'msg'=>"Phone number already exist."];
                                return response($response, 200);
                            }else{
                                $customer->phone = $request->phone; 
                            }
                        }
                        if(isset($request->updated_at) && !empty($request->updated_at)){
                            $customer->updated_at = date('Y-m-d H:i:s'); 
                        }
                        $customer->save();
                    }
                    if($customer){
                        if(isset($request->old_password) && !empty($request->old_password)){
                            $response = ['status'=>200,'data'=>$customer,'msg'=>"Password update successfully."];
                        }else{
                            $response = ['status'=>200,'data'=>$customer,'msg'=>"Profile update successfully."];
                        }
                        return response($response, 200);
                    }else{
                        $response = ['status'=>500,'msg'=>"Something went to wrong"];
                        return response($response, 500);
                    }
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    
    
    //Home slider
    public function homeSlider(Request $request) {
        try {
            $simpleSlider = SimpleSlider::with('sliderItems')->get();
            if(!empty($simpleSlider)){
                $response = ['status'=>200,'data'=>$simpleSlider,'msg'=>"Home slider lists."];
                return response($response, 200);
            }else{
                $response = ['status'=>400,'msg'=>"Home slider not found."];
                return response($response, 200);
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    

}
