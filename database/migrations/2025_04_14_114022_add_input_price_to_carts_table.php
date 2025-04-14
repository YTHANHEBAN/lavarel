<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('input_price')->nullable()->after('price')->comment('Giá nhập sản phẩm, có thể null');
        });
    }

    public function down(): void {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('input_price');
        });
    }
};
