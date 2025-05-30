<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('emails', function (Blueprint $table) {
        $table->id();
        $table->string('sender');
        $table->string('to');
        $table->string('cc')->nullable();
        $table->string('bcc')->nullable();
        $table->string('subject');
        $table->text('message');
        $table->enum('status', ['draft', 'sent', 'failed'])->default('draft');
        $table->timestamp('sent_at')->nullable();
        $table->timestamps();
    });
   }

    public function down(): void
    {
        //
    }

};
