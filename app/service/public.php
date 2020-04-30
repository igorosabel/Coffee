<?php declare(strict_types=1);
class publicService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 * Lortu urte/hilabete bateko kafeen zerrenda
	 *
	 * @param int $month Hilabete zenbakia
	 *
	 * @param int $year Urte zenbakia
	 *
	 * @return array Eskatutako urte/hilabetean egon diren kafeen zerrenda
	 */
	public function getMonthCoffees(int $month, int $year): array {
		$db = new ODB();
		$sql = "SELECT * FROM `coffee` WHERE `m` = ? AND `y` = ? ORDER BY `created_at` ASC";
		$db->query($sql, [$month, $year]);

		$list = [];
		while ($res=$db->next()) {
			$cof = new Coffee();
			$cof->update($res);
			$cof->loadPeople();
			array_push($list, $cof);
		}

		return $list;
	}

	/**
	 * Kafezaleen zerrenda osoa lortu
	 *
	 * @return array Kafezaleen zerrenda osoa
	 */
	public function getPeople(): array {
		$db = new ODB();
		$sql = "SELECT * FROM `person` ORDER BY `name`";
		$db->query($sql);

		$list = [];
		while ($res=$db->next()) {
			$person = new Person();
			$person->update($res);
			$list['person_' . $person->get('id')] = $person;
		}

		return $list;
	}

	/**
	 * Pertsona bat joan den kafeetara zerrenda osoa lortu
	 *
	 * @param int $id_person Pertsona baten id-a
	 *
	 * @return array Pertsona baten kafe zerrenda osoa
	 */
	public function getPersonCoffees(int $id_person): array {
		$db = new ODB();
		$sql = "SELECT c.*, w.`pay` FROM `coffee` c, `went` w WHERE c.`id` = w.`id_coffee` AND w.`id_person` = ? ORDER BY `created_at` DESC";
		$db->query($sql, [$id_person]);

		$list = [];
		while ($res=$db->next()) {
			$cof = new Coffee();
			$cof->update($res);
			$cof->setIsPaid((int)$res['pay']);

			array_push($list, $cof);
		}

		return $list;
	}

	/**
	 * Lortu egun/hilabete/urte zehatz bateko kafea eta joan diren pertsona zerrenda kargatu
	 *
	 * @param int $day Kafearen eguna
	 *
	 * @param int $month Kafearen hilabetea
	 *
	 * @param int $year Kafearen urtea
	 *
	 * @return array Array bat kafearekin eta joan ziren pertsona zerrendarekin
	 */
	public function getDay(int $day, int $month, int $year): array {
		$db = new ODB();
		$sql = "SELECT * FROM `coffee` WHERE `d` = ? AND `m` = ? AND `y` = ?";
		$db->query($sql, [$day, $month, $year]);
		$cof = new Coffee();
		if ($res=$db->next()){
			$cof->update($res);
		}
		else{
			$time = mktime(0, 0, 0, $month, $day, $year);
			$week_day = date('w', $time);

			$cof->set('id',0);
			$cof->set('d', $day);
			$cof->set('m', $month);
			$cof->set('y', $year);
			$cof->set('special', ($week_day=='5'));
			$cof->set('id_person', null);
		}
		$went_ids = $cof->getPeopleIds();
		$people = $this->getPeople();

		foreach ($people as $person){
			if (in_array($person->get('id'), $went_ids)){
				$person->setDidGo(true);
			}
		}

		$list = ['coffee'=>$cof, 'people'=>$people];

		return $list;
	}

	/**
	 * Lortu kafe zehatz baten datuak eta joan ziren pertsona zerrenda
	 *
	 * @param int $id Kafearen id-a
	 *
	 * @return array Array bat kaferen datuekin eta joan ziren pertsonen zerrendarekin
	 */
	public function getCoffee(int $id): array {
		$db = new ODB();
		$sql = "SELECT * FROM `coffee` WHERE `id` = ?";
		$db->query($sql, [$id]);
		$cof = new Coffee();
		$res = $db->next();
		$cof->update($res);
		$went_ids = $cof->getPeopleIds();
		$people = $this->getPeople();

		foreach ($people as $person){
			if (in_array($person->get('id'), $went_ids)){
				$person->setDidGo(true);
			}
		}

		$list = ['coffee'=>$cof, 'people'=>$people];

		return $list;
	}

	/**
	 * Ezabatu pertsona bat eta joan zen kafeetara
	 *
	 * @param Person $person Ezabatu behar den pertsona
	 *
	 * @return void
	 */
	public function deletePerson(Person $person): void {
		$db = new ODB();
		$sql = "UPDATE `coffee` SET `id_person` = NULL WHERE `id_person` = ?";
		$db->query($sql, [$person->get('id')]);

		$sql = "DELETE FROM `went` WHERE `id_person` = ?";
		$db->query($sql, [$person->get('id')]);

		$person->delete();
	}
}