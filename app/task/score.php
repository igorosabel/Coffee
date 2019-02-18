<?php
class scoreTask{
  public function __toString(){
    return "score: Función para calcular el score de cada usuario";
  }

  function __construct(){}

  public function run($options=[]){
    $db = new ODB();
    $sql = "SELECT * FROM `coffee`";
    $db->query($sql);
    $scores = [];

    while ($res=$db->next()){
      $coffee = new Coffee();
      $coffee->update($res);

      $multiplier = 1;
      if ($coffee->get('special')){
        $multiplier = 1.2;
      }

      $people = $coffee->getPeople();
      $score = (count($people) -1) * $multiplier;

      if (!array_key_exists($coffee->get('id_person'), $scores)){
        $scores[$coffee->get('id_person')] = 0;
      }

      $scores[$coffee->get('id_person')] += $score;
    }

    foreach ($scores as $id_person => $score){
      $sql = sprintf("UPDATE `person` SET `score` = %s WHERE `id` = %s", $score, $id_person);
      $db->query($sql);
      echo "Actualizo person ".$id_person." con puntuación ".$score."\n";
    }
  }
}
