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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn("courier_name");
            $table->integer("courier_id")->references("id")->on("couriers")->nullable()->after('details');
            $table->integer("branch_id")->references("id")->on("branches")->after('details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string("courier_name");
            $table->dropColumn(['courier_id', 'branch_id']);
        });
    }
};
