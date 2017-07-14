<?php
class Went extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'went';
    $model = array(
        'id_person'  => array('type'=>Base::PK,      'com'=>'Id de la persona que ha bajado al café', 'incr'=>false),
        'id_coffee'  => array('type'=>Base::PK,      'com'=>'Id de la vez que se ha bajado al café', 'incr'=>false),
        'pay'        => array('type'=>Base::BOOL,    'com'=>'Indica si la persona ha pagado 1 o no 0'),
        'created_at' => array('type'=>Base::CREATED, 'com'=>'Fecha de creación del registro'),
        'updated_at' => array('type'=>Base::UPDATED, 'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model,array('id_person','id_coffee'));
  }

  private $person = null;

  public function getPerson(){
    if (is_null($this->person)){
      $this->loadPerson();
    }
    return $this->person;
  }

  public function setPerson($person){
    $this->person = $person;
  }

  public function loadPerson(){
    $sql = sprintf("SELECT * FROM `person` WHERE `id` = %s", $this->get('id_person'));
    $this->db->query($sql);
    $res = $this->db->next();

    $person = new Person();
    $person->update($res);

    $this->setPerson($person);
  }
}