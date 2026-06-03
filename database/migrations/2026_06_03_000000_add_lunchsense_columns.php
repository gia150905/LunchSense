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
        // 1. Extend Users
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('wallet_balance', 10, 2)->default(0.00)->after('password');
            $table->text('active_dietary_tags')->nullable()->after('wallet_balance');
            $table->string('favorite_cafeteria')->default('Cafeteria DKG 6, UUM')->after('active_dietary_tags');
        });

        // 2. Extend Menus
        Schema::table('menus', function (Blueprint $table) {
            $table->string('category')->default('Popular')->after('status');
            $table->integer('portions_left')->default(15)->after('category');
            $table->integer('prep_time')->default(5)->after('portions_left'); // in minutes
            $table->decimal('rating', 3, 2)->default(4.80)->after('prep_time');
            $table->integer('reviews_count')->default(50)->after('rating');
            $table->integer('calories')->default(450)->after('reviews_count');
            $table->text('ingredients')->nullable()->after('calories');
        });

        // 3. Extend Seats
        Schema::table('seats', function (Blueprint $table) {
            $table->string('zone')->default('Zone A')->after('status');
            $table->boolean('social_mode')->default(false)->after('zone');
            $table->text('current_users')->nullable()->after('social_mode'); // JSON list of table mates
            $table->text('coordinates')->nullable()->after('current_users'); // JSON grid coords e.g. {"x": 1, "y": 2}
        });

        // 4. Extend Orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('pickup_counter')->default('Counter A')->after('order_status');
            $table->string('payment_method')->default('DuitNow QR')->after('pickup_counter');
            $table->decimal('service_fee', 4, 2)->default(0.50)->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['wallet_balance', 'active_dietary_tags', 'favorite_cafeteria']);
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['category', 'portions_left', 'prep_time', 'rating', 'reviews_count', 'calories', 'ingredients']);
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->dropColumn(['zone', 'social_mode', 'current_users', 'coordinates']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pickup_counter', 'payment_method', 'service_fee']);
        });
    }
};
