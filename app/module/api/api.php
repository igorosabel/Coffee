<?php declare(strict_types=1);
class api extends OModule {
	private ?publicService $public_service = null;

	function __construct(){
		$this->$public_service = new publicService();
	}

	/**
	 * Función para obtener la lista de cafés de un mes dado
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	public function getMonthCoffees(ORequest $req): void {
		$status = 'ok';
		$month  = $req->getParamInt('month');
		$year   = $req->getParamInt('year');
		$list   = [];

		if (is_null($month) || is_null($year)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$list = $this->$public_service->getMonthCoffees($month, $year);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('m', $month);
		$this->getTemplate()->add('y', $year);
		$this->getTemplate()->addPartial('list', 'api/monthCoffee', ['list'=>$list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para guardar una persona
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	public function savePerson(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$name   = $req->getParamString('name');
		$color  = $req->getParamString('color');

		if (is_null($id) || is_null($name) || is_null($color)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$person = new Person();
			if ($id!=0) {
				$person->find(['id'=>$id]);
			}
			$person->set('name',  $name);
			$person->set('color', $color);
			$person->save();

			$id = $person->get('id');
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id',     $id);
	}

	/**
	 * Función para obtener los datos de una persona
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	public function getPerson(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$name   = '';
		$color  = '';
		$list   = [];

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$person = new Person();
			if ($person->find(['id'=>$id])) {
				$name  = $person->get('name');
				$color = $person->get('color');
				$list  = $this->$public_service->getPersonCoffees($person->get('id'));
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id',     $id);
		$this->getTemplate()->add('name',   $name);
		$this->getTemplate()->add('color',  $color);
		$this->getTemplate()->addPartial('list', 'api/personCoffees', ['list'=>$list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para obtener los datos de un día concreto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	public function getDay(ORequest $req): void {
		$status = 'ok';
		$day    = $req->getParamInt('day');
		$month  = $req->getParamInt('month');
		$year   = $req->getParamInt('year');

		$id_coffee = 0;
		$special   = 0;
		$id_pay    = 0;
		$people    = [];

		if (is_null($day) || is_null($month) || is_null($year)){
			$status = 'error';
		}

		if ($status=='ok'){
			$data = $this->$public_service->getDay($day, $month, $year);

			$id_coffee = $data['coffee']->get('id');
			$special   = ($data['coffee']->get('special') ? 1 : 0);
			$id_pay    = is_null($data['coffee']->get('id_person')) ? 0 : $data['coffee']->get('id_person');
			$people    = $data['people'];
		}

		$this->getTemplate()->add('status',    $status);
		$this->getTemplate()->add('d',         $day);
		$this->getTemplate()->add('m',         $month);
		$this->getTemplate()->add('y',         $year);
		$this->getTemplate()->add('id_coffee', $id_coffee);
		$this->getTemplate()->add('special',   $special);
		$this->getTemplate()->add('id_pay',    $id_pay);
		$this->getTemplate()->addPartial('people', 'api/people', ['people'=>$people, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para guardar un café
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	public function saveCoffee(ORequest $req): void {
		$status  = 'ok';
		$id      = $req->getParamInt('id');
		$id_pay  = $req->getParamInt('id_pay');
		$d       = $req->getParamInt('d');
		$m       = $req->getParamInt('m');
		$y       = $req->getParamInt('y');
		$special = $req->getParamBool('special');
		$list    = $req->getParam('list');

		if (is_null($id) || is_null($id_pay) || is_null($d) || is_null($m) || is_null($y) || is_null($list)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cof = new Coffee();
			if ($id!=0) {
				$cof->find(['id'=>$id]);
			}
			$cof->set('d', $d);
			$cof->set('m', $m);
			$cof->set('y', $y);
			$cof->set('special', $special);
			$cof->set('id_person', ($id_pay===0) ? null : $id_pay);

			$cof->save();
			$cof->updateWent($id_pay, $list);

			OTools::runTask('score', ['silent'=>true]);
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para borrar un café
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	public function deleteCoffee(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cof = new Coffee();
			if ($cof->find(['id'=>$id])) {
				$people = $cof->getPeople();
				$cof->deleteFull();

				foreach ($people as $went) {
					$person = $went->getPerson();
					$person->updateNumbers();
				}

				OTools::runTask('score', ['silent'=>true]);
			}
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para obtener la lista de personas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	public function getPeople(ORequest $req): void {
		$this->getTemplate()->addPartial('people', 'api/people', ['people'=>$this->$public_service->getPeople(), 'extra'=>'nourlencode']);
	}

	/**
	 * Función para borrar una persona
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	public function deletePerson(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$person = new Person();
			if ($person->find(['id'=>$id])) {
				$this->$public_service->deletePerson($person);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
  
	/**
	 * Función para actualizar un café
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	public function updateCoffee(ORequest $req): void {}

	/**
	 * Función para obtener la lista de cafés de un mes dado
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	public function getMonthList(ORequest $req): void {
		$status = 'ok';
		$month  = $req->getParamInt('month');
		$year   = $req->getParamInt('year');
		$list   = [];

		if (is_null($month) || is_null($year)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$full_list = $this->$public_service->getMonthCoffees($month, $year);
			foreach ($full_list as $coffee) {
				if (!array_key_exists($coffee->get('d'), $list)) {
					$list[$coffee->get('d')] = array();
				}
				array_push($list[$coffee->get('d')], $coffee);
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('m', $month);
		$this->getTemplate()->add('y', $year);
		$this->getTemplate()->addPartial('list', 'api/monthCoffeeList', ['list'=>$list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para obtener los detalles de un café
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 *
	 * @return void
	 */
	public function getCoffee(ORequest $req): void {
		$status    = 'ok';
		$id_coffee = $req->getParamInt('id');
		$day       = '';
		$month     = '';
		$year      = '';
		$special   = 0;
		$id_pay    = 0;
		$people    = [];

		if (is_null($id_coffee)){
			$status = 'error';
		}

		if ($status=='ok'){
			$data = $this->$public_service->getCoffee($id_coffee);

			$day     = $data['coffee']->get('d');
			$month   = $data['coffee']->get('m');
			$year    = $data['coffee']->get('y');
			$special = ($data['coffee']->get('special') ? 1 : 0);
			$id_pay  = $data['coffee']->get('id_person');
			$people  = $data['people'];
		}

		$this->getTemplate()->add('status',    $status);
		$this->getTemplate()->add('d',         $day);
		$this->getTemplate()->add('m',         $month);
		$this->getTemplate()->add('y',         $year);
		$this->getTemplate()->add('id_coffee', $id_coffee);
		$this->getTemplate()->add('special',   $special);
		$this->getTemplate()->add('id_pay',    $id_pay);
		$this->getTemplate()->addPartial('people', 'api/people', ['people'=>$people, 'extra'=>'nourlencode']);
	}
}