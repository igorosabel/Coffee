<?php
class api extends OController{
  private $public_service;

  function __construct(){
    $this->$public_service = new publicService($this);
  }

  /*
   * Función para obtener la lista de cafés de un mes dado
   */
  public function getMonthCoffees($req){
    $status = 'ok';
    $month  = Base::getParam('month', $req['url_params'], false);
    $year   = Base::getParam('year',  $req['url_params'], false);
    $list   = [];

    if ($month===false || $year===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $list = $this->$public_service->getMonthCoffees($month, $year);
    }

    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->add('m', $month);
    $this->getTemplate()->add('y', $year);
    $this->getTemplate()->addPartial('list', 'api/monthCoffee', ['list'=>$list, 'extra'=>'nourlencode']);
  }

  /*
   * Función para guardar una persona
   */
  public function savePerson($req){
    $status = 'ok';
    $id     = Base::getParam('id',    $req['url_params'], false);
    $name   = Base::getParam('name',  $req['url_params'], false);
    $color  = Base::getParam('color', $req['url_params'], false);

    if ($id===false || $name===false || $color===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $person = new Person();
      if ($id!=0){
        $person->find(array('id'=>$id));
      }
      $person->set('name',  $name);
      $person->set('color', $color);
      $person->save();

      $id = $person->get('id');
    }

    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->add('id',     $id);
  }

  /*
   * Función para obtener los datos de una persona
   */
  public function getPerson($req){
    $status = 'ok';
    $id     = Base::getParam('id', $req['url_params'], false);
    $name   = '';
    $color  = '';
    $list   = [];

    if ($id===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $person = new Person();
      if ($person->find(array('id'=>$id))){
        $name  = $person->get('name');
        $color = $person->get('color');
        $list  = $this->$public_service->getPersonCoffees($person->get('id'));
      }
      else{
        $status = 'error';
      }
    }

    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->add('id',     $id);
    $this->getTemplate()->add('name',   $name);
    $this->getTemplate()->add('color',  $color);
    $this->getTemplate()->addPartial('list', 'api/personCoffees', ['list'=>$list, 'extra'=>'nourlencode']);
  }

  /*
   * Función para obtener los datos de un día concreto
   */
  public function getDay($req){
    $status = 'ok';
    $day    = Base::getParam('day',   $req['url_params'], false);
    $month  = Base::getParam('month', $req['url_params'], false);
    $year   = Base::getParam('year',  $req['url_params'], false);

    $id_coffee = 0;
    $special   = 0;
    $id_pay    = 0;
    $people    = [];

    if ($day===false || $month===false || $year===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $data = $this->$public_service->getDay($day, $month, $year);

      $id_coffee = $data['coffee']->get('id');
      $special   = ($data['coffee']->get('special') ? 1 : 0);
      $id_pay    = is_null($data['coffee']->get('id_person')) ? 0 : $data['coffee']->get('id_person');
      $people    = $data['people'];
    }

    $this->getTemplate()->add('status',    $status);
    $this->getTemplate()->add('d',         $day);
    $this->getTemplate()->add('m',         $month);
    $this->getTemplate()->add('y',         $year);
    $this->getTemplate()->add('id_coffee', $id_coffee);
    $this->getTemplate()->add('special',   $special);
    $this->getTemplate()->add('id_pay',    $id_pay);
    $this->getTemplate()->addPartial('people', 'api/people', ['people'=>$people, 'extra'=>'nourlencode']);
  }

  /*
   * Función para guardar un café
   */
  public function saveCoffee($req){
    $status  = 'ok';
    $id      = Base::getParam('id',      $req['url_params'], false);
    $id_pay  = Base::getParam('id_pay',  $req['url_params'], false);
    $d       = Base::getParam('d',       $req['url_params'], false);
    $m       = Base::getParam('m',       $req['url_params'], false);
    $y       = Base::getParam('y',       $req['url_params'], false);
    $special = Base::getParam('special', $req['url_params'], false);
    $list    = Base::getParam('list',    $req['url_params'], false);

    if ($id===false || $id_pay===false || $d===false || $m===false || $y===false || $list===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $cof = new Coffee();
      if ($id!=0){
        $cof->find(array('id'=>$id));
      }
      $cof->set('d', $d);
      $cof->set('m', $m);
      $cof->set('y', $y);
      $cof->set('special', $special);
      $cof->set('id_person', ($id_pay===0) ? null : $id_pay);

      $cof->save();
      $cof->updateWent($id_pay, $list);

      Base::runTask('score', ['silent'=>true]);
    }

    $this->getTemplate()->add('status', $status);
  }

  /*
   * Función para borrar un café
   */
  public function deleteCoffee($req){
    $status = 'ok';
    $id     = Base::getParam('id', $req['url_params'], false);

    if ($id===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $cof = new Coffee();
      if ($cof->find(array('id'=>$id))){
        $people = $cof->getPeople();
        $cof->deleteFull();

        foreach ($people as $went){
          $person = $went->getPerson();
          $person->updateNumbers();
        }

        Base::runTask('score', ['silent'=>true]);
      }
    }

    $this->getTemplate()->add('status', $status);
  }

  /*
   * Función para obtener la lista de personas
   */
  public function getPeople($req){
    $this->getTemplate()->addPartial('people', 'api/people', ['people'=>$this->$public_service->getPeople(), 'extra'=>'nourlencode']);
  }

  /*
   * Función para borrar una persona
   */
  public function deletePerson($req){
    $status = 'ok';
    $id     = Base::getParam('id', $req['url_params'], false);

    if ($id===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $person = new Person();
      if ($person->find(array('id'=>$id))){
        $this->$public_service->deletePerson($person);
      }
      else{
        $status = 'error';
      }
    }

    $this->getTemplate()->add('status', $status);
  }  /*
   * Función para actualizar un café
   */
  public function updateCoffee($req){}

  /*
   * Función para obtener la lista de cafés de un mes dado
   */
  public function getMonthList($req){
    $status = 'ok';
    $month  = Base::getParam('month', $req['url_params'], false);
    $year   = Base::getParam('year',  $req['url_params'], false);
    $list   = [];

    if ($month===false || $year===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $full_list = $this->$public_service->getMonthCoffees($month, $year);
      foreach ($full_list as $coffee){
        if (!array_key_exists($coffee->get('d'), $list)){
          $list[$coffee->get('d')] = array();
        }
        array_push($list[$coffee->get('d')], $coffee);
      }
    }

    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->add('m', $month);
    $this->getTemplate()->add('y', $year);
    $this->getTemplate()->addPartial('list', 'api/monthCoffeeList', ['list'=>$list, 'extra'=>'nourlencode']);
  }

  /*
   * Función para obtener los detalles de un café
   */
  public function getCoffee($req){
    $status    = 'ok';
    $id_coffee = Base::getParam('id', $req['url_params'], false);
    $day       = '';
    $month     = '';
    $year      = '';
    $special   = 0;
    $id_pay    = 0;
    $people    = [];

    if ($id_coffee===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $data = $this->$public_service->getCoffee($id_coffee);

      $day     = $data['coffee']->get('d');
      $month   = $data['coffee']->get('m');
      $year    = $data['coffee']->get('y');
      $special = ($data['coffee']->get('special') ? 1 : 0);
      $id_pay  = $data['coffee']->get('id_person');
      $people  = $data['people'];
    }

    $this->getTemplate()->add('status',    $status);
    $this->getTemplate()->add('d',         $day);
    $this->getTemplate()->add('m',         $month);
    $this->getTemplate()->add('y',         $year);
    $this->getTemplate()->add('id_coffee', $id_coffee);
    $this->getTemplate()->add('special',   $special);
    $this->getTemplate()->add('id_pay',    $id_pay);
    $this->getTemplate()->addPartial('people', 'api/people', ['people'=>$people, 'extra'=>'nourlencode']);
  }
}
