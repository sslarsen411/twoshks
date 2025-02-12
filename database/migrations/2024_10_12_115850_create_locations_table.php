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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->string('addr', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();            
            $table->string('zip', 5)->nullable();
            $table->string('loc_phone', 20)->nullable();
            $table->string('CID', 50)->nullable();
            $table->string('PID', 50)->nullable();  
            $table->enum('status', ['active', 'inactive'])->default('active');         
            $table->string('init_rate',4)->nullable();   
            $table->string('init_rct',10)->nullable();   
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->foreign('users_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
