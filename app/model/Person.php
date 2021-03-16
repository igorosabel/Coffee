<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Person extends OModel{
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct(){
		$table_name  = 'person';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada persona'
			],
			'name' => [
				'type'     => OModel::TEXT,
				'size'     => 50,
				'comment'  => 'Nombre de la persona',
				'nullable' => false
			],
			'num_coffee' => [
				'type'     => OModel::NUM,
				'comment'  => 'Número de veces que ha bajado al café',
				'nullable' => false,
				'default'  => 0
			],
			'num_pay' => [
				'type'     => OModel::NUM,
				'comment'  => 'Número de veces que ha pagado',
				'nullable' => false,
				'default'  => 0
			],
			'num_special' => [
				'type'     => OModel::NUM,
				'comment'  => 'Número de viernes que ha bajado',
				'nullable' => false,
				'default'  => 0
			],
			'num_special_pay' => [
				'type'     => OModel::NUM,
				'comment'  => 'Número de viernes que ha pagado',
				'nullable' => false,
				'default'  => 0
			],
			'color' => [
				'type'     => OModel::TEXT,
				'size'     => 6,
				'comment'  => 'Color para identificar a la persona',
				'nullable' => false
			],
			'score' => [
				'type'     => OModel::FLOAT,
				'comment'  => 'Puntuación de la persona',
				'nullable' => false,
				'default'  => 0
			],
			'created_at' => [
				'type'    => OModel::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OModel::UPDATED,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
	}

	/**
	 * Itzuli pertsonaren izena
	 */
	public function __toString(){
		return $this->get('name');
	}

	/**
	 * Pertsona bat kafe batera joan zen adierazteko
	 */
	private bool $did_go = false;

	/**
	 * Itzuli pertsona bat kafe batera joan bazen
	 *
	 * @return bool Pertsona bat kafe batera joan bazen
	 */
	public function getDidGo(): bool {
		return $this->did_go;
	}

	/**
	 * Gorde pertsona bat kafe batera joan bazen
	 *
	 * @param bool $did_go Pertsona bat kafe batera joan bazen
	 *
	 * @return void
	 */
	public function setDidGo(bool $did_go): void {
		$this->did_go = $did_go;
	}

	/**
	 * Eguneratu pertsona baten estatistikak
	 *
	 * @return void
	 */
	public function updateNumbers(): void {
		$sql = "SELECT COUNT(*) AS `num` FROM `went` WHERE `id_person` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$res = $this->db->next();

		$this->set('num_coffee', $res['num']);

		$sql = "SELECT COUNT(*) AS `num` FROM `went` WHERE `id_person` = ? AND `pay` = 1";
		$this->db->query($sql, [$this->get('id')]);
		$res = $this->db->next();

		$this->set('num_pay', $res['num']);

		$sql = "SELECT COUNT(*) AS `num` FROM `coffee` WHERE `id` IN (SELECT `id_coffee` FROM `went` WHERE `id_person` = ?) AND `special` = 1";
		$this->db->query($sql, [$this->get('id')]);
		$res = $this->db->next();

		$this->set('num_special', $res['num']);

		$sql = "SELECT COUNT(*) AS `num` FROM `coffee` WHERE `id` IN (SELECT `id_coffee` FROM `went` WHERE `id_person` = ? AND `pay` = 1) AND `special` = 1";
		$this->db->query($sql, [$this->get('id')]);
		$res = $this->db->next();

		$this->set('num_special_pay', $res['num']);

		$this->save();
	}
}