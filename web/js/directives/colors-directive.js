(function(){
  angular
    .module('CoffeeApp')
    .directive('colors', function() {
      return {
        restrict: 'E',
        templateUrl: 'partials/colors.html',
        scope: {
          data: '='
        },
        transclude : false,
        controller: 'ColorsController',
        controllerAs: 'vmd'
      };
    });
})();