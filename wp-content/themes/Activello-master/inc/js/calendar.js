(function($) {
  $('#calendar').datepicker({
    inline: true,
    firstDay: 1,
    showOtherMonths: false,
    dayNamesMin: ['Нд', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    dateFormat: "dd/mm/yy",
    onSelect: function(date) {
     var getUrl = window.location;
     var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
     window.location.href = baseUrl + "/bydate?&date=" + date;
    }
  });
})(jQuery);
