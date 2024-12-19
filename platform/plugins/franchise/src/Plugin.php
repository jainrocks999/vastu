<?php

namespace Botble\Franchise;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('franchise_assign_booking_tbl');
        Schema::dropIfExists('franchise_booking_payment_tbl');
        Schema::dropIfExists('franchise_booking_appointment_tbl');
        Schema::dropIfExists('franchise_services_relation_tbl');
        Schema::dropIfExists('franchise_services_tbl');
        Schema::dropIfExists('franchise_tbl');

        Setting::delete([
            'franchise_assign_booking_tbl',
            'franchise_booking_payment_tbl',
            'franchise_booking_appointment_tbl',
            'franchise_services_relation_tbl',
            'franchise_services_tbl',
            'franchise_tbl'
        ]);
    }
}
