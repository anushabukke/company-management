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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('entity_no', 125);
            $table->date('date_of_incorporation');
            $table->string('name', 125);
            $table->string('entity_no_and_name', 125)->nullable();
            $table->unsignedBigInteger('jurisdiction_id');
            $table->string('status', 125);

            // Define foreign key constraints if needed
            // $table->foreign('jurisdiction_id')->references('id')->on('jurisdictions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
