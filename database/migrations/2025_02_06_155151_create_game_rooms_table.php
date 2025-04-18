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
		Schema::create('game_rooms', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('game_id');
			$table->string('name');
			$table->string('max_users')->default(0);
			$table->enum('status', ['WAITING', 'IN_PROGRESS'])->default('WAITING');
			$table->timestamps();

			$table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('game_rooms');
	}
};
