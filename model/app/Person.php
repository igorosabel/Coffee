<?php
class Person extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'person';
    $model = array(
        'id'              => array('type'=>Base::PK,                 'com'=>'Id único de cada persona'),
        'name'            => array('type'=>Base::TEXT,    'len'=>50, 'com'=>'Nombre de la persona'),
        'num_coffee'      => array('type'=>Base::NUM,                'com'=>'Número de veces que ha bajado al café'),
        'num_pay'         => array('type'=>Base::NUM,                'com'=>'Número de veces que ha pagado'),
        'num_special'     => array('type'=>Base::NUM,                'com'=>'Número de viernes que ha bajado'),
        'num_special_pay' => array('type'=>Base::NUM,                'com'=>'Número de viernes que ha pagado'),
        'color'           => array('type'=>Base::TEXT,    'len'=>6,  'com'=>'Color para identificar a la persona'),
        'created_at'      => array('type'=>Base::CREATED,            'com'=>'Fecha de creación del registro'),
        'updated_at'      => array('type'=>Base::UPDATED,            'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
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