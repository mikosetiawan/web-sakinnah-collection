<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\SchemaBlueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->default(0)->after('payment_type');
            }
            if (!Schema::hasColumn('transactions', 'remaining_payment_proof')) {
                $table->string('remaining_payment_proof')->nullable()->after('payment_proof');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
            if (Schema::hasColumn('transactions', 'remaining_payment_proof')) {
                $table->dropColumn('remaining_payment_proof');
            }
        });
    }
};