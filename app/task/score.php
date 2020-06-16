<?php declare(strict_types=1);
class scoreTask extends OTask{
	/**
	 * Itzuli ataza hau egiten duenaren deskripzioa
	 */
	public function __toString(){
		return "score: Erabiltzaile bakoitzaren puntuazioa eguneratu";
	}

	/**
	 * Exekutatu puntuazioa eguneratzeko ataza
	 *
	 * @param array $options Parametroz pasatzen da ataza hau ixilik egin behar duen edo mezuak bota behar dituen
	 *
	 * @return void
	 */
	public function run(array $options=[]): void {
		if (!array_key_exists('silent', $options)) {
			$silent = false;
		}
		else {
			$silent = $options['silent'];
		}
		$db = new ODB();
		$sql = "SELECT * FROM `coffee`";
		$db->query($sql);
		$scores = [];

		while ($res=$db->next()) {
			$coffee = new Coffee();
			$coffee->update($res);

			$multiplier = 1;
			if ($coffee->get('special')) {
				$multiplier = 1.2;
			}

			$people = $coffee->getPeople();
			$score = (count($people) -1) * $multiplier;

			if (!array_key_exists($coffee->get('id_person'), $scores)) {
				$scores[$coffee->get('id_person')] = 0;
			}

			$scores[$coffee->get('id_person')] += $score;
		}

		foreach ($scores as $id_person => $score) {
			$sql = sprintf("UPDATE `person` SET `score` = %s WHERE `id` = %s", $score, $id_person);
			$db->query($sql);
			if (!$silent) {
				echo "Actualizo person ".$id_person." con puntuación ".$score."\n";
			}
		}
	}
}