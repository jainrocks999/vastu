<?php

namespace Botble\Franchise\Services;

use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Models\Product;
use Botble\Franchise\Facades\FranchiseHelper;
use Exception;

class CartValidateSameStore
{
    public function handle(?Product $originalProduct = null, ?string $errorMessage = null): void
    {
        if (! FranchiseHelper::isSingleVendorCheckout()) {
            return;
        }

        $products = Cart::instance('cart')->products();

        if ($originalProduct) {
            $products->map(function (Product $product) use ($errorMessage, $originalProduct) {
                if ($product->store_id !== $originalProduct->store_id) {
                    throw new Exception($errorMessage);
                }
            });

            return;
        }

        if ($products->pluck('store_id')->unique()->count() > 1) {
            throw new Exception($errorMessage);
        }
    }
}
