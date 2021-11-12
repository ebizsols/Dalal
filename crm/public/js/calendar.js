/**
 * Calendar JS - Calendar js for Client Manager theme
 * @version v3.1.0
 * @copyright 2020 Pepdev.
 */

 $(document).ready(function () {
 	"use strict";
 	var path = $('input.site_url').val();

 	function createEventPopover(event) {
        var allday = '',
        start = moment(event.start_date +' '+ event.start_time),
        end = moment(event.end_date +' '+ event.end_time);
        if (event.allDay == '0') {
            allday = '<div><span class="font-12 text-primary">End Date:</span> '+end.format($('.common_daterange_format').val())+' at '+end.format('hh:mm A')+'</div>';
        } else {
            allday = '<div>All Day Event</div>';
        }
        return '<div><span class="font-12 text-primary">Start Date:</span> '+start.format($('.common_daterange_format').val())+' at '+start.format('hh:mm A')+'</div>'+
        allday + '<div><span class="font-12 text-primary">Description:</span> '+event.description+'</div>';
    }

    $('.event-start').daterangepicker({
        singleDatePicker: true,
        timePicker : true,
        autoApply: true,
        autoUpdateInput: true,
        locale: {
            format: $('.common_daterange_format').val()+' hh:mm A',
        }
    });

    $('.event-end').daterangepicker({
        singleDatePicker: true,
        timePicker : true,
        autoApply: true,
        autoUpdateInput: true,
        locale: {
            format: $('.common_daterange_format').val()+' hh:mm A',
        }
    });

    $('#calendar').fullCalendar({
        events: path.concat('calendar/event'),
        header: {left: 'prev,next today',center: 'title',right: 'listDay,listWeek,month'},
        monthNames: JSON.parse($('.lang_month_names').val()),
        buttonText: JSON.parse($('.lang_buttons').val()),
        
        monthNamesShort: JSON.parse($('.lang_month_names_short').val()),
        dayNames: JSON.parse($('.lang_day_names').val()),
        dayNamesShort: JSON.parse($('.lang_day_names_short').val()),
        lazyFetching: true,
        loading: function (isLoading, view) {
            var ele = $('#calendar').parent('.panel-body');
            if (isLoading) {
                $(ele).block({
                    message: '<div class="font-16"><div class="ti-reload spinner mr-2 d-inline-block"></div>Loading ...</div>',
                    overlayCSS: {backgroundColor: '#fff',opacity: 0.8,cursor: 'wait'},
                    css: {border: 0,padding: '10px 15px',color: '#fff',width: 'auto',backgroundColor: '#333'},
                });
            } else {
                ele.unblock()
            }
        },
        defaultDate: moment().format("YYYY-MM-DD"),
        eventLimit: true,
        editable: false,
        eventMouseover: function (event, jsEvent) {
            $(this).popover({
                container: '#calendar',
                html: true,
                title: event.title,
                content: createEventPopover(event),
            }).popover('toggle');
        },
        eventMouseout: function (event, jsEvent, view) {
            $(this).popover('dispose');
        },
        eventClick: function (event, jsEvent, view) {
            var start = moment(event.start_date +' '+ event.start_time);
            $('.event-title').val(event.title);
            $('.event-start').val(start.format($('.common_daterange_format').val()+' hh:mm A'));
            $('.event-start').data('daterangepicker').setStartDate(start);
            $('.event-descr').val(event.description);
            $('.event-id').val(event.id);
            if (event.allDay === 1) {
                $('.event-allday').prop('checked', true);
                $('.event-end').parents('.form-group').hide();
            } else if (event.end_date !== null) {
                var end = moment(event.end_date +' '+ event.end_time);
                $('.event-end').val(end.format($('.common_daterange_format').val()+' hh:mm A'));
                $('.event-end').data('daterangepicker').setStartDate(end);
            }
            $('#event-modal').modal('show');
            $('.event-delete').show();
        }
    });

    $('#event-modal').on('hidden.bs.modal', function (e) {
        $('#event-modal input, #event-modal textarea').not('input[name=_token]').val('');
        $('.event-allday').prop('checked', false);
        $('.event-end').parents('.form-group').show();
        $('.event-delete').hide();
    });

    $('#event-modal').on('click', '.event-allday',function(){
     var ele = $(this); 
        //$('.event-end').val('');
        if(ele.prop("checked") === true){
        	$('.event-end').parents('.form-group').hide();
        }
        else if(ele.prop("checked") === false){
        	$('.event-end').parents('.form-group').show();
        }
    });
    $('.event-delete').hide();
});
