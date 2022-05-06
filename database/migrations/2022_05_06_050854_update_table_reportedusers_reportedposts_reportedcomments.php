<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableReportedusersReportedpostsReportedcomments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reported_posts', function (Blueprint $table) {
            $table->string('reported_by');
            $table->string('noreports');
            $table->json('reason')->change();
        });

        Schema::table('reported_users', function (Blueprint $table) {
            $table->string('reported_by');
            $table->string('noreports');
            $table->json('reason')->change();
        });

        Schema::table('reported_comments', function (Blueprint $table) {
            $table->string('reported_by');
            $table->string('noreports');
            $table->json('reason')->change();
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
