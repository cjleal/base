<?php namespace Modules\Admin\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AdminDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call(perfilesSeeder::class);
		$this->call(usuariosSeeder::class);
		$this->call(preguntasSeeder::class);
		

		Model::reguard();
	}
}
