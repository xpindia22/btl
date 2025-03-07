<?php
 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            // Ensure email column exists before adding secondary_email
            if (!Schema::hasColumn('players', 'email')) {
                $table->string('email')->nullable()->after('name'); 
            }

            $table->string('secondary_email')->nullable()->after('email');
            $table->string('secret_question1')->nullable()->after('password');
            $table->string('secret_question2')->nullable();
            $table->string('secret_question3')->nullable();
        });
    }

    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['secondary_email', 'secret_question1', 'secret_question2', 'secret_question3']);

            if (Schema::hasColumn('players', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};

