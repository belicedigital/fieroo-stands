<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveHiddeFromFormFromStandsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stands_types', function (Blueprint $table) {
            $table->dropColumn('is_hidden_from_form');
        });

        Schema::table('stands_types_translations', function (Blueprint $table) {
            $table->dropColumn('visibility');
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
