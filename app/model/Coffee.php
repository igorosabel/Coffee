<?php
class Coffee extends OBase{
  function __construct(){
    $table_name  = 'coffee';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada día que se ha bajado al café'
        ],
        'd' => [
          'type'     => Base::NUM,
          'comment'  => 'Día que se ha bajado al café',
          'nullable' => false
        ],
        'm' => [
          'type'     => Base::NUM,
          'comment'  => 'Mes que se ha bajado al café',
          'nullable' => false
        ],
        'y' => [
          'type'     => Base::NUM,
          'comment'  => 'Año que se ha bajado al café',
          'nullable' => false
        ],
        'id_person' => [
          'type'     => Base::NUM,
          'comment'  => 'Id de la persona que ha pagado',
          'nullable' => false
        ],
        'special' => [
          'type'     => Base::BOOL,
          'comment'  => 'Indica si es un día especial (viernes de pintxo)',
          'nullable' => false
        ],
        'created_at' => [
          'type'    => Base::CREATED,
          'comment' => 'Fecha de creación del registro'
        ],
        'updated_at' => [
          'type'    => Base::UPDATED,
          'comment' => 'Fecha de última modificación del registro'
        ]
    ];

    parent::load($table_name, $model);
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
    $list = [];
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

    $people = [];
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
      $person->find(['id'=>$this->get('id_person')]);
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
      $person->find(['id'=>$id]);
      $person->updateNumbers();
    }
  }

  public function deleteFull(){
    $sql = sprintf("DELETE FROM `went` WHERE `id_coffee` = %s", $this->get('id'));
    $this->db->query($sql);

    $this->delete();
  }
}
