<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('orders_translations')) {
            Schema::create('orders_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->foreignId('orders_id');
                $table->string('name')->nullable();
                $table->string('image')->nullable();
                $table->string('url')->nullable();

                $table->primary(['lang_code', 'orders_id'], 'orders_translations_primary');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('orders_translations');
    }
};
