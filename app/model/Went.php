<?php
class Went extends OModel{
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
    $person = new Person();
    $person->find(['id' => $this->get('id_person')]);

    $this->setPerson($person);
  }
}
