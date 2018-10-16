<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table){
            $table->integer('sso_enabled')->nullable()->after('password');
            $table->string('sso_username')->nullable()->unique()->after('password');
            $table->string('status')->after('password');
            $table->integer('login_type')->after('password');
            $table->timestamp('last_login')->after('password');
            $table->integer('role_id')->after('password');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
