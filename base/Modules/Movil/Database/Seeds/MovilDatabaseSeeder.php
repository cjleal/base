<?php namespace Modules\Movil\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class MovilDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call(ApiUsuarioSeeder::class);
		$this->call(CatColoresSeeder::class);
		$this->call(EstacionesSeeder::class);
		$this->call(OcasionesSeeder::class);
		$this->call(TonoPielSeeder::class);
		$this->call(TipoPrendaSeeder::class);
		
		Model::reguard();
	}
}
