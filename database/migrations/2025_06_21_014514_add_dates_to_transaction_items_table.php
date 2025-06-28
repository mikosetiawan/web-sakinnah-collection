<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            if (!Schema::hasColumn('transaction_items', 'pickup_date')) {
                $table->date('pickup_date')->nullable()->after('price');
            }
            if (!Schema::hasColumn('transaction_items', 'event_date')) {
                $table->date('event_date')->nullable()->after('pickup_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            if (Schema::hasColumn('transaction_items', 'pickup_date')) {
                $table->dropColumn('pickup_date');
            }
            if (Schema::hasColumn('transaction_items', 'event_date')) {
                $table->dropColumn('event_date');
            }
        });
    }
};