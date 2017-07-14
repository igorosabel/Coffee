(function(){
  'use strict';

  angular
    .module('CoffeeApp')
    .controller('MainController', MainController);

  MainController.$inject = ['$scope', '$location', '$mdSidenav', 'APIService', 'DataShareService'];
  function MainController($scope, $location, $mdSidenav, APIService, DataShareService){
    console.log('MainController');
    const vm = this;

    const today   = new Date();
    vm.m          = today.getMonth() +1;
    vm.y          = today.getFullYear();
    vm.colors     = {list: [], load: null};
    vm.people     = {};
    vm.peopleList = [];
    vm.coffees    = [];
    vm.calendarData = {
      selectDay: selectDay
    };
    
    vm.openMenu = openMenu;
    vm.addToday = addToday;

    APIService.GetMonthCoffees({month: vm.m, year: vm.y}, monthCoffeesSuccess, monthCoffeesError);

    function monthCoffeesSuccess(response){
      vm.people  = response.people;
      for (let i in vm.people){
        vm.peopleList.push(vm.people[i]);
      }
      vm.colors.list = vm.peopleList;
      
      const marked = {};
      marked[vm.y] = {};
      marked[vm.y][vm.m] = {};
      for (let i in response.list){
        marked[vm.y][vm.m][response.list[i].d] = 'person_'+response.list[i].id_person;
      }
      vm.calendarData.cal.updateMarked(marked);
      vm.coffees = response.list;
      vm.colors.load();
    }

    function monthCoffeesError(response){
      console.error(response);
    }
    
    function openMenu(){
      $mdSidenav('leftmenu').toggle();
    }
    
    function addToday(){
      const today = new Date();
      selectDay({
        day: today.getDate(),
        month: today.getMonth()+1,
        year: today.getFullYear()
      },false);
    }
    
    function selectDay(day,apply=true){
      console.log('day');
      console.log(day);
      DataShareService.SetGlobal('selectedDay',day);
      if (apply){
        $scope.$apply(function(){
          $location.path('/day');
        });
      }
      else{
        $location.path('/day');
      }
    }
  }
})();