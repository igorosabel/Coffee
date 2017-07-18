(function(){
  'use strict';

  angular
    .module('CoffeeApp')
    .controller('CalendarController', CalendarController);

  CalendarController.$inject = ['$scope','$mdMedia'];
  function CalendarController($scope, $mdMedia){
    console.log('CalendarController');
    const vmd = this;
    let data = $scope.data;
    vmd.calendarOptions = {
      where: document.getElementById('calendar'),
      load: true
    };

    $scope.$watch('data',function(){
      vmd.calendarOptions.$mdMedia = $mdMedia;
      vmd.calendarOptions.selectDay = $scope.data.selectDay;
      vmd.cal = new Calendar(vmd.calendarOptions);
      $scope.data.cal = vmd.cal;
    });
  }

  class Calendar{
    constructor(options){
      // Auxiliar $mdMedia
      this.$mdMedia = options.$mdMedia;
      
      // Donde dibujar el calendario
      this.where = null;
      if (!options.hasOwnProperty('where')){
        console.error('Es necesario definir donde se mostrará el calendario.');
        options.load = false;
      }
      else{
        this.where = options.where;
      }

      // Cargo nombres de los meses y de los días
      this.configure();

      // Cargo fecha a mostrar
      if (!options.hasOwnProperty('date')){
        options.date = new Date();
      }
      this.day = options.date.getDate();
      this.month = options.date.getMonth();
      this.year = options.date.getFullYear();

      // Cargo callback a ejecutar al pinchar en un día
      this.selectDay = null;
      if (options.hasOwnProperty('selectDay')){
        this.selectDay = options.selectDay;
      }
      
      // Cargo callback a ejecutar al cambiar de mes
      this.changeMonth = null;
      if (options.hasOwnProperty('changeMonth')){
        this.changeMonth = options.changeMonth;
      }

      // Cargo lista de días a marcar
      this.marked = {};
      if (options.hasOwnProperty('marked')){
        this.marked = options.marked;
      }

      // Si en las opciones load es verdadero cargo inmediatamente el calendario
      if (options.hasOwnProperty('load') && options.load===true){
        this.render();
      }
    }
    configure(){
      this.months = ['Urtarrila','Otsaila','Martxoa','Apirila','Maiatza','Ekaina','Uztaila','Abuztua','Iraila','Urria','Azaroa','Abendua'];
      this.monthsShort = ['Urt','Ots','Mar','Api','Mai','Eka','Uzt','Abu','Ira','Urr','Aza','Abe'];
      this.days = ['Astelehena','Asteartea','Asteazkena','Osteguna','Ostirala','Larunbata','Igandea'];
      this.daysShort = ['Asl','Ast','Asz','Ost','Osi','Lar','Iga'];
    }
    getDate(){
      return {d: this.day, m: this.month, y: this.year};
    }
    updateMarked(marked){
      this.marked = marked;
      this.render();
    }
    header(){
      const months = this.$mdMedia('gt-xs') ? this.months : this.monthsShort;
      const days   = this.$mdMedia('gt-xs') ? this.days   : this.daysShort;
      let header = `
        <div class="calendar-header">
          <a href="#" class="calendar-previous">&lt;</a>
          ${months[this.month]} ${this.year}
          <a href="#" class="calendar-next">&gt;</a>
        </div>
        <div class="calendar-row">`;
      for (let i in days){
        header += `<div class="calendar-header-day">${days[i]}</div>`;
      }
      header += `</div>`;
      return  header;
    }
    otherMonthDay(day){
      return `<div class="calendar-day-other">${day}</div>`;
    }
    currentMonthDay(day){
      const now = new Date();
      let today = (this.year===now.getFullYear() && this.month===now.getMonth() && this.day===day) ? ' calendar-today' : '';
      let clickable = (this.selectDay===null) ? '' : ' calendar-clickable';
      let marked = '';
      if (this.marked[this.year] && this.marked[this.year][this.month+1] && this.marked[this.year][this.month+1][day]){
        marked = ' ' + this.marked[this.year][this.month+1][day];
      }
      return `<div class="calendar-day${today}${clickable}${marked}">${day}</div>`;
    }
    draw(){
      // Obtengo el primer día del mes y el primer día de la semana
      const firstDay = new Date(this.year, this.month, 1);
      const firstDayWeekday = firstDay.getDay() === 0 ? 7 : firstDay.getDay();

      // Obtengo número de días en el mes
      const monthLength = new Date(this.year, this.month+1, 0).getDate();
      const previousMonthLength = new Date(this.year, this.month, 0).getDate();

      let html = `<div class="calendar-all">`;

      // Cabecera del calendario
      html += this.header();

      // Contenido del calendario
      html += `<div class="calendar-table">`;

      // Variables con valores por defecto para los días
      let day  = 1; // Día actual del mes
      let prev = 1; // Días del mes anterior
      let next = 1; // Días del mes siguiente

      html += `<div class="calendar-row">`;
      // Bucle de semanas (filas)
      for (let i = 0; i < 9; i++){
        // Bucle días de la semana (celdas)
        for (let j = 1; j <= 7; j++){
          if (day <= monthLength && (i > 0 || j >= firstDayWeekday)){
            // Mes actual
            html += this.currentMonthDay(day);
            day++;
          }
          else{
            if (day <= monthLength) {
              // Mes anterior
              html += this.otherMonthDay( previousMonthLength - firstDayWeekday + prev + 1 );
              prev++;
            }
            else{
              // Mes siguiente
              html += this.otherMonthDay(next);
              next++;
            }
          }
        }

        // Paro de hacer filas si es el final del mes
        if (day > monthLength) {
          html += `</div>`;
          break;
        } else {
          html += `</div><div class="calendar-row">`;
        }
      }
      html += `
        </div>
      </div>`;

      return html;
    }
    render(){
      this.where.innerHTML = this.draw();
      this.where.querySelector('.calendar-previous').addEventListener('click', this.previousMonth.bind(this));
      this.where.querySelector('.calendar-next').addEventListener('click', this.nextMonth.bind(this));
      if (this.selectDay!==null){
        this.where.querySelectorAll('.calendar-day').forEach(day => day.addEventListener('click', this.daySelected.bind(this)));
      }
    }
    previousMonth(ev){
      ev.preventDefault();
      this.month--;
      if (this.month===-1){
        this.month = 11;
        this.year--;
      }
      this.render();
      if (this.changeMonth!=null){
        this.changeMonth({
          month: (this.month+1),
          year: this.year
        });
      }
    }
    nextMonth(ev){
      ev.preventDefault();
      this.month++;
      if (this.month===12){
        this.month = 0;
        this.year++;
      }
      this.render();
    }
    daySelected(ev){
      this.selectDay({
        day: parseInt(ev.target.innerHTML),
        month: (this.month+1),
        year: this.year
      });
    }
  }
})();