<?php
  /*
   * Clase con funciones generales para usar a lo largo del sitio
   */
class stPublic{
  public static function getMonthCoffees($month, $year){
    $db = new ODB();
    $sql = sprintf("SELECT * FROM `coffee` WHERE `m` = %s AND `y` = %s ORDER BY `created_at` ASC", $month, $year);
    $db->query($sql);

    $list = array();
    while ($res=$db->next()){
      $cof = new Coffee();
      $cof->update($res);
      $cof->loadPeople();
      array_push($list, $cof);
    }

    return $list;
  }

  public static function getPeople(){
    $db = new ODB();
    $sql = sprintf("SELECT * FROM `person` ORDER BY `name`");
    $db->query($sql);

    $list = array();
    while ($res=$db->next()){
      $person = new Person();
      $person->update($res);
      $list['person_' . $person->get('id')] = $person;
    }

    return $list;
  }
  
  public static function getPersonCoffees($id_person){
    $db = new ODB();
    $sql = sprintf("SELECT c.*, w.`pay` FROM `coffee` c, `went` w WHERE c.`id` = w.`id_coffee` AND w.`id_person` = %s ORDER BY `created_at` DESC", $id_person);
    $db->query($sql);
    
    $list = array();
    while ($res=$db->next()){
      $cof = new Coffee();
      $cof->update($res);
      $cof->setIsPaid((int)$res['pay']);
      
      array_push($list, $cof);
    }
    
    return $list;
  }
  
  public static function getDay($day, $month, $year){
    $db = new ODB();
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
    $people = self::getPeople();
    
    foreach ($people as $person){
      if (in_array($person->get('id'), $went_ids)){
        $person->setDidGo(true);
      }
    }
    
    $list = array('coffee'=>$cof, 'people'=>$people);

    return $list;
  }

  public static function deletePerson($person){
    $db = new ODB();
    $sql = sprintf("UPDATE `coffee` SET `id_person` = NULL WHERE `id_person` = %s", $person->get('id'));
    $db->query($sql);

    $sql = sprintf("DELETE FROM `went` WHERE `id_person` = %s", $person->get('id'));
    $db->query($sql);

    $person->delete();
  }
}