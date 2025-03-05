<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('email'); // ✅ Add Date of Birth
            $table->string('sex', 10)->nullable()->after('dob'); // ✅ Add Sex
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dob', 'sex']);
        });
    }
};
