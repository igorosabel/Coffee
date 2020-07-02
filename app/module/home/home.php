<?php declare(strict_types=1);
class home extends OModule {
	/**
	 * Página temporal, sitio cerrado
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	function closed(ORequest $req): void {}

	/**
	 * Pantalla de inicio
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	function index(ORequest $req): void {}
}