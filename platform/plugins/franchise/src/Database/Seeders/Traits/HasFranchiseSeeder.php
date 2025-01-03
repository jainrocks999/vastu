<?php

namespace Botble\Franchise\Database\Seeders\Traits;

use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Product;
use Botble\Franchise\Models\Store;
use Botble\Franchise\Models\VendorInfo;
use Botble\Slug\Facades\SlugHelper;

trait HasFranchiseSeeder
{
    protected function createStores(array $data): array
    {
        Customer::query()->where('is_vendor', 1)->update(['is_vendor' => 0]);
        Store::query()->truncate();
        VendorInfo::query()->truncate();

        $faker = $this->fake();

        foreach (Customer::query()->whereNot('email', 'customer@botble.com')->get() as $customer) {
            $customer->is_vendor = $customer->id < 9;
            $customer->vendor_verified_at = $customer->is_vendor ? $this->now() : null;
            $customer->save();

            if ($customer->is_vendor) {
                $vendorInfo = new VendorInfo();
                $vendorInfo->bank_info = [
                    'name' => $faker->name(),
                    'number' => $faker->e164PhoneNumber(),
                    'full_name' => $faker->name(),
                    'description' => $faker->name(),
                ];
                $vendorInfo->customer_id = $customer->id;

                $vendorInfo->save();
            }
        }

        $vendorIds = Customer::query()->where('is_vendor', 1)->pluck('id');

        $stores = [];

        foreach ($data as $key => $item) {
            $item['email'] = $faker->safeEmail();
            $item['phone'] = $faker->e164PhoneNumber();
            $item['country'] = $faker->countryCode();
            $item['state'] = $faker->state();
            $item['city'] = $faker->city();
            $item['address'] = $faker->streetAddress();
            $item['description'] = $faker->text(400);

            $customerId = $item['customer_id'] ?? $vendorIds->random();

            if ($key == 0) {
                $customerId = Customer::query()->where('email', 'vendor@botble.com')->value('id');
            }

            $item['customer_id'] = $customerId;

            /**
             * @var Store $store
             */
            $store = Store::query()->create($item);

            $stores[] = $store;

            SlugHelper::createSlug($store);

            $this->createMetadata($store, $item);
        }

        $storeIds = Store::query()->pluck('id');

        foreach (Product::query()->where('is_variation', 0)->get() as $product) {
            $product->store_id = $storeIds->random();
            $product->save();
        }

        return $stores;
    }
}
