(function(){
  'use strict';

  angular
    .module('CoffeeApp')
    .controller('PersonController', PersonController);

  PersonController.$inject = ['$routeParams','APIService'];
  function PersonController($routeParams, APIService){
    console.log('PersonController');
    const vm       = this;
    vm.id          = parseInt($routeParams.id);
    vm.name        = '';
    vm.color       = '#000';
    vm.headerColor = '#fff';
    vm.coffees     = [];
    
    APIService.GetPerson(vm.id, getPersonSuccess, getPersonError);
    
    function getPersonSuccess(response){
      vm.name        = urldecode(response.name);
      vm.color       = urldecode(response.color);
      vm.headerColor = invertColor(vm.color,true);
      let list = [];
      for (let i in response.list){
        list.push({
          id: response.list[i].id,
          d: response.list[i].d,
          m: response.list[i].m,
          y: response.list[i].y,
          special: response.list[i].special,
          pay: (response.list[i].id_person===vm.id)
        });
      }
      vm.coffees = list;
    }
    
    function getPersonError(response){
      console.error(response);
    }
  }
})();