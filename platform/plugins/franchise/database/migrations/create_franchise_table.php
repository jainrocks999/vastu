<?php

use Botble\Franchise\Enums\WithdrawalStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('franchise_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 60)->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('dob')->nullable();  // Changed to date type for date of birth
            $table->string('gender', 20)->nullable();
            $table->string('current_location')->nullable();
            $table->string('country', 120)->nullable();
            $table->string('state', 120)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('pincode', 10)->nullable();  // Pincode should be a string, can include leading zeros
            $table->string('certificate', 120)->nullable();
            $table->string('certificate_docs', 120)->nullable();
            $table->string('experience_field', 120)->nullable();
            $table->integer('experience_of_year')->nullable();  // Changed size to integer
            $table->date('application_req_date')->nullable();  // Changed to date type
            $table->string('application_status', 120)->nullable()->default('pending');
            $table->string('specializations', 120)->nullable();
            $table->string('language', 120)->nullable();
            $table->string('level', 120)->nullable();
            $table->integer('charges')->nullable();  // Changed size to integer
            $table->string('logo')->nullable();
            $table->string('short_description', 400)->nullable();
            $table->longText('content')->nullable();
            $table->string('status', 60)->default('Active');
            $table->timestamps();
        });
        
        Schema::create('franchise_services_tbl', function (Blueprint $table) {
            $table->string('services_name')->nullable();
            $table->longText('services_desc')->nullable();
            $table->integer('services_price')->nullable();
            $table->string('status')->nullable()->default('Active');
            $table->timestamps();
        });


        // Correcting the 'franchise_services_relation_tbl' table
        Schema::create('franchise_services_relation_tbl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_id');  // Changed to unsignedBigInteger for foreign key consistency
            $table->unsignedBigInteger('services_id');  // Changed to unsignedBigInteger for foreign key consistency
            $table->timestamps();
        });

        // Correcting the 'franchise_booking_appointment_tbl' table
        Schema::create('franchise_booking_appointment_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('booking_name');
            $table->string('booking_email')->nullable();
            $table->string('booking_mobile_no')->nullable();
            $table->unsignedBigInteger('selected_franchise_id')->nullable();  // Corrected to unsignedBigInteger
            $table->unsignedBigInteger('selected_franchise_services')->nullable();  // Corrected to unsignedBigInteger
            $table->date('booking_date')->nullable();
            $table->string('booking_text');
            $table->time('booking_time')->nullable();  // Corrected data type for time
            $table->string('status')->nullable()->default('pending');
            $table->timestamps();
        });

        // Correcting the 'franchise_booking_payment_tbl' table
        Schema::create('franchise_booking_payment_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->unsignedBigInteger('booking_id')->nullable();  // Corrected to unsignedBigInteger
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->timestamp('payment_date')->nullable();  // Changed to timestamp to better represent payment date
            $table->timestamps();
        });

        // Correcting the 'franchise_assign_booking_tbl' table
        Schema::create('franchise_assign_booking_tbl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_id');  // Changed to unsignedBigInteger for foreign key consistency
            $table->unsignedBigInteger('booking_id')->nullable();  // Changed to unsignedBigInteger
            $table->string('status')->nullable();
            $table->timestamps();
        });
    
    }

    public function down(): void
    {
        Schema::dropIfExists('franchise_tbl');
        Schema::dropIfExists('franchise_services_tbl');
        Schema::dropIfExists('franchise_services_relation_tbl');
        Schema::dropIfExists('franchise_booking_appointment_tbl');
        Schema::dropIfExists('franchise_booking_payment_tbl');
        Schema::dropIfExists('franchise_assign_booking_tbl');
    }
};
