<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id')->index();
            $table->string('session_id')->nullable()->index();
            $table->ipAddress('ip_address')->nullable();
            $table->string('country_code', 2)->nullable()->index();
            $table->string('country_name')->nullable();
            $table->string('user_agent')->nullable();
            $table->enum('device_type', ['desktop', 'mobile', 'tablet', 'other'])->default('other')->index();
            $table->string('browser')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};


