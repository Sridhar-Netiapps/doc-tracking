<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouriersTable extends Migration
{
    public function up()
    {
        Schema::create('couriers', function (Blueprint $table) {
            $table->id(); // Auto-increment ID
            $table->string('courier_id')->unique(); // Courier ID
            $table->string('name'); // Name of the courier
            $table->string('number'); // Courier contact number
            $table->text('address'); // Courier address (pickup/delivery address)
            $table->enum('status', ['Pending', 'In Transit', 'Delivered'])->default('Pending'); // Courier status
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('couriers');
    }
}

