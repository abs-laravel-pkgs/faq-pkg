<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FaqsU1 extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('faqs', function (Blueprint $table) {
			$table->unsignedMediumInteger('display_order')->default(999)->after('answer');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('faqs', function (Blueprint $table) {
			$table->dropColumn('display_order');
		});
	}
}
