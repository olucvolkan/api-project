<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('age_loads', function (Blueprint $table) {
            $table->id();
            $table->integer('from_range')->unsigned();
            $table->integer('max_range')->unsigned();
            $table->float('load', 8, 2);
            $table->timestamps();
            $table->softDeletes();

            // Add index for better query performance
            $table->index(['from_range', 'max_range']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('age_loads');
    }
}; 