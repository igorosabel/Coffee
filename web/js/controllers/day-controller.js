(function(){
  'use strict';

  angular
    .module('CoffeeApp')
    .controller('DayController', DayController);

  DayController.$inject = ['$location','$mdDialog','APIService','DataShareService'];
  function DayController($location, $mdDialog, APIService, DataShareService){
    console.log('DayController');
    const vm     = this;
    vm.day       = DataShareService.GetGlobal('selectedDay');
    vm.people    = [];
    vm.id_coffee = 0;
    vm.id_pay    = 0;
    vm.sending   = false;
    
    const date = new Date(vm.day.year, vm.day.month-1, vm.day.day);
    vm.special = (date.getDay()===5);
    
    vm.save         = save;
    vm.deleteCoffee = deleteCoffee;
    
    APIService.GetDay(vm.day, getDaySuccess, getDayError);
    
    function getDaySuccess(response){
      console.log(response);
      vm.id_coffee = response.id_coffee;
      vm.id_pay    = response.id_pay;
      
      for (let i in response.list){
        const person = {
          id: response.list[i].id,
          name: response.list[i].name,
          didGo: response.list[i].did_go,
          pay: (response.list[i].id===vm.id_pay)
        };
        vm.people.push(person);
      }
      
    }
    
    function getDayError(response){
      console.error('Errorea egun honetako datuak lortzerakoan');
    }
    
    function save(ev){
      let ok = false;
      for (let i in vm.people){
        if (vm.people[i].didGo){
          ok = true;
        }
      }
      
      if (vm.id_pay===0){
        ok = false;
      }
      
      if (!ok){
        $mdDialog.show(
          $mdDialog.alert()
            .parent(angular.element(document.body))
            .clickOutsideToClose(true)
            .title('Errorea')
            .textContent('Ez da inor joan kafera?')
            .ariaLabel('Errorea')
            .ok('Ados')
            .targetEvent(ev)
        );
      }
      else{
        vm.sending = true;
        const saveObj = {
          id: vm.id_coffee,
          d: vm.day.day,
          m: vm.day.month,
          y: vm.day.year,
          special: vm.special,
          id_pay: parseInt(vm.id_pay),
          list: []
        };
        
        for (let i in vm.people){
          if (vm.people[i].didGo){
            saveObj.list.push(vm.people[i].id);
          }
        }
        
        console.log(saveObj);
        APIService.SaveCoffee(saveObj, saveSuccess, saveError);
      }
    }
    
    function saveSuccess(response){
      $location.path('/');
    }
    
    function saveError(response){
      console.error('Errorea kafea gordetzerakoan');
    }
    
    function deleteCoffee(ev){
      const confirm = $mdDialog.confirm()
          .title('Kafea ezabatu')
          .textContent('Ziur zaude kafe hau ezabatu nahi duzula?')
          .ariaLabel('Kafea ezabatu')
          .targetEvent(ev)
          .ok('Ados')
          .cancel('Utzi');

      $mdDialog.show(confirm).then(function() {
        APIService.DeleteCoffee(vm.id_coffee, deleteSuccess, deleteError);
      }, function(){});
    }
    
    function deleteSuccess(response){
      $location.path('/');
    }
    
    function deleteError(){
      console.error('Errorea kafea ezabatzerakoan');
    }
  }
})();