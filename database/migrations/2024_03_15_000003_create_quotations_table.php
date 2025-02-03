<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 15, 2);
            $table->foreignId('currency_id')->constrained('currencies');
            $table->timestamps();
            $table->softDeletes();

            $table->index('currency_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
}; 