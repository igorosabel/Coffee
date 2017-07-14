(function(){
  'use strict';

  angular
    .module('CoffeeApp')
    .factory('APIService', APIService);

  APIService.$inject = ['$http'];
  function APIService($http){
    const service = {};

    service.GetMonthCoffees = GetMonthCoffees;
    service.SavePerson      = SavePerson;
    service.GetPerson       = GetPerson;
    service.GetDay          = GetDay;
    service.SaveCoffee      = SaveCoffee;
    service.DeleteCoffee    = DeleteCoffee;
    service.GetPeople       = GetPeople;
    service.DeletePerson    = DeletePerson;

    return service;

    function GetMonthCoffees(date,callback,callback_error){
      $http.post('./api/get-month-coffees', date)
        .then(function (response){
          callback && callback(response.data);
        },
        function (response){
          callback_error && callback_error(response);
        });
    }

    function SavePerson(person,callback,callback_error){
      $http.post('./api/save-person', person)
        .then(function (response){
          callback && callback(response.data);
        },
        function (response){
          callback_error && callback_error(response);
        });
    }
    
    function GetPerson(id,callback,callback_error){
      $http.post('./api/get-person', {id})
        .then(function (response){
          callback && callback(response.data);
        },
        function (response){
          callback_error && callback_error(response);
        });
    }
    
    function GetDay(day,callback,callback_error){
      $http.post('./api/get-day', day)
        .then(function (response){
          callback && callback(response.data);
        },
        function (response){
          callback_error && callback_error(response);
        });
    }
    
    function SaveCoffee(saveObj,callback,callback_error){
      $http.post('./api/save-coffee', saveObj)
        .then(function (response){
          callback && callback(response.data);
        },
        function (response){
          callback_error && callback_error(response);
        });
    }
    
    function DeleteCoffee(id,callback,callback_error){
      $http.post('./api/delete-coffee', {id})
        .then(function (response){
          callback && callback(response.data);
        },
        function (response){
          callback_error && callback_error(response);
        });
    }

    function GetPeople(callback,callback_error){
      $http.post('./api/get-people', {})
        .then(function (response){
            callback && callback(response.data);
          },
          function (response){
            callback_error && callback_error(response);
          });
    }

    function DeletePerson(id,callback,callback_error){
      $http.post('./api/delete-person', {id})
        .then(function (response){
            callback && callback(response.data);
          },
          function (response){
            callback_error && callback_error(response);
          });
    }
  }
})();