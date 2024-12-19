<?php

namespace Botble\Course\Services;

use Botble\Course\Facades\CourseHelper;
use Botble\Location\Models\Country;
use Illuminate\Support\Arr;

class HandleSetCountryForPaymentCheckout
{
    public function execute(array $sessionCheckoutData): void
    {
        add_filter('payment_checkout_country', function ($default) use ($sessionCheckoutData) {
            if ($country = Arr::get($sessionCheckoutData, 'country')) {
                if (CourseHelper::loadCountriesStatesCitiesFromPluginLocation()) {
                    $country = Country::query()
                        ->where('id', $country)
                        ->value('code');
                }

                return $country;
            }

            return $default;
        }, 999);
    }
}
