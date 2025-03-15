<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
{
    Schema::table('tournaments', function (Blueprint $table) {
        $table->decimal('tournament_fee', 10, 2)->default(0)->after('name'); // Default 0 means free
    });
}

public function down()
{
    Schema::table('tournaments', function (Blueprint $table) {
        $table->dropColumn('tournament_fee');
    });
}

};
