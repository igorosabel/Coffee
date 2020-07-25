<?php declare(strict_types=1);
class home extends OModule {
	/**
	 * Página temporal, sitio cerrado
	 *
	 * @url /cerrado
	 * @layout blank
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function closed(ORequest $req): void {}

	/**
	 * Pantalla de inicio
	 *
	 * @url /
	 * @layout blank
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function index(ORequest $req): void {}
}