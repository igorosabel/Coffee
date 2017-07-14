(function(){
  'use strict';

  angular
    .module('CoffeeApp')
    .controller('PeopleController', PeopleController);

  PeopleController.$inject = ['$location','APIService','DataShareService'];
  function PeopleController($location, APIService, DataShareService){
    console.log('PeopleController');
    const vm = this;

    vm.colors     = {list: [], load: null};
    vm.peopleList = [];

    vm.selectPerson = selectPerson;

    APIService.GetPeople(peopleSuccess, peopleError);

    function peopleSuccess(response){
      for (let i in response.people){
        vm.peopleList.push({
          id: response.people[i].id,
          name: urldecode(response.people[i].name),
          color: urldecode(response.people[i].color)
        });
      }
      vm.colors.list = vm.peopleList;
      vm.colors.load();
    }

    function peopleError(response){
      console.error('Errore bat gertatu da gendearen zerrenda lortzeko');
    }

    function selectPerson(ind){
      DataShareService.SetGlobal('edit-person', vm.peopleList[ind]);
      $location.path('/edit-person/'+vm.peopleList[ind].id);
    }
  }
})();