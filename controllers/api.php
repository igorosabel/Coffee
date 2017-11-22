<?php
  /*
   * Función para obtener la lista de cafés de un mes dado
   */
  function executeGetMonthCoffees($req, $t){
    $status = 'ok';
    $month  = Base::getParam('month', $req['url_params'], false);
    $year   = Base::getParam('year',  $req['url_params'], false);
    $list   = array();

    if ($month===false || $year===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $list = stPublic::getMonthCoffees($month, $year);
    }

    $t->setLayout(false);
    $t->setJson(true);

    $t->add('status', $status);
    $t->add('m', $month);
    $t->add('y', $year);
    $t->addPartial('list', 'api/monthCoffee', array('list'=>$list, 'extra'=>'nourlencode'));
    $t->process();
  }

  /*
   * Función para guardar una persona
   */
  function executeSavePerson($req, $t){
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

    $t->setLayout(false);
    $t->setJson(true);

    $t->add('status', $status);
    $t->add('id',     $id);
    $t->process();
  }

  /*
   * Función para obtener los datos de una persona
   */
  function executeGetPerson($req, $t){
    $status = 'ok';
    $id     = Base::getParam('id', $req['url_params'], false);
    $name   = '';
    $color  = '';
    $list   = array();

    if ($id===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $person = new Person();
      if ($person->find(array('id'=>$id))){
        $name  = $person->get('name');
        $color = $person->get('color');
        $list  = stPublic::getPersonCoffees($person->get('id'));
      }
      else{
        $status = 'error';
      }
    }

    $t->setLayout(false);
    $t->setJson(true);

    $t->add('status', $status);
    $t->add('id',     $id);
    $t->add('name',   $name);
    $t->add('color',  $color);
    $t->addPartial('list', 'api/personCoffees', array('list'=>$list, 'extra'=>'nourlencode'));
    $t->process();
  }
  
  /*
   * Función para obtener los datos de un día concreto
   */
  function executeGetDay($req, $t){
    $status = 'ok';
    $day    = Base::getParam('day',   $req['url_params'], false);
    $month  = Base::getParam('month', $req['url_params'], false);
    $year   = Base::getParam('year',  $req['url_params'], false);
    
    $id_coffee = 0;
    $special   = 0;
    $id_pay    = 0;
    $people    = array();

    if ($day===false || $month===false || $year===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $data = stPublic::getDay($day, $month, $year);
      
      $id_coffee = $data['coffee']->get('id');
      $special   = ($data['coffee']->get('special') ? 1 : 0);
      $id_pay    = $data['coffee']->get('id_person');
      $people    = $data['people'];
    }

    $t->setLayout(false);
    $t->setJson(true);

    $t->add('status',    $status);
    $t->add('d',         $day);
    $t->add('m',         $month);
    $t->add('y',         $year);
    $t->add('id_coffee', $id_coffee);
    $t->add('special',   $special);
    $t->add('id_pay',    $id_pay);
    $t->addPartial('people', 'api/people', array('people'=>$people, 'extra'=>'nourlencode'));
    $t->process();
  }
  
  /*
   * Función para guardar un café
   */
  function executeSaveCoffee($req, $t){
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
    }

    $t->setLayout(false);
    $t->setJson(true);

    $t->add('status', $status);
    $t->process();
  }
  
  /*
   * Función para borrar un café
   */
  function executeDeleteCoffee($req, $t){
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
      }
    }

    $t->setLayout(false);
    $t->setJson(true);

    $t->add('status', $status);
    $t->process();
  }

  /*
   * Función para obtener la lista de personas
   */
  function executeGetPeople($req, $t){
    $people = stPublic::getPeople();

    $t->setLayout(false);
    $t->setJson(true);

    $t->addPartial('people', 'api/people', array('people'=>$people, 'extra'=>'nourlencode'));
    $t->process();
  }

  /*
   * Función para borrar una persona
   */
  function executeDeletePerson($req, $t){
    $status = 'ok';
    $id     = Base::getParam('id', $req['url_params'], false);

    if ($id===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $person = new Person();
      if ($person->find(array('id'=>$id))){
        stPublic::deletePerson($person);
      }
      else{
        $status = 'error';
      }
    }

    $t->setLayout(false);
    $t->setJson(true);

    $t->add('status', $status);
    $t->process();
  }  /*
   * Función para actualizar un café
   */
  function executeUpdateCoffee($req, $t){
    $t->setLayout(false);
    $t->setJson(true);
    $t->process();
  }

  /*
   * Función para obtener la lista de cafés de un mes dado
   */
  function executeGetMonthList($req, $t){
    $status = 'ok';
    $month  = Base::getParam('month', $req['url_params'], false);
    $year   = Base::getParam('year',  $req['url_params'], false);
    $list   = array();

    if ($month===false || $year===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $full_list = stPublic::getMonthCoffees($month, $year);
      foreach ($full_list as $coffee){
        if (!array_key_exists($coffee->get('d'), $list)){
          $list[$coffee->get('d')] = array();
        }
        array_push($list[$coffee->get('d')], $coffee);
      }
    }

    $t->setLayout(false);
    $t->setJson(true);

    $t->add('status', $status);
    $t->add('m', $month);
    $t->add('y', $year);
    $t->addPartial('list', 'api/monthCoffeeList', array('list'=>$list, 'extra'=>'nourlencode'));
    $t->process();
  }
  
  /*
   * Función para obtener los detalles de un café
   */
  function executeGetCoffee($req, $t){
    $status    = 'ok';
    $id_coffee = Base::getParam('id',   $req['url_params'], false);
    $day       = '';
    $month     = '';
    $year      = '';
    $special   = 0;
    $id_pay    = 0;
    $people    = array();

    if ($id_coffee===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $data = stPublic::getCoffee($id_coffee);
      
      $day     = $data['coffee']->get('d');
      $month   = $data['coffee']->get('m');
      $year    = $data['coffee']->get('y');
      $special = ($data['coffee']->get('special') ? 1 : 0);
      $id_pay  = $data['coffee']->get('id_person');
      $people  = $data['people'];
    }

    $t->setLayout(false);
    $t->setJson(true);

    $t->add('status',    $status);
    $t->add('d',         $day);
    $t->add('m',         $month);
    $t->add('y',         $year);
    $t->add('id_coffee', $id_coffee);
    $t->add('special',   $special);
    $t->add('id_pay',    $id_pay);
    $t->addPartial('people', 'api/people', array('people'=>$people, 'extra'=>'nourlencode'));
    $t->process();
  }
