<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->index()->constrained('companies')->cascadeOnDelete();
            $table->string("make");
            $table->string("model", 50);
            $table->string("registration_number");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
