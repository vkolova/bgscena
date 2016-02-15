(function($) {
  $('#calendar').datepicker({
    inline: true,
    firstDay: 1,
    showOtherMonths: true,
    dayNamesMin: ['Нд', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
     dateFormat: "dd/mm/yy",
    onSelect: function(string){
      console.log('yoloswag');
       window.location = "http://localhost/bgscena/bydate?&date=" + string
    }
  });



})(jQuery);
