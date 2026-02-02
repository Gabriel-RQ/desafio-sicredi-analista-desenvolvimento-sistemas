<?php

use App\Models\Address;
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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('state');
            $table->string('city');
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('cpf')->index();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email');
            $table->foreignIdFor(Address::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('members');
    }
};
