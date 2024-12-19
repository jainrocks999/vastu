<?php

namespace App\Http\Controllers\Api\V1\Admin;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\AdsTracking\FacebookPixel;
use Botble\Ecommerce\AdsTracking\GoogleTagManager;
use Botble\Ecommerce\Enums\DiscountTypeEnum;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\OrderHelper;
use Botble\Ecommerce\Http\Requests\UpdateCartRequest;
use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\CartTemp;
use Botble\Ecommerce\Services\HandleApplyCouponService;
use Botble\Ecommerce\Services\HandleApplyPromotionsService;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;
use DB;

class ApiCartController extends BaseController
{
    public function __construct(
        protected HandleApplyPromotionsService $applyPromotionsService,
        protected HandleApplyCouponService $handleApplyCouponService
    ) {
    }

    public function getCart(Request $request)
    {
        try{    
            $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }
            $cartItems = CartTemp::where('user_id',$request->user_id)->get();
            if(isset($cartItems) && !empty($cartItems)){
                $data = [];
                $param = [];
                foreach($cartItems as $cartItem){
                    $param['user_id'] = $cartItem->user_id;
                    $param['rowid'] = $cartItem->rowid;
                    $param['product_id'] = $cartItem->product_id;
                    $param['qty'] = $cartItem->qty;
                    $param['name'] = $cartItem->name;
                    $param['price'] = $cartItem->price;
                    $param['option'] = json_decode($cartItem->option);
                    $param['taxrate'] = $cartItem->taxrate;
                    $param['subtotal'] = $cartItem->subtotal;
                    $data[] = $param;
                }
                $response = ['status'=>200,'data' => $data,'msg'=>"cart items list."];
                return response($response, 200);
            }else{
                $response = ['status'=>404,'msg'=>"Cart empty ."];
                return response($response, 200);
            }
        } catch (\Exception $e) {
            dd($e);
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }
  

    public function storeCart(Request $request)
    {
        try{    
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'id' => 'required',
                'qty' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }

            $response = $this->httpResponse();
            $product = Product::query()->find($request->input('id'));
            if (! $product) {
                return $response
                    ->setError()
                    ->setMessage(__('This product is out of stock or not exists!'));
            }
    
            if ($product->variations->count() > 0 && ! $product->is_variation) {
                $product = $product->defaultVariation->product;
            }
    
            $originalProduct = $product->original_product;
    
            if ($product->isOutOfStock()) {
                return $response
                    ->setError()
                    ->setMessage(
                        __(
                            'Product :product is out of stock!',
                            ['product' => $originalProduct->name ?: $product->name]
                        )
                    );
            }
    
            $maxQuantity = $product->quantity;
    
            if (! $product->canAddToCart($request->input('qty', 1))) {
                return $response
                    ->setError()
                    ->setMessage(__('Maximum quantity is :max!', ['max' => $maxQuantity]));
            }
    
            $product->quantity -= $request->input('qty', 1);
    
            $outOfQuantity = false;
            foreach (Cart::instance('cart')->content() as $item) {
                if ($item->id == $product->id) {
                    $originalQuantity = $product->quantity;
                    $product->quantity = (int) $product->quantity - $item->qty;
    
                    if ($product->quantity < 0) {
                        $product->quantity = 0;
                    }
    
                    if ($product->isOutOfStock()) {
                        $outOfQuantity = true;
    
                        break;
                    }
    
                    $product->quantity = $originalQuantity;
                }
            }
    
            if (
                EcommerceHelper::isEnabledProductOptions() &&
                $originalProduct->options()->where('required', true)->exists()
            ) {
                if (! $request->input('options')) {
                    return $response
                        ->setError()
                        ->setData(['next_url' => $originalProduct->url])
                        ->setMessage(__('Please select product options!'));
                }
    
                $requiredOptions = $originalProduct->options()->where('required', true)->get();
    
                $message = null;
    
                foreach ($requiredOptions as $requiredOption) {
                    if (! $request->input('options.' . $requiredOption->id . '.values')) {
                        $message .= trans(
                            'plugins/ecommerce::product-option.add_to_cart_value_required',
                            ['value' => $requiredOption->name]
                        );
                    }
                }
    
                if ($message) {
                    return $response
                        ->setError()
                        ->setMessage(__('Please select product options!'));
                }
            }
    
            if ($outOfQuantity) {
                return $response
                    ->setError()
                    ->setMessage(__(
                        'Product :product is out of stock!',
                        ['product' => $originalProduct->name ?: $product->name]
                    ));
            }
            $cartItems = OrderHelper::handleAddCart($product, $request);
            $cartItem = Arr::first(array_filter($cartItems, fn ($item) => $item['id'] == $product->id));
           
            $param = [];
            $param['user_id'] = $request->user_id;
            $param['rowid'] = $cartItem['rowId'];
            $param['product_id'] = $cartItem['id'];
            $param['qty'] = $cartItem['qty'];
            $param['name'] = $cartItem['name'];
            $param['price'] = $cartItem['price'];
            $param['option'] = json_encode($cartItem['options']);
            $param['taxrate'] = $cartItem['tax'];
            $param['subtotal'] = $cartItem['subtotal'];
            $param['created_at'] = date('Y-m-d H:i:s');
            $data = DB::table('ec_cart_temp')->insert($param);
            
            $response->setMessage(__(
                'Added product :product to cart successfully!',
                ['product' => $originalProduct->name ?: $product->name]
            ));
    
            $responseData = [
                'status' => true,
                'content' => $cartItems,
            ];
    
            app(GoogleTagManager::class)->addToCart(
                $originalProduct,
                $cartItem['qty'],
                $cartItem['subtotal'],
            );
    
            app(FacebookPixel::class)->addToCart(
                $originalProduct,
                $cartItem['qty'],
                $cartItem['subtotal'],
            );
    
            $token = OrderHelper::getOrderSessionToken();
            $nextUrl = route('public.checkout.information', $token);
    
            if (EcommerceHelper::getQuickBuyButtonTarget() == 'cart') {
                $nextUrl = route('public.cart');
            }
    
            if ($request->input('checkout')) {
                Cart::instance('cart')->refresh();
    
                $responseData['next_url'] = $nextUrl;
    
                if ($request->ajax() && $request->wantsJson()) {
                    return $response->setData($responseData);
                }
    
                return $response
                    ->setData($responseData)
                    ->setNextUrl($nextUrl);
            }
            return $response
                ->setData([
                    ...$this->getDataForResponse(),
                    ...$responseData,
                ]);
        }catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
      
    }


    public function updateCart(Request $request)
    {
        try{    
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'rowid' => 'required',
                'qty' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }

            $cartItem = CartTemp::where('user_id',$request->user_id)->where('rowid',$request->rowid)->first(); 
            if(isset($cartItem) && !empty($cartItem)){
                $cartItem->subtotal = $request->qty * $cartItem->price;
                $cartItem->qty = $request->qty;
                $cartItem->save();
                $param['user_id'] = $cartItem->user_id;
                $param['rowid'] = $cartItem->rowid;
                $param['product_id'] = $cartItem->product_id;
                $param['qty'] = $cartItem->qty;
                $param['name'] = $cartItem->name;
                $param['price'] = $cartItem->price;
                $param['option'] = json_decode($cartItem->option);
                $param['taxrate'] = $cartItem->taxrate;
                $param['subtotal'] = $cartItem->subtotal;
                $data[] = $param;
            
            }
            $responseData = [
                'status' => 200,
                'data' => $data,
                'msg' => 'Cart item updated successfully.'
            ];
            return response($responseData, 200);
        }catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }

    public function removeCartItem(Request $request)
    {
        try{    
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'rowid' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }
            $cartItem = CartTemp::where('user_id',$request->user_id)->where('rowid',$request->rowid)->delete();
            if($cartItem){
                $results = [
                    'status' => 200,
                    'msg' => 'Cart item remove successfully.'
                ];
            }else{
                $results = [
                    'status' => 500, 'msg' => 'Something went wrong.'
                ];
            }
            return response($results, 200);
        }catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }


    public function destroyCart(Request $request)
    {
        try{    
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response =['status'=>400,'msg' => $errors[0]];
                return response($response, 200);
            }
            $cartItem = CartTemp::where('user_id',$request->user_id)->delete();
            if($cartItem){
                $results = [
                    'status' => 200,
                    'msg' => 'Cart empty successfully.'
                ];
            }else{
                $results = [
                    'status' => 500, 'msg' => 'Something went wrong.'
                ];
            }
            return response($results, 200);
        }catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['status' => 500, 'msg' => 'Something went wrong.'], 500);
        }
    }

    

    protected function getCartData(): array
    {
        $products = Cart::instance('cart')->products();

        $promotionDiscountAmount = $this->applyPromotionsService->execute();

        $couponDiscountAmount = 0;

        if ($couponCode = session('auto_apply_coupon_code')) {
            $coupon = Discount::query()
                ->where('code', $couponCode)
                ->where('apply_via_url', true)
                ->where('type', DiscountTypeEnum::COUPON)
                ->exists();

            if ($coupon) {
                $couponData = $this->handleApplyCouponService->execute($couponCode);

                if (! Arr::get($couponData, 'error')) {
                    $couponDiscountAmount = Arr::get($couponData, 'data.discount_amount');
                }
            }
        }

        $sessionData = OrderHelper::getOrderSessionData();

        if (session()->has('applied_coupon_code')) {
            $couponDiscountAmount = Arr::get($sessionData, 'coupon_discount_amount', 0);
        }

        return [$products, $promotionDiscountAmount, $couponDiscountAmount];
    }

    protected function getDataForResponse(): array
    {
        $cartContent = null;

        $cartData = $this->getCartData();
        [$products, $promotionDiscountAmount, $couponDiscountAmount] = $cartData;

        if (Route::is('public.cart.*')) {
            $crossSellProducts = get_cart_cross_sale_products(
                $products->pluck('original_product.id')->all(),
                (int) theme_option('number_of_cross_sale_product', 4)
            ) ?: collect();

            $cartContent = view(
                EcommerceHelper::viewPath('cart'),
                compact('products', 'promotionDiscountAmount', 'couponDiscountAmount', 'crossSellProducts')
            )->render();
        }

        return apply_filters('ecommerce_cart_data_for_response', [
            'count' => Cart::instance('cart')->count(),
            'total_price' => format_price(Cart::instance('cart')->rawSubTotal()),
            'content' => Cart::instance('cart')->content(),
            'cart_content' => $cartContent,
        ], $cartData);
    }
}
