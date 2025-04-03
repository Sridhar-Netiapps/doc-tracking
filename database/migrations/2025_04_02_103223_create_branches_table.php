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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('region_id', 100);
            $table->string('region_name', 100);
            $table->string('business_type');
            $table->string('rbi_classification');
            $table->string('branch_office_type');
            $table->string('underbanked_district');
            $table->date('operational_date')->nullable();
            $table->string('population_tier');
            $table->string('population_group');
            $table->string('address_part1');
            $table->string('address_part2');
            $table->string('address_part3');
            $table->string('pincode');
            $table->string('city');
            $table->string('district_id');
            $table->string('state_id');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('post_office');
            $table->string('micr');
            $table->string('ifsc')->nullable();
            $table->string('opening_fy')->nullable();
            $table->string('branch_type')->nullable();
            $table->string('old_name')->nullable();
            $table->string('status', 100)->default('Active');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
