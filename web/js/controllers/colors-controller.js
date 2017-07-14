(function(){
  'use strict';

  angular
    .module('CoffeeApp')
    .controller('ColorsController', ColorsController);

  ColorsController.$inject = ['$scope','$sce'];
  function ColorsController($scope, $sce){
    console.log('ColorsController');

    const vmd = this;
    vmd.css   = '';
    let data  = $scope.data;

    $scope.$watch('data',function(){
      $scope.data.load = loadColors;
    });

    function loadColors(){
      for (let i in $scope.data.list){
        vmd.css += `
          .person_${$scope.data.list[i].id}{
            color: ${invertColor($scope.data.list[i].color,true)} !important;
            background-color: ${$scope.data.list[i].color} !important;
            width: 100%;
          }
        `;
      }
      vmd.css = $sce.trustAsHtml(vmd.css);
    }
  }
})();