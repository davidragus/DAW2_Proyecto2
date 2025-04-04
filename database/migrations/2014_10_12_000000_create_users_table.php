<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->string('username')->unique();
			$table->string('name');
			$table->string('surname1');
			$table->string('surname2')->nullable();
			$table->string('email')->unique();
			$table->string('dni')->unique();
			$table->enum('gender', ['M', 'F', 'N'])->default('N');
			$table->string('phone_number');
			$table->string('password');
			$table->string('birthdate');
			$table->string('country_code')->nullable();
			$table->integer('chips')->default(0);
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
};
