<?php
namespace Abs\FaqPkg\Database\Seeds;

use App\Permission;
use Illuminate\Database\Seeder;

class FaqPkgPermissionSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$permissions = [
			//FAQ
			[
				'display_order' => 99,
				'parent' => null,
				'name' => 'faqs',
				'display_name' => 'Faqs',
			],
			[
				'display_order' => 1,
				'parent' => 'faqs',
				'name' => 'add-faq',
				'display_name' => 'Add',
			],
			[
				'display_order' => 2,
				'parent' => 'faqs',
				'name' => 'delete-faq',
				'display_name' => 'Edit',
			],
			[
				'display_order' => 3,
				'parent' => 'faqs',
				'name' => 'delete-faq',
				'display_name' => 'Delete',
			],

		];
		Permission::createFromArrays($permissions);
	}
}