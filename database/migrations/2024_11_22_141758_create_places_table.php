<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('capacity');
            $table->timestamps();
        });

        DB::table('places')->insert([
            ['id' => 1, 'name' => 'AULA 1', 'capacity' => 25],
            ['id' => 2, 'name' => 'AULA 2', 'capacity' => 27],
            ['id' => 4, 'name' => 'AULA VIRTUAL', 'capacity' => 50],
            ['id' => 5, 'name' => 'AUDITORIO', 'capacity' => 199],
            ['id' => 6, 'name' => 'AULA FUNDACIÓN', 'capacity' => 27],
            ['id' => 7, 'name' => 'AULA DE SIMULACION', 'capacity' => 100],
            ['id' => 8, 'name' => 'CAMARA GESELL', 'capacity' => 50],
            ['id' => 9, 'name' => 'HAB11-A.SIMUL.ENF.', 'capacity' => 25],
            ['id' => 10, 'name' => 'HAB15-ASIMUL.ENF.', 'capacity' => 25],
            ['id' => 11, 'name' => 'ZOOM', 'capacity' => 0],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
