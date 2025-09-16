<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestdataTable extends Migration
{
    public function up()
    {
        Schema::create('restdata', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message');
            $table->string('url');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restdata');
    }
}
