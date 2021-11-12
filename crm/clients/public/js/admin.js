/**
 * Admin JS - admin js for Client Manage theme
 * @version v2.5.1
 * @copyright 2020 Pepdev.
 */
 $(document).ready(function () {
    "use strict";

    //********************************************
    //Data-Title Tool tip bootstrap **************
    //********************************************
    $('[data-toggle="tooltip"]').tooltip();


    //********************************************
    //Left Menu **********************************
    //********************************************

    $('body').on('click', '.menu-close', function () {
        var ele = $(this);
        $('#menu-wrapper').css('width', '60px');
        $('.page-wrapper').css('margin-left', '60px');
        ele.find('i').addClass('icon-arrow-right-circle');
        ele.removeClass('menu-close');
        ele.addClass('menu-open');
    });

    $('body').on('click', '.menu-open', function () {
        var ele = $(this);
        $('#menu-wrapper').css('width', '250px');
        $('.page-wrapper').css('margin-left', '250px');
        ele.find('i').removeClass('icon-arrow-right-circle');
        ele.removeClass('menu-open');
        ele.addClass('menu-close');
    });

    $('body').on('click', '.menu-icon a', function () {
         var ele = $(this);
         if ($('.menu').css('display') == 'none') {
            $('.menu').slideDown();
            ele.find('i').addClass('icon-close');
         } else {
            $('.menu').slideUp();
            ele.find('i').removeClass('icon-close');
         }
    });


    //********************************************
    //Listing Table ******************************
    //********************************************
    var datatable = $('.datatable-table').DataTable({
        "aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
        "iDisplayLength": 25,
        "order": [],
        'responsive': true,
        'pagingType': 'full_numbers',
        'language': {
            "lengthMenu": "_MENU_",
            "zeroRecords": $('#datatable_no_records').val(),
            "info": $('#text_showing_page').val(),
            "infoEmpty": $('#datatable_no_records').val(),
            "infoFiltered": "",
            "search": '<i class="las la-search"></i>',
            "paginate": {
                "first":       '<i class="las la-angle-double-left"></i>',
                "previous":    '<i class="las la-angle-left"></i>',
                "next":        '<i class="las la-angle-right"></i>',
                "last":        '<i class="las la-angle-double-right"></i>'
            },
        }
    });

    //********************************************
    //Tacket Page ********************************
    //********************************************
    $('body').on('change', '.ticket-attachments', function () {
        var file = $(this);
        if (typeof(file['0']['files']['0']) === "undefined") {
            $(file).val('');
            $(file).parent('.custom-file').find('label').text($('.lang-choose_file').val());
            return false;
        }
        var FileSize = file['0']['files']['0']['size'] / 1024 / 1024;
        if (FileSize > 2) {
            alert($('.lang-text_file_size_exceeds_2_MB').val());
            file.val('');
        } else {
            file.parent('.custom-file').find('label').text(file['0']['files']['0']['name']);
        }
    });

    $('body').on('click', '#add-more-file', function () {
        var count = parseInt($('.attachments').find('.custom-file:nth-last-child(1) input').prop('id').split("_")[2]) + 1; 
        $('.attachments').append('<div class="custom-file">'+
            '<label class="custom-file-label" for="attachments_file_'+count+'">'+$('.lang-choose_file').val()+'</label>'+
            '<input type="file" name="filename[]" accept="application/pdf, image/gif, image/jpeg, image/png" class="custom-file-input ticket-attachments" size="20" onchange="" id="attachments_file_'+count+'">'+
            '</div>')
    });

     // Display Alert Message
    if ($('.alert-message').length) {
        var alert_message = JSON.parse($('.alert-message').val());
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "10000",
            "hideDuration": "10000",
            "timeOut": "2000",
            "extendedTimeOut": "800",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        toastr[alert_message.alert](alert_message.value, alert_message.alert);
    }

    //********************************************
    //Dashboard Page *****************************
    //********************************************
    if ($('.chart-invoice-status').length !== 0) {
        var invoicestatus = $('.chart-invoice-status').val();
        invoicestatus = JSON.parse(invoicestatus);
        Morris.Donut({
            element: 'status-chart',
            data: invoicestatus,
            colors: ['#93e3ff', '#b0dd91', '#ffe699', '#f8cbad', '#a4a4a4'],
            formatter: function(y) {
                return y + '%'
            }
        });
    }

    if ($('.chart-ticket-status').length !== 0) {
        var ticketstatus = $('.chart-ticket-status').val();
        ticketstatus = JSON.parse(ticketstatus);
        
        Morris.Donut({
            element: 'ticket-chart',
            data: ticketstatus,
            colors: ['#f4516c', '#716aca'],
            formatter: function(y) {
                return y + '%'
            }
        });
    }
});