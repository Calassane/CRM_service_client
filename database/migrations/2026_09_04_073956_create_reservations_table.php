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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('reference', 30)->unique();
            $table->string('vehicle');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status', 20)->index();
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
