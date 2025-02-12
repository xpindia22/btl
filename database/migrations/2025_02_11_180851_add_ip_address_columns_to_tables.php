<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIpAddressColumnsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add ip_address column to the players table if it doesn't exist.
        Schema::table('players', function (Blueprint $table) {
            if (!Schema::hasColumn('players', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('created_by');
            }
        });

        // Add ip_address column to the tournaments table.
        Schema::table('tournaments', function (Blueprint $table) {
            if (!Schema::hasColumn('tournaments', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('updated_at');
            }
        });

        // Add ip_address column to the categories table.
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('updated_at');
            }
        });

        // Add ip_address column to the users table.
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('remember_token');
            }
        });

        // Add ip_address column to the matches table.
        Schema::table('matches', function (Blueprint $table) {
            if (!Schema::hasColumn('matches', 'ip_address')) {
                // You can either remove the after clause or use an existing column.
                $table->string('ip_address')->nullable();
                // Alternatively, you could use:
                // $table->string('ip_address')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('ip_address');
        });

        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('ip_address');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('ip_address');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ip_address');
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('ip_address');
        });
    }
}
