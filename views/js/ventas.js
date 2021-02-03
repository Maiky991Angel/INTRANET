
$(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('  YYYY-D-MMMM ') + ' - ' + end.format('YYYY-D-MMMM'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Hoy': [moment(), moment()],
           'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()],
           'Ultimos 30 dias': [moment().subtract(29, 'days'), moment()],
           'Este mes': [moment().startOf('month'), moment().endOf('month')],
           'Ultimo mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]

           
        }
    }, cb);

    cb(start, end);

});
this.locale = {
    direction: 'ltr',
    format: moment.localeData().longDateFormat('L'),
    separator: ' - ',
    applyLabel: 'Aceptar',
    cancelLabel: 'Cancelar',
    weekLabel: 'W',
    customRangeLabel: 'Personalizado',
    daysOfWeek: moment.weekdaysMin(),
    monthNames: moment.monthsShort(),
    firstDay: moment.localeData().firstDayOfWeek()
};