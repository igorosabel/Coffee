function startApp(){
  angular
    .module('CoffeeApp', ['ngMaterial','ngRoute','ngSanitize','ngAnimate','ngMessages','mdColorPicker']);
}

function configApp(){
  angular
    .module('CoffeeApp')
    .config(function ($routeProvider) {
      $routeProvider
        .when('/', {
          templateUrl: 'partials/main.html',
          controller: 'MainController',
          controllerAs: 'vm'
        })
        .when('/add-person', {
          templateUrl: 'partials/save-person.html',
          controller: 'AddPersonController',
          controllerAs: 'vm'
        })
        .when('/person/:id', {
          templateUrl: 'partials/person.html',
          controller: 'PersonController',
          controllerAs: 'vm'
        })
        .when('/day', {
          templateUrl: 'partials/day.html',
          controller: 'DayController',
          controllerAs: 'vm'
        })
        .when('/people', {
          templateUrl: 'partials/people.html',
          controller: 'PeopleController',
          controllerAs: 'vm'
        })
        .when('/edit-person/:id', {
          templateUrl: 'partials/save-person.html',
          controller: 'EditPersonController',
          controllerAs: 'vm'
        })
        .otherwise({redirectTo: '/'});
    });
}

startApp();
configApp();