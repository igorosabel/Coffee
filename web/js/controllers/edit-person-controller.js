(function(){
  'use strict';

  angular
    .module('CoffeeApp')
    .controller('EditPersonController', EditPersonController);

  EditPersonController.$inject = ['$location','$routeParams','$mdDialog','APIService','DataShareService'];
  function EditPersonController($location, $routeParams, $mdDialog, APIService, DataShareService){
    console.log('EditPersonController');
    const vm     = this;
    vm.id        = parseInt($routeParams.id);
    vm.izenburua = 'Editatu kafezalea';
    vm.person    = DataShareService.GetGlobal('edit-person');

    vm.save         = save;
    vm.deletePerson = deletePerson;

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

    function deletePerson(ev){
      const confirm = $mdDialog.confirm()
        .title('Pertsona ezabatu')
        .textContent('Ziur zaude pertsona hau ezabatu nahi duzula?')
        .ariaLabel('Pertsona ezabatu')
        .targetEvent(ev)
        .ok('Ados')
        .cancel('Utzi');

      $mdDialog.show(confirm).then(function() {
        APIService.DeletePerson(vm.person.id, deleteSuccess, deleteError);
      }, function(){});
    }

    function deleteSuccess(response){
      $location.path('/');
    }

    function deleteError(){
      console.error('Errorea pertsona ezabatzerakoan');
    }
  }
})();