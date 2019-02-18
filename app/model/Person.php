<?php
class Person extends OBase{
  function __construct(){
    $table_name  = 'person';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada persona'
        ],
        'name' => [
          'type'     => Base::TEXT,
          'size'     => 50,
          'comment'  => 'Nombre de la persona',
          'nullable' => false
        ],
        'num_coffee' => [
          'type'     => Base::NUM,
          'comment'  => 'Número de veces que ha bajado al café',
          'nullable' => false,
          'default'  => 0
        ],
        'num_pay' => [
          'type'     => Base::NUM,
          'comment'  => 'Número de veces que ha pagado',
          'nullable' => false,
          'default'  => 0
        ],
        'num_special' => [
          'type'     => Base::NUM,
          'comment'  => 'Número de viernes que ha bajado',
          'nullable' => false,
          'default'  => 0
        ],
        'num_special_pay' => [
          'type'     => Base::NUM,
          'comment'  => 'Número de viernes que ha pagado',
          'nullable' => false,
          'default'  => 0
        ],
        'color' => [
          'type'     => Base::TEXT,
          'size'     => 6,
          'comment'  => 'Color para identificar a la persona',
          'nullable' => false
        ],
        'score' => [
          'type'     => Base::FLOAT,
          'comment'  => 'Puntuación de la persona',
          'nullable' => false,
          'default'  => 0
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

  public function __toString(){
    return $this->get('name');
  }

  private $did_go = false;

  public function getDidGo(){
    return $this->did_go;
  }

  public function setDidGo($did_go){
    $this->did_go = $did_go;
  }

  public function updateNumbers(){
    $sql = sprintf("SELECT COUNT(*) AS `num` FROM `went` WHERE `id_person` = %s", $this->get('id'));
    $this->db->query($sql);
    $res = $this->db->next();

    $this->set('num_coffee', $res['num']);

    $sql = sprintf("SELECT COUNT(*) AS `num` FROM `went` WHERE `id_person` = %s AND `pay` = 1", $this->get('id'));
    $this->db->query($sql);
    $res = $this->db->next();

    $this->set('num_pay', $res['num']);

    $sql = sprintf("SELECT COUNT(*) AS `num` FROM `coffee` WHERE `id` IN (SELECT `id_coffee` FROM `went` WHERE `id_person` = %s) AND `special` = 1", $this->get('id'));
    $this->db->query($sql);
    $res = $this->db->next();

    $this->set('num_special', $res['num']);

    $sql = sprintf("SELECT COUNT(*) AS `num` FROM `coffee` WHERE `id` IN (SELECT `id_coffee` FROM `went` WHERE `id_person` = %s AND `pay` = 1) AND `special` = 1", $this->get('id'));
    $this->db->query($sql);
    $res = $this->db->next();

    $this->set('num_special_pay', $res['num']);

    $this->save();
  }
}
