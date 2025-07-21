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
        // add soft deletes
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login')->nullable()->after('remember_token');
            $table->tinyInteger('status')->default(0)->after('last_login');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_login');
            $table->dropColumn('status');
            $table->dropSoftDeletes();
        });
    }
};
