<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('course_category', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->text('description');
            $table->tinyInteger('order')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
        });

        Schema::create('course', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('code', 100)->nullable();
            $table->text('label1');
            $table->text('description1');
            $table->text('label2');
            $table->text('description2');
            $table->text('label3');
            $table->text('description3');
            $table->text('label4');
            $table->text('description4');
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->string('docs')->nullable();
            $table->enum('type', ['free', 'paid'])->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->text('instructor')->nullable(); 
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('language', 50)->nullable();
            $table->text('prerequisites')->nullable();
            $table->integer('enrollment_limit')->nullable();
            $table->enum('enrollment_type', ['open', 'restricted'])->nullable();
            $table->boolean('certification')->default(false);
            $table->decimal('discounts', 5, 2)->nullable();
            $table->string('tags')->nullable();
            $table->string('keywords')->nullable();
            $table->foreignId('course_category_id');
            $table->enum('status', ['published', 'draft', 'archived'])->default('published'); 
            $table->timestamps();
        });
        
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('course_id');
            $table->timestamp('enrollment_date')->useCurrent();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
        });
        
        Schema::create('course_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('course_id');
            $table->string('rating');
            $table->text('comment')->nullable();
            $table->enum('status', ['published', 'pending', 'hidden'])->default('published');
            $table->timestamps();
        });

        Schema::create('course_related_relations', function (Blueprint $table) {
            $table->id();
            $table->integer('from_course_id');
            $table->integer('to_course_id');
            $table->timestamps();
        });

        Schema::create('course_discount_course', function (Blueprint $table) {
            $table->foreignId('discount_id');
            $table->foreignId('course_id');
            $table->primary(['discount_id', 'course_id']);
        });

        Schema::create('course_discount_course_categorys', function (Blueprint $table) {
            $table->foreignId('discount_id');
            $table->foreignId('course_category_id');
            $table->primary(['discount_id', 'course_category_id']);
        });

        Schema::create('course_discount_student', function (Blueprint $table) {
            $table->foreignId('discount_id');
            $table->foreignId('student_id');
            $table->primary(['discount_id', 'student_id']);
        });

        Schema::create('course_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->string('course_title', 255);
            $table->text('course_description');
            $table->string('course_category', 100);
            $table->enum('order_status', ['pending', 'completed', 'cancelled', 'failed'])->default('pending');
            $table->decimal('amount', 10, 2);
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->string('coupon_code', 100)->nullable();
            $table->text('discount_description')->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('sub_total', 10, 2);
            $table->string('payment_method', 50)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_transaction_id', 255)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('user_id');
            $table->foreignId('course_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_category');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('course_enrollment');
        Schema::dropIfExists('course_reviews');
        Schema::dropIfExists('course_discount_course');
        Schema::dropIfExists('course_discount_course_category');
        Schema::dropIfExists('course_discount_student');
    }
};
