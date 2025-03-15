<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tournament_categories', function (Blueprint $table) {
            $table->decimal('category_fee', 10, 2)->default(0)->after('category_id');
        });
    }
    
    public function down()
    {
        Schema::table('tournament_categories', function (Blueprint $table) {
            $table->dropColumn('category_fee');
        });
    }
    
};
