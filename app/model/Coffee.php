<?php declare(strict_types=1);
class Coffee extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct(){
		$table_name  = 'coffee';
		$model = [
			'id' => [
				'type'    => OCore::PK,
				'comment' => 'Id único de cada día que se ha bajado al café'
			],
			'd' => [
				'type'     => OCore::NUM,
				'comment'  => 'Día que se ha bajado al café',
				'nullable' => false
			],
			'm' => [
				'type'     => OCore::NUM,
				'comment'  => 'Mes que se ha bajado al café',
				'nullable' => false
			],
			'y' => [
				'type'     => OCore::NUM,
				'comment'  => 'Año que se ha bajado al café',
				'nullable' => false
			],
			'id_person' => [
				'type'     => OCore::NUM,
				'comment'  => 'Id de la persona que ha pagado',
				'nullable' => false
			],
			'special' => [
				'type'     => OCore::BOOL,
				'comment'  => 'Indica si es un día especial (viernes de pintxo)',
				'nullable' => false
			],
			'created_at' => [
				'type'    => OCore::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OCore::UPDATED,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
	}

	/**
	 * Ordainduta dagoela adierazteko
	 */
	private bool $isPaid = false;

	/**
	 * Gordetzeko ordainduta badago
	 *
	 * @param int $isPaid Ordainduta dagoen adierazten du (1 bai 0 ez)
	 *
	 * @return void
	 */
	public function setIsPaid(int $isPaid): void {
		$this->isPaid = ($isPaid==1);
	}

	/**
	 * Lortu ordainduta dagoen
	 *
	 * @return bool Ordainduta badago
	 */
	public function getIsPaid(): bool {
		return $this->isPaid;
	}

	/**
	 * Kafe baten parte hartu duten pertsona zerrenda
	 */
	private ?array $people = null;

	/**
	 * Lortukafe baten parte hartu duten pertsona zerrenda
	 *
	 * @return array Pertsona zerrenda
	 */
	public function getPeople(): array {
		if (is_null($this->people)) {
			$this->loadPeople();
		}
		return $this->people;
	}

	/**
	 * Lortu kafe baten parte hartu duten pertsonen id-ak
	 *
	 * @return array Pertsonen id zerrenda
	 */
	public function getPeopleIds(): array {
		$list = [];
		foreach ($this->getPeople() as $went) {
			array_push($list, $went->get('id_person'));
		}
		return $list;
	}

	/**
	 * Gorde kafe baten parte hartu duten pertsonen zerrenda
	 *
	 * @param array Pertsona zerrenda
	 *
	 * @return void
	 */
	public function setPeople(array $people): void {
		$this->people = $people;
	}

	/**
	 * Kafe baten parte hartu duten pertsonen zerrenda kargatu
	 *
	 * @return void
	 */
	public function loadPeople(): void {
		$sql = "SELECT * FROM `went` WHERE `id_coffee` = ?";
		$this->db->query($sql, [$this->get('id')]);

		$people = [];
		while ($res=$this->db->next()) {
			$went = new Went();
			$went->update($res);
			$went->loadPerson();
			array_push($people, $went);
		}

		$this->setPeople($people);
	}

	/**
	 * Kafea ordaindu duen pertsona
	 */
	private ?Person $paid = null;

	/**
	 * Lortu kafea ordaindu duen pertsona
	 *
	 * @return Person Kafea ordaindu 
	 */
	public function getPaid(): Person {
		if (is_null($this->paid)) {
			$this->loadPaid();
		}
		return $this->paid;
	}

	/**
	 * Kafea ordaindu duena gorde
	 *
	 * @param Person Kafea ordaindu duen pertsona
	 *
	 * @return void
	 */
	public function setPaid(Person $paid): void {
		$this->paid = $paid;
	}

	/**
	 * Kargatu kafea ordaindu duen pertsona
	 *
	 * @return void
	 */
	public function loadPaid(): void {
		$person = new Person();
		if (!is_null($this->get('id_person'))) {
			$person->find(['id'=>$this->get('id_person')]);
		}
		$this->setPaid($person);
	}

	/**
	 * Eguneratu kafe batera joan diren pertsonak eta nor ordaindu duen
	 *
	 * @param int $id_pay Kafea ordaindu duen pertsonaren id-a
	 *
	 * @param array $list Kafe batera joan diren pertsonen zerrenda
	 *
	 * @return void
	 */
	public function updateWent(int $id_pay, array $list): void {
		$sql = "DELETE FROM `went` WHERE `id_coffee` = ?";
		$this->db->query($sql, [$this->get('id')]);

		foreach ($list as $id) {
			$went = new Went();
			$went->set('id_person', $id);
			$went->set('id_coffee', $this->get('id'));
			$went->set('pay', ($id_pay==$id));
			$went->save();

			$person = new Person();
			$person->find(['id'=>$id]);
			$person->updateNumbers();
		}
	}

	/**
	 * Kafe bat eta joan diren pertsonak ezabatu
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$sql = "DELETE FROM `went` WHERE `id_coffee` = ?";
		$this->db->query($sql, [$this->get('id')]);

		$this->delete();
	}
}