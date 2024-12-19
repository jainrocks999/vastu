<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('course_category_translations')) {
            Schema::create('course_category_translations', function (Blueprint $table) {
                $table->string('lang_code', 5);
                $table->foreignId('course_category_id');
                $table->string('name')->nullable();
                $table->text('description')->nullable();
                $table->primary(['lang_code', 'course_category_id'], 'course_category_translations_primary');
            });
        }
        
        if (!Schema::hasTable('courses_translations')) {
            Schema::create('courses_translations', function (Blueprint $table) {
                $table->string('lang_code', 5);
                $table->foreignId('course_id');
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->string('tags')->nullable();
                $table->string('keywords')->nullable();
                $table->primary(['lang_code', 'course_id'], 'courses_translations_primary');
            });
        }
        
        if (!Schema::hasTable('course_reviews_translations')) {
            Schema::create('course_reviews_translations', function (Blueprint $table) {
                $table->string('lang_code', 5);
                $table->foreignId('course_review_id');
                $table->text('comment')->nullable();
                $table->primary(['lang_code', 'course_review_id'], 'course_reviews_translations_primary');
            });
        }
        
    }

    public function down(): void
    {
        Schema::dropIfExists('course_category_translations');
        Schema::dropIfExists('courses_translations');
        Schema::dropIfExists('courses_reviews_translations');
    }
};
