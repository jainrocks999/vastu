<?php

namespace App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Models\Review;
use DB;

class ApiAddressController extends Controller
{
    //Product list Api
    public function fetchAddress(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|max:255', 
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $data = [];
                $addresses = Address::query()
                            ->where('customer_id', $request->user_id)
                            ->paginate(10);
            }
            $response = ['status'=>200,'data'=>$addresses,'msg'=>"Fetch Address list."];
            return response($response, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }

    //Customer address delete 
    public function storeCustomerAddress(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255', 
                'email' => 'nullable|string|email|max:255',
                'phone' => 'required|numeric|digits:10',
                'country' => 'required|string|max:255', 
                'state' => 'required|string|max:255', 
                'city' => 'required|string|max:255', 
                'address' => 'required|string|max:255', 
                'zip_code' => 'nullable|string|max:255',
                'is_default' => 'nullable|string|max:255',  
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{

                $addresses = Address::where('customer_id', $request->user_id)->where('is_default',1)->first();
                $address = new Address();
                $address->name = $request->name;
                $address->email = $request->email;
                $address->phone = $request->phone;
                $address->country = $request->country;
                $address->state = $request->state;
                $address->city = $request->city;
                $address->address = $request->address;
                $address->apartment = $request->apartment;
                $address->customer_id = $request->user_id;
                if(isset($addresses) && $addresses->is_default == 1){
                    $address->is_default = 0;
                }else{
                    $address->is_default = 1;
                }
                $address->created_at = date('Y-m-d H:i:s');
                $address->zip_code = $request->zip_code;
                $address->save();
                $response = ['status'=>200,'data'=>$address,'msg'=>"Address created successfully."];
            }
            return response($response, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }

    //Customer address update
    public function updateCustomerAddress(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'customer_address_id' => 'required|integer|max:255', 
                'name' => 'required|string|max:255', 
                'email' => 'nullable|string|email|max:255',
                'phone' => 'required|numeric|digits:10',
                'country' => 'required|string|max:255', 
                'state' => 'required|string|max:255', 
                'city' => 'required|string|max:255', 
                'address' => 'required|string|max:255', 
                'zip_code' => 'nullable|string|max:255',
                'is_default' => 'nullable|string|max:255',  
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
              
                $address = Address::where('customer_id',$request->user_id)->where('id',$request->customer_address_id)->first();
                if(isset($address) && !empty($address)){
                    $address->name = $request->name;
                    $address->email = $request->email;
                    $address->phone = $request->phone;
                    $address->country = $request->country;
                    $address->state = $request->state;
                    $address->city = $request->city;
                    $address->address = $request->address;
                    $address->customer_id = $request->user_id;
                    $address->is_default = $request->is_default;
                    $address->created_at = date('Y-m-d H:i:s');
                    $address->zip_code = $request->zip_code;
                    $address->save();
                    $response = ['status'=>200,'data'=>$address,'msg'=>"Address updated successfully."];
                }else{
                    $response = ['status'=>400,'data'=>$address,'msg'=>"Address not found."];
                }
                return response($response, 200);
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }

    //Customer address delete
    public function deleteCustomerAddress(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|max:255', 
                'customer_address_id' => 'required|integer|max:255', 
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $addresses = Address::where('id',$request->customer_address_id)->where('customer_id',$request->user_id)->delete();
            }
            $response = ['status'=>200,'data'=>$addresses,'msg'=>"Address remove successfully."];
            return response($response, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }

    //Customer product reviews
    public function getProductReviews(ProductInterface $productRepository,Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|max:255', 
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $reviews = Review::query()
                    ->where('customer_id', $request->user_id)
                    ->whereHas('product', function ($query) {
                        $query->wherePublished();
                    })
                    ->with(['product', 'product.slugable'])
                    ->orderByDesc('ec_reviews.created_at')
                    ->paginate(12);
        
                $products = $productRepository->productsNeedToReviewByCustomer($request->user_id);
                $params = [];
                $params['reviews'] = $reviews;
                $params['product'] = $products;
            }
            $response = ['status'=>200,'data'=>$params,'msg'=>"Address remove successfully."];
            return response($response, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }

}