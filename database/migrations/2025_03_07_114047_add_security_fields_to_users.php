<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('secondary_email')->nullable();
            $table->string('secret_question1')->nullable();
            $table->string('secret_question2')->nullable();
            $table->string('secret_question3')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['secondary_email', 'secret_question1', 'secret_question2', 'secret_question3']);
        });
    }
};
