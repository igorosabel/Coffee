(function(){
  'use strict';

  angular
    .module('CoffeeApp')
    .controller('AddPersonController', AddPersonController);

  AddPersonController.$inject = ['$location','APIService'];
  function AddPersonController($location, APIService){
    console.log('AddPersonController');
    const vm = this;

    vm.izenburua = 'Kafezale berria';
    vm.person = {
      id: 0,
      name: '',
      color: '#fff'
    };
    
    vm.save = save;
    
    function save(ev){
      ev.preventDefault();
      APIService.SavePerson(vm.person, addPersonSuccess, addPersonError);
    }

    function addPersonSuccess(response){
      $location.path('/');
    }

    function addPersonError(response){
      console.error(response);
    }
  }
})();