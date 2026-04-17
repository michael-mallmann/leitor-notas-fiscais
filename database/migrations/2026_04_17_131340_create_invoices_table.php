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
    Schema::create('invoices', function (Blueprint $table) {
        $table->string('file_path')->nullable();
        $table->id();
        $table->string('company_name')->nullable();
        $table->string('cnpj')->nullable();
        $table->date('date')->nullable();
        $table->decimal('total_value', 10, 2)->nullable();
        $table->json('items')->nullable();
        $table->string('category')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
