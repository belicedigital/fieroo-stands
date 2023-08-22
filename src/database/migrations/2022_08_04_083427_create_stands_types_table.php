<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stands_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('stands_types_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stand_type_id');
            $table->foreign('stand_type_id')->references('id')->on('stands_types')->onDelete('cascade');
            $table->string('locale', 8)->index();
            $table->foreign('locale')->references('code')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['stand_type_id','locale']);
            $table->string('name');
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedInteger('max_number_modules')->default(0);
            $table->enum('visibility', [1, 2, 3])->nullable();
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stands_types_translations');
        Schema::dropIfExists('stands_types');
    }
}
