<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('tinker_commands', function (Blueprint $table) {
        $table->string('execution_time')->nullable();
        $table->string('memory_usage')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tinker_commands', function (Blueprint $table) {
            //
        });
    }
};
