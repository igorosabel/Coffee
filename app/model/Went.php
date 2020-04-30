<?php declare(strict_types=1);
class Went extends OModel{
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct(){
		$table_name  = 'went';
		$model = [
			'id_person' => [
				'type'    => OCore::PK,
				'comment' => 'Id de la persona que ha bajado al café',
				'incr'    => false
			],
			'id_coffee' => [
				'type'    => OCore::PK,
				'comment' => 'Id de la vez que se ha bajado al café',
				'incr'    => false
			],
			'pay' => [
				'type'    => OCore::BOOL,
				'comment' => 'Indica si la persona ha pagado 1 o no 0',
				'nullable' => false,
				'default'  => 0
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
	 * Kafe batera joan zen pertsona
	 */
	private ?Person $person = null;

	/**
	 * Lortu kafera joan zen pertsona
	 *
	 * @return Person Kafera joan zen pertsona
	 */
	public function getPerson(): Person {
		if (is_null($this->person)){
			$this->loadPerson();
		}
		return $this->person;
	}

	/**
	 * Kafe batera joan zen pertsona gorde
	 *
	 * @param Person Kafera joan zen pertsona
	 *
	 * @return void
	 */
	public function setPerson(Person $person): void {
		$this->person = $person;
	}

	/**
	 * Kafera joan zen pertsona kargatu
	 *
	 * @return void
	 */
	public function loadPerson(): void{
		$person = new Person();
		$person->find(['id' => $this->get('id_person')]);

		$this->setPerson($person);
	}
}