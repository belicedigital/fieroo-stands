<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingIsHiddenToStandsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stands_types', function (Blueprint $table) {
            $table->boolean('is_hidden_from_form')->default(false)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stands_types', function (Blueprint $table) {
            $table->dropColumn('is_hidden_from_form');
        });
    }
}
