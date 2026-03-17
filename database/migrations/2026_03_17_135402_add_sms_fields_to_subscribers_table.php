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
        Schema::table('subscribers', function (Blueprint $table) {
            $table->boolean('phone_opt_in')->default(true);
            $table->timestamp('opted_in_at')->nullable();
            $table->string('opt_out_token', 64)->nullable()->unique();
            $table->json('last_sent_forecast')->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->string('sms_frequency')->default('daily');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropColumn('phone_opt_in');
            $table->dropColumn('opted_in_at');
            $table->dropColumn('opt_out_token');
            $table->dropColumn('last_sent_forecast');
            $table->dropColumn('last_sent_at');
            $table->dropColumn('sms_frequency');
        });
    }
};
