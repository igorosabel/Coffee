<?php
  /*
   * Clase con funciones generales para usar a lo largo del sitio
   */
class publicService extends OService{
  function __construct($controller=null){
    $this->setController($controller);
  }

  public function getMonthCoffees($month, $year){
    $db = $this->getController()->getDB();
    $sql = sprintf("SELECT * FROM `coffee` WHERE `m` = %s AND `y` = %s ORDER BY `created_at` ASC", $month, $year);
    $db->query($sql);

    $list = [];
    while ($res=$db->next()){
      $cof = new Coffee();
      $cof->update($res);
      $cof->loadPeople();
      array_push($list, $cof);
    }

    return $list;
  }

  public function getPeople(){
    $db = $this->getController()->getDB();
    $sql = "SELECT * FROM `person` ORDER BY `name`";
    $db->query($sql);

    $list = [];
    while ($res=$db->next()){
      $person = new Person();
      $person->update($res);
      $list['person_' . $person->get('id')] = $person;
    }

    return $list;
  }

  public function getPersonCoffees($id_person){
    $db = $this->getController()->getDB();
    $sql = sprintf(
      "SELECT c.*, w.`pay` FROM `coffee` c, `went` w WHERE c.`id` = w.`id_coffee` AND w.`id_person` = %s ORDER BY `created_at` DESC",
      $id_person
    );
    $db->query($sql);

    $list = [];
    while ($res=$db->next()){
      $cof = new Coffee();
      $cof->update($res);
      $cof->setIsPaid((int)$res['pay']);

      array_push($list, $cof);
    }

    return $list;
  }

  public function getDay($day, $month, $year){
    $db = $this->getController()->getDB();
    $sql = sprintf("SELECT * FROM `coffee` WHERE `d` = %s AND `m` = %s AND `y` = %s", $day, $month, $year);
    $db->query($sql);
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

  public function getCoffee($id){
    $db = $this->getController()->getDB();
    $sql = sprintf("SELECT * FROM `coffee` WHERE `id` = %s", $id);
    $db->query($sql);
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

  public function deletePerson($person){
    $db = $this->getController()->getDB();
    $sql = sprintf("UPDATE `coffee` SET `id_person` = NULL WHERE `id_person` = %s", $person->get('id'));
    $db->query($sql);

    $sql = sprintf("DELETE FROM `went` WHERE `id_person` = %s", $person->get('id'));
    $db->query($sql);

    $person->delete();
  }
}
