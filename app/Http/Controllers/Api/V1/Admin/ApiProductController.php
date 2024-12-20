<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Wishlist;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\ProductCategoryHelper;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Services\Products\GetProductService;
use DB;
use Botble\Ecommerce\Models\Review;


class ApiProductController extends Controller
{
    //Product list Api
    public function fetchProduct(Request $request){
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
                $products = Product::get()->toArray();
                if(isset($products) && !empty($products)){ 
                    foreach($products as $product){
                        $wishlistProducts = Wishlist::where('customer_id', $request->user_id)->where('product_id', $product['id'])->first();
                        if(isset($wishlistProducts) && !empty($wishlistProducts)){
                            $product['is_wishlist'] = 1;
                        }else{
                            $product['is_wishlist'] = 0;
                        }
                        $data[] = $product;
                    }
                }
            }
            $response = ['status'=>200,'data'=>$data,'msg'=>"Fetch Product list."];
            return response($response, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }

    //Product list Api
    public function fetchSingleProduct(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|integer|max:255', 
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $data = [];
                $products = Product::with(['productCollections','crossSales'])->where('id',$request->product_id)->first();
                $reviews = Review::where('product_id',$request->product_id)->get();
                if(isset($reviews) && !empty($reviews)){
                    $param = [];
                    foreach($reviews as $review){
                     $param['rating'] = $review->star;
                     $customers = Customer::where('id',$review->customer_id)->first();
                     $param['customer_name'] = $customers->name;
                     $param['customer_email'] = $customers->email;
                     $param['customer_image'] = $customers->avatar;
                     $dataTwo[] = $param;
                    }
                     $products->reviews = isset($dataTwo) ? $dataTwo : [] ;
                }
                $data[] = $products;
            }
            $response = ['status'=>200,'data'=>$data,'msg'=>"Fetch single product details."];
            return response($response, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }

    //WishList Product list Api
    public function fetchWishlistProduct(Request $request){
    try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|max:255'
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $wishlistProducts = Wishlist::where('customer_id',$request->user_id)->get();
                $products = Product::where('id',$request->product_id)->get();
                $data = [];
              //  dd($wishlistProducts);
                if(isset($wishlistProducts) && !empty($wishlistProducts)){ 
                    foreach($wishlistProducts as $wishlistProduct){
                        $products = Product::where('id',$wishlistProduct->product_id)->first();
                        if(isset($products) && !empty($products)){
                            $products->is_wishlist = 1;
                            $data[] = $products;
                        }else{
                            $data = [];
                        }
                    }
                }
            }
            $response = ['status'=>200,'data'=>$data,'msg'=>"Fetch Wishlist product."];
            return response($response, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }

    //wishlist Product Add api 
    public function postWishlistProduct(Request $request){
    try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|max:255',
                'product_id' => 'required|integer|max:255',
                'is_add' => 'required|integer|max:255'
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $wishlistProducts = Wishlist::where('customer_id',$request->user_id)->where('product_id',$request->product_id)->first();
                if(isset($wishlistProducts) && !empty($wishlistProducts)){
                        $wishlistProducts = Wishlist::where('customer_id',$request->user_id)->where('product_id',$request->product_id)->delete();
                        $response = ['status'=>200,'msg'=>"Wishlist product remove successfully."];
                }else{
                    $wishlistProducts = new Wishlist();
                    $wishlistProducts->customer_id = $request->user_id;
                    $wishlistProducts->product_id = $request->product_id;
                    $wishlistProducts->save();
                    $response = ['status'=>200,'msg'=>"Wishlist product added successfully."];
                }
            }
            return response($response, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }


    //product category Add api 
    public function remedies(Request $request){
        try{
            $productCategory = ProductCategoryHelper::getActiveTreeCategories();
            $remediesCategoryBanner = SimpleSlider::with('sliderItems')->where('key','remedies-categories-banner')->where('status','published')->get();
            if(isset($productCategory) && !empty($productCategory) || isset($remediesCategoryBanner) && !empty($remediesCategoryBanner)){
                $data['remedies_categories_banner'] = $remediesCategoryBanner;
                $data['remedies_categories'] = $productCategory;
                $response = ['status'=>200,'data'=>$data,'msg'=>"Fetch product category list."];
            }else{
                $response = ['status'=>400,'msg'=>"Product category empty."];
            }
            return response($response, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    
    //product category by product
    public function remediesByProduct(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'category_id' => 'required|integer|max:255',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $productCategorys = ProductCategory::with('products')->where('id',$request->category_id)->get('id');
                $params = [];
                $paramProduct = [];
                $data = [];
                if(!empty($productCategorys)){
                    foreach($productCategorys as $productCategory){
                        foreach($productCategory->products as $itemsproducts){ 
                            $params['id'] = $itemsproducts->id;
                            $params['category_id'] = $request->category_id;
                            $params['name'] = $itemsproducts->name;
                            $params['short_description'] = $itemsproducts->short_description;
                            $params['description'] = $itemsproducts->description;
                            $params['content'] = $itemsproducts->content;
                            $params['status'] = $itemsproducts->status;
                            $params['images'] = $itemsproducts->images;
                            $params['price'] = $itemsproducts->price;
                            $params['sale_price'] = $itemsproducts->sale_price;
                            $reviews = Review::where('product_id',$itemsproducts->id)->first();
                            $params['rating'] = isset($reviews) ? $reviews->star : 1 ;
                            $wishlistProducts = Wishlist::where('product_id',$itemsproducts->id)->first();
                            if(isset($wishlistProducts) && !empty($wishlistProducts)){
                                $params['is_wishlist'] = 1;
                            }else{
                                $params['is_wishlist'] = 0;
                            }
                            $paramProduct[] = $params;
                        }
                    }
                }
                if(isset($paramProduct) && !empty($paramProduct)){
                    $response = ['status'=>200,'data'=>$paramProduct,'msg'=>"Fetch product category successfully."];
                }else{
                    $response = ['status'=>400,'msg'=>"Product category empty."];
                }
            }
            return response($response, 200);
        } catch (\Exception $e) {
            dd($e);
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    
    
    //brand by product
    public function productBrand(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|max:255',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $productBrand = Brand::get();
                if(isset($productBrand) && !empty($productBrand)){
                    $response = ['status'=>200,'data'=>$productBrand,'msg'=>"Fetch product brand successfully."];
                }else{
                    $response = ['status'=>400,'msg'=>"Product Brand empty."];
                }
            }
            return response($response, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    
    
    
    //product brand by product
    public function brandByProduct(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|max:255',
                'brand_id' => 'required|integer|max:255',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $productBrands = Brand::with('products')->where('id',$request->brand_id)->get('id');
                $params = [];
                $paramProduct = [];
                if(!empty($productBrands)){
                    foreach($productBrands as $productBrand){
                        foreach($productBrand->products as $itemsproducts){ 
                            $params['id'] = $itemsproducts->id;
                            $params['brand_id'] = $request->brand_id;
                            $params['name'] = $itemsproducts->name;
                            $params['description'] = $itemsproducts->description;
                            $params['content'] = $itemsproducts->content;
                            $params['status'] = $itemsproducts->status;
                            $params['images'] = $itemsproducts->images;
                            $params['price'] = $itemsproducts->price;
                            $params['sale_price'] = $itemsproducts->sale_price;
                            $params['reviews_avg'] = $itemsproducts->reviews_avg;
                            $wishlistProducts = Wishlist::where('product_id',$itemsproducts->id)->first();
                            if(isset($wishlistProducts) && !empty($wishlistProducts)){
                                $params['is_wishlist'] = 1;
                            }else{
                                $params['is_wishlist'] = 0;
                            }
                            $paramProduct[] = $params;
                        }
                    }
                }
                if(isset($paramProduct) && !empty($paramProduct)){
                    $response = ['status'=>200,'data'=>$paramProduct,'msg'=>"Fetch product brand successfully."];
                }else{
                    $response = ['status'=>400,'msg'=>"Product category empty."];
                }
            }
            return response($response, 200);
        } catch (\Exception $e) {
            dd($e);
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
    
    //Product search
    public function ajaxSearchProducts(Request $request, GetProductService $productService)
    {
        try{
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|max:255',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }else{
                $request->merge(['num' => 12]);
                $with = EcommerceHelper::withProductEagerLoadingRelations();
                $products = $productService->getProduct($request, null, null, $with);
                $paramProduct = [];
                $params = [];
                if(!empty($products)){
                    foreach($products as $product){
                        $params['id'] = $product['id'];
                        $params['name'] = $product['name'];
                        $params['description'] = $product['description'];
                        $params['content'] = $product['content'];
                        $params['status'] = $product['status'];
                        $params['images'] = $product['images'];
                        $params['price'] = $product['price'];
                        $params['sale_price'] = $product['sale_price'];
                        $params['reviews_avg'] = $product['reviews_avg'];
                        $wishlistProducts = Wishlist::where('product_id',$product->id)->first();
                        if(isset($wishlistProducts) && !empty($wishlistProducts)){
                            $params['is_wishlist'] = 1;
                        }else{
                            $params['is_wishlist'] = 0;
                        }
                        $paramProduct[] = $params;
                    }
                }
                if(isset($products) && !empty($products)){
                    $response = ['status'=>200,'data'=>$paramProduct,'msg'=>"Fetch product search."];
                }else{
                    $response = ['status'=>200,'msg'=>"Product not found."];
                }
            }
            return response($response, 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
}
