(function(){
  angular
    .module('CoffeeApp')
    .directive('calendar', function() {
      return {
        restrict: 'E',
        templateUrl: 'partials/calendar.html',
        scope: {
          data: '='
        },
        transclude : false,
        controller: 'CalendarController',
        controllerAs: 'vmd'
      };
    });
})();