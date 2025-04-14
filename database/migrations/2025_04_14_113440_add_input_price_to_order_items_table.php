<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('input_price')->after('price')->default(0)->comment('Giá nhập tại thời điểm bán');
        });
    }

    public function down(): void {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('input_price');
        });
    }
};
