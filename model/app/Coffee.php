<?php
class Coffee extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'coffee';
    $model = array(
        'id'         => array('type'=>Base::PK,      'com'=>'Id único de cada día que se ha bajado al café'),
        'd'          => array('type'=>Base::NUM,     'com'=>'Día que se ha bajado al café'),
        'm'          => array('type'=>Base::NUM,     'com'=>'Mes que se ha bajado al café'),
        'y'          => array('type'=>Base::NUM,     'com'=>'Año que se ha bajado al café'),
        'id_person'  => array('type'=>Base::NUM,     'com'=>'Id de la persona que ha pagado'),
        'special'    => array('type'=>Base::BOOL,    'com'=>'Indica si es un día especial (viernes de pintxo)'),
        'created_at' => array('type'=>Base::CREATED, 'com'=>'Fecha de creación del registro'),
        'updated_at' => array('type'=>Base::UPDATED, 'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
  
  private $isPaid = false;
  
  public function setIsPaid($isPaid){
    $this->isPaid = ($isPaid==1);
  }
  
  public function getIsPaid(){
    return $this->isPaid;
  }

  private $people = null;

  public function getPeople(){
    if (is_null($this->people)){
      $this->loadPeople();
    }
    return $this->people;
  }

  public function getPeopleIds(){
    $list = array();
    foreach ($this->getPeople() as $went){
      array_push($list, $went->get('id_person'));
    }
    return $list;
  }

  public function setPeople($people){
    $this->people = $people;
  }

  public function loadPeople(){
    $sql = sprintf("SELECT * FROM `went` WHERE `id_coffee` = %s", $this->get('id'));
    $this->db->query($sql);

    $people = array();
    while ($res=$this->db->next()){
      $went = new Went();
      $went->update($res);
      $went->loadPerson();
      array_push($people, $went);
    }

    $this->setPeople($people);
  }

  private $paid = null;

  public function getPaid(){
    if (is_null($this->paid)) {
      $this->loadPaid();
    }
    return $this->paid;
  }

  public function setPaid($paid){
    $this->paid = $paid;
  }

  public function loadPaid(){
    $person = new Person();
    if (!is_null($this->get('id_person'))){
      $person->find(array('id'=>$this->get('id_person')));
    }
    $this->setPaid($person);
  }
  
  public function updateWent($id_pay, $list){
    $sql = sprintf("DELETE FROM `went` WHERE `id_coffee` = %s", $this->get('id'));
    $this->db->query($sql);
    
    foreach ($list as $id){
      $went = new Went();
      $went->set('id_person', $id);
      $went->set('id_coffee', $this->get('id'));
      $went->set('pay', ($id_pay==$id));
      $went->save();
      
      $person = new Person();
      $person->find(array('id'=>$id));
      $person->updateNumbers();
    }
  }
  
  public function deleteFull(){
    $sql = sprintf("DELETE FROM `went` WHERE `id_coffee` = %s", $this->get('id'));
    $this->db->query($sql);
    
    $this->delete();
  }
}