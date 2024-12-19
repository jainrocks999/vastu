<?php
namespace App\Http\Controllers\Api\V1\Admin;
use Botble\ACL\Traits\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth; 
use Botble\Franchise\Models\Franchise;
use Botble\Franchise\Models\FranchiseService;
use Botble\ACL\Models\User;
use Botble\ACL\Models\Role;
use Botble\Ecommerce\Models\Customer;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class ApiFranchiseController extends Controller
{
    
    //Franchise Services
    public function fetchFranchiseServices(Request $request) {
        try {
            $franchiseServices = FranchiseService::get();
            if(!empty($franchiseServices)){
                $response = ['status'=>200,'data'=>$franchiseServices,'msg'=>"Franchise services lists."];
                return response($response, 200);
            }else{
                $response = ['status'=>400,'msg'=>"Franchise services list not found."];
                return response($response, 200);
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    
    

}