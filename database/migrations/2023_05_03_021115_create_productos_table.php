<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre", 200);
            $table->decimal("precio", 10, 2)->default(0);
            $table->integer("stock")->default(0);
            $table->boolean("estado")->default(true);
            $table->string("imagen")->nullable();
            $table->text("descripcion")->nullable();

            // N:1
            $table->bigInteger("categoria_id")->unsigned();
            // $table->unsignedBigInteger("category_id");
            $table->foreign("categoria_id")->references("id")->on("categorias");
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
