<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;

class home extends OModule {
	/**
	 * Página temporal, sitio cerrado
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/cerrado',
		layout: 'blank'
	)]
	public function closed(ORequest $req): void {}

	/**
	 * Pantalla de inicio
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/',
		layout: 'blank'
	)]
	public function index(ORequest $req): void {}
}