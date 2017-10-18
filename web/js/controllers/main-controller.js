(function(){
  'use strict';

  angular
    .module('CoffeeApp')
    .controller('MainController', MainController);

  MainController.$inject = ['$scope', '$location', '$mdSidenav', '$mdMedia', 'APIService', 'DataShareService'];
  function MainController($scope, $location, $mdSidenav, $mdMedia, APIService, DataShareService){
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
      selectDay: selectDay,
      changeMonth: selectMonth
    };
    vm.sortField = 'percentage';
    vm.sortOrder = 'down';
    vm.label = {
      ehunekoa: $mdMedia('gt-xs') ? 'Ehunekoa' : '%',
      ostirala: $mdMedia('gt-xs') ? 'Ostiralak': 'Ost'
    };
    
    vm.openMenu    = openMenu;
    vm.addToday    = addToday;
    vm.changeOrder = changeOrder;

    APIService.GetMonthCoffees({month: vm.m, year: vm.y}, monthCoffeesSuccess, monthCoffeesError);
    APIService.GetPeople(peopleSuccess, peopleError);

    function monthCoffeesSuccess(response){
      const marked = {};
      marked[vm.y] = {};
      marked[vm.y][vm.m] = {};
      for (let i in response.list){
        marked[vm.y][vm.m][response.list[i].d] = 'person_'+response.list[i].id_person;
      }
      vm.calendarData.cal.updateMarked(marked);
      vm.coffees = response.list;
    }

    function monthCoffeesError(response){
      console.error(response);
    }
    
    function peopleSuccess(response){
      vm.people  = response.people;
      for (let i in vm.people){
        vm.peopleList.push(vm.people[i]);
      }
      listOrder();
      vm.colors.list = vm.peopleList;
      vm.colors.load();
    }
    
    function peopleError(response){
      console.log(response);
    }
    
    function listOrder(){
      if (vm.sortField=='percentage'){
        if (vm.sortOrder=='down'){
          vm.peopleList.sort(function(a, b) {
            return (b.num_pay / b.num_coffee) - (a.num_pay / a.num_coffee);
          });
        }
        if (vm.sortOrder=='up'){
          vm.peopleList.sort(function(a, b) {
            return (a.num_pay / a.num_coffee) - (b.num_pay / b.num_coffee);
          });
        }
      }
      if (vm.sortField=='special'){
        if (vm.sortOrder=='down'){
          vm.peopleList.sort(function(a, b) {
            return (b.num_special_pay / b.num_special) - (a.num_special_pay / a.num_special);
          });
        }
        if (vm.sortOrder=='up'){
          vm.peopleList.sort(function(a, b) {
            return (a.num_special_pay / a.num_special) - (b.num_special_pay / b.num_special);
          });
        }
      }
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
    
    function selectMonth(date){
      console.log(date);
    }
    
    function changeOrder(field,ev){
      ev.preventDefault();
      vm.sortField = field;
      vm.sortOrder = (vm.sortOrder=='up') ? 'down' : 'up';
      listOrder();
    }
  }
})();