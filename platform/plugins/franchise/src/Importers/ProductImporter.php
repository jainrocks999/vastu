<?php

namespace Botble\Franchise\Importers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Importers\ProductImporter as BaseProductImporter;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Product;
use Botble\Franchise\Facades\FranchiseHelper;
use Illuminate\Http\Request;

class ProductImporter extends BaseProductImporter
{
    public function getLayout(): string
    {
        return FranchiseHelper::viewPath('vendor-dashboard.layouts.master');
    }

    public function getValidateUrl(): string
    {
        return route('franchise.vendor.import.products.validate');
    }

    public function getImportUrl(): string
    {
        return route('franchise.vendor.import.products.store');
    }

    public function getDownloadExampleUrl(): ?string
    {
        return route('franchise.vendor.import.products.download-example');
    }

    public function getExportUrl(): ?string
    {
        return route('franchise.vendor.export.products.index');
    }

    protected function assignProductData(Request $request, Product $product): Product
    {
        $product->status = FranchiseHelper::getSetting('enable_product_approval', true)
            ? BaseStatusEnum::PENDING
            : BaseStatusEnum::PUBLISHED;

        if (EcommerceHelper::isEnabledSupportDigitalProducts() && $request->input('product_type')) {
            $product->product_type = $request->input('product_type');
        }

        $product->store_id = auth('customer')->user()->store->id;
        $product->created_by_id = auth('customer')->id();
        $product->created_by_type = Customer::class;

        return $product;
    }

    public function getUploadUrl(): string
    {
        return route('franchise.vendor.import.data-synchronize.upload');
    }
}
