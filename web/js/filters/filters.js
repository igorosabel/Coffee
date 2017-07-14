(function(){
  'use strict';

  angular
    .module('CoffeeApp')
    .filter('urldecode',         urldecodeFilter)
    .filter('urlencode',         urlencodeFilter)
    .filter('percentageTotal',   percentageTotalFilter)
    .filter('percentageFridays', percentageFridaysFilter)
    .filter('formatNum',         formatNumFilter)
    .filter('slugify',           slugifyFilter);

  function urldecodeFilter(){
    return function (str){
      return urldecode(str);
    };
  }

  function urlencodeFilter(){
    return function (str){
      return urlencode(str);
    };
  }

  function percentageTotalFilter(){
    return function (person){
      let num = 0;
      if (person.num_coffee!==0){
        num = Math.floor((person.num_pay / person.num_coffee) * 100);
      }
      return num+'% ('+person.num_pay+'/'+person.num_coffee+')';
    };
  }
  
  function percentageFridaysFilter(){
    return function (person){
      let num = 0;
      if (person.num_special!==0){
        num = Math.floor((person.num_special_pay / person.num_special) * 100);
      }
      return num+'% ('+person.num_special_pay+'/'+person.num_special+')';
    };
  }
  
  function formatNumFilter(){
    return function (num){
      num = parseInt(num);
      return (num<10) ? '0'+num : num;
    };
  }

  function slugifyFilter(){
    return function (str){
      return slugify(str);
    };
  }

})();