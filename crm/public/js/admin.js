/**
 * Admin JS - admin js for Client Manager theme
 * @version v3.1.0
 * @copyright 2020 Pepdev.
 */
/**
 * Mobile Menu Open/Close
 * Data-Title Tool tip bootstrap
 * Date Range Picker
 * Left Menu
 * Add Contact Persons
 * Add Task
 * Delete Item From List
 * Listing Table
 * Image  Uplaod
 */
 Dropzone.autoDiscover = false;
 $(document).ready(function () {
    "use strict";
    var path = $('.site_url').val();

    //********************************************
    //Data-Title Tool tip bootstrap **************
    //********************************************
    $('[data-toggle="tooltip"]').tooltip();
    
    //********************************************
    //Mobile Menu Open/Close *********************
    //********************************************
    $(window).resize(function () {
        if ($(window).width() > 576) {
            $('#menu-wrapper').show();
        }
    });
    
    $('body').on('click', '.mobile-menu-open', function () {
        $('.mobile-menu-background').show();
        $('.menu-wrapper').show();
    });

    $('body').on('click', '.mobile-menu-background', function () {
        $('.mobile-menu-background').hide();
        $('.menu-wrapper').hide();
    });


    //Open Left Side Menu in mobile
    $('body').on('click', '.open-left-menu', function () {
        var ele = $('.menu-wrapper'), nav_ele = $('.navbar-container');
        $('body').append('<div class="menu-overlay"></div>');
        ele.addClass('menu-mobile-open');
        nav_ele.addClass('menu-mobile-open');
    });

    
    //Close Left Side Menu in mobile
    $('body').on('click', '.menu-overlay', function () {
        $('.menu-wrapper, .navbar-container').removeClass('menu-mobile-open');
        $('.menu-overlay').remove();
    });

        //Open Page hdr right menu in Mobile
        $('body').on('click', '.open-page-menu-desktop', function () {
            var ele = $('.page-hdr-desktop');
            $('.page-search').slideUp(300);
            if (ele.css('display') === "none") {
                ele.slideDown(300);
            } else {
                ele.slideUp(300);
            }
        });
    //Open Page search in mobile
    $('body').on('click', '.open-mobile-search', function () {
        var ele = $('.page-search');
        $('.page-hdr-desktop').slideUp(300);
        if (ele.css('display') === "none") {
            ele.slideDown(300);
        } else {
            ele.slideUp(300);
        }
    });

    //********************************************
    //Date Range Picker **************************
    //********************************************
    $('.date').daterangepicker({
        singleDatePicker: true,
        autoApply: false,
        autoUpdateInput: false,
        locale: {
            format: $('.common_daterange_format').val()
        }
    });

    $('.date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format($('.common_daterange_format').val()));
    });

    $('.date').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $('.datetime').daterangepicker({
        singleDatePicker: true,
        timePicker : true,
        autoApply: false,
        autoUpdateInput: false,
        locale: {
            format: $('.common_daterange_format').val()+' h:mm A',
        }
    });

    $('.datetime').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format($('.common_daterange_format').val()+' h:mm A'));
    });

    $('.datetime').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    
    //Attendence
    $('.attendence').daterangepicker({
        singleDatePicker: true,
        autoApply: false,
        autoUpdateInput: false,
        locale: {
            format: $('.common_daterange_format').val()
        }
    });

    $('.attendence').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format($('.common_daterange_format').val()));
        $('.attendence-container').removeClass('d-none');
        $('.attendence-submit').removeClass('d-none');
    });

    $('.attendence-container').on('change', '.attendence-head', function() {
        var ele = $(this);
        if (ele.prop("checked") === true) {
            $('.attendence-container .attendence-'+ele.val()).prop("checked", true);
        }
        $('.attendence-container .attendence-head').not('#attendence-head-'+ele.val()).prop("checked", false);
    });

    if ($('.attendance-month').length) {
        $(".attendance-month1").datepicker( {
            dateFormat: 'M yy',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            beforeShow: function(input, inst) {
                inst.dpDiv.css({marginTop: '10px', marginLeft: -input.offsetWidth + 'px'});
            },
            onClose: function(dateText, inst) {
                var month = (inst.selectedMonth+1);
                month = (month < 10 ? "0"+month : month);
                var date = inst.selectedYear + '-' + month;
                if (date !== $('.range-month').val()) {
                    window.location.href = $('.site_url').val()+'staffattendance/view&id='+$('.staff-id').val()+'&monthyear='+date;
                }
            }
        });

        $('.attendance-month').daterangepicker({
            singleDatePicker: true,
            autoApply: false,
            autoUpdateInput: true,
            opens: 'left',
            alwaysShowCalendars: false,
            showDropdowns: true,
            locale: {
                format: 'MM-YYYY'
            }
        });

        $('.attendance-month').on('apply.daterangepicker', function(ev, picker) {
            //$(this).val(picker.startDate.format($('.common_daterange_format').val()));
            var date = picker.startDate.format('YYYY-MM');
            window.location.href = $('.site_url').val()+'staffattendance/view&id='+$('.staff-id').val()+'&monthyear='+date;
        });
    }

    if ($('.table-date-range').length) {
        var tabledate_data = $('.table-date-range').data();
        $('.table-date-range').daterangepicker({
            autoApply: false,
            alwaysShowCalendars: true,
            opens: 'left',
            applyButtonClasses: 'btn-danger',
            cancelClass: 'btn-white',
            locale: {
                format: $('.common_daterange_format').val(),
                separator: " => ",
            },
            startDate: tabledate_data.start,
            endDate: tabledate_data.end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'All Time': [moment('2015-01-01'), moment().add(60, 'days')],
            },
        });

        $('.table-date-range').on('apply.daterangepicker', function(ev, picker) {
            window.location.replace(path+tabledate_data.route+'&start='+picker.startDate.format('YYYY-MM-DD')+'&end='+picker.endDate.format('YYYY-MM-DD'));
        });
    }

    //Email User
    $('.send-user-type').on('change', function() {
        var ele = $(this), user = ele.find('option:selected').val(), receiver = $('select.send-receiver');
        $('.receiver-container .block').remove();
        $.ajax({
            method: "POST",
            url: "index.php?route=get/receiver",
            data: { user: user , _token: $('.s_token').val()},
            error: function () {
                alert('Sorry Try Again!');
            },
            success: function (response) {
                if (response !== "") {
                    $.each(JSON.parse(response), function (key, value) {
                        if (value.id === 'all') {
                            $('.receiver-container').append('<div class="custom-control custom-checkbox custom-checkbox-1 block mb-3 receiver-all">'+
                                '<input type="checkbox" class="custom-control-input" id="receiver-'+value.id+'" value="'+value.id+'" checked>'+
                                '<label class="custom-control-label" for="receiver-'+value.id+'">'+value.name+'</label></div>');
                        } else {
                            $('.receiver-container').append('<div class="custom-control custom-checkbox custom-checkbox-1 block mb-3 receiver-single">'+
                                '<input type="checkbox" name="receiver[user][]" class="custom-control-input" id="receiver-'+value.id+'" value="'+value.id+'" checked>'+
                                '<label class="custom-control-label" for="receiver-'+value.id+'">'+value.name+'</label></div>');
                        }
                    });
                }
            }
        });
    });

    $('.receiver-container').on('change', '.receiver-all input', function () {
        var ele = $(this);
        if (ele.is(":checked")) {
            ele.parents('.receiver-container').find('input').prop("checked", true);
        } else {
            ele.parents('.receiver-container').find('input').prop("checked", false);
        }
    });
    $('.receiver-container').on('change', '.receiver-single input', function () {
        var ele = $(this);
        $('.receiver-container').find('.receiver-all input').prop("checked", false);
    });

    $('body').on('change', 'select.mail-type', function () {
        if ($(this).val() == "2") { $('#smtp-mail').show(); }
        else { $('#smtp-mail').hide(); }
    });

    //********************************************
    //Currency Page ******************************
    //********************************************
    $('body').on('click', '.edit-currency', function () {
        var ele = $(this);
        $('#addCurrencyModel input[name="name"]').val(ele.data("name"));
        $('#addCurrencyModel input[name="abbr"]').val(ele.data("abbr"));
        $('#addCurrencyModel input[name="id"]').val(ele.data("id"));
        $('#addCurrencyModel select[name="status"]').val(ele.data("status"));
        $('#addCurrencyModel .modal-title').text($('.lang-edit-currency').val());
        $('#addCurrencyModel form').attr('action', $('.site_url').val().concat('currency/edit'));
        $('#addCurrencyModel').modal('show');
    });

    $('#addCurrencyModel').on('hidden.bs.modal', function (e) {
        $('#addCurrencyModel .modal-title').text($('.lang-new-currency').val());
        $('#addCurrencyModel form').attr('action', $('.site_url').val().concat('currency/add'));
        $('#addCurrencyModel input').not( "[name='_token']" ).val('');
        $('#addCurrencyModel textarea').val('');
    });

    //********************************************
    //New or Edit Payment Method Modal ***********
    //********************************************
    $('body').on('click', '.edit-paymentmethod', function () {
        var ele = $(this);
        $('#addPaymentMethodModel input[name="name"]').val(ele.data("name"));
        $('#addPaymentMethodModel textarea[name="description"]').val(ele.data("description"));
        $('#addPaymentMethodModel input[name="id"]').val(ele.data("id"));
        $('#addPaymentMethodModel select[name="status"]').val(ele.data("status"));
        $('#addPaymentMethodModel .modal-title').text($('.lang-edit-payment-method').val());
        $('#addPaymentMethodModel form').attr('action', $('.site_url').val().concat('paymentmethod/edit'));
        $('#addPaymentMethodModel').modal('show');
    });

    $('#addPaymentMethodModel').on('hidden.bs.modal', function (e) {
        $('#addPaymentMethodModel .modal-title').text($('.lang-new-payment-method').val());
        $('#addPaymentMethodModel form').attr('action', $('.site_url').val().concat('paymentmethod/add'));
        $('#addPaymentMethodModel input').not( "[name='_token']" ).val('');
        $('#addPaymentMethodModel textarea').val('');
    });

    //********************************************
    //New Tax or Edit Tax Modal ******************
    //********************************************
    $('body').on('click', '.edit-tax', function () {
        var ele = $(this);
        $('#addTaxModel input[name="name"]').val(ele.data("name"));
        $('#addTaxModel input[name="rate"]').val(ele.data("rate"));
        $('#addTaxModel input[name="id"]').val(ele.data("id"));
        $('#addTaxModel .modal-title').text($('.lang-edit-tax').val());
        $('#addTaxModel form').attr('action', $('.site_url').val().concat('tax/edit'));
        $('#addTaxModel').modal('show');
    });

    $('#addTaxModel').on('hidden.bs.modal', function (e) {
        $('#addTaxModel .modal-title').text($('.lang-new-tax').val());
        $('#addTaxModel form').attr('action', $('.site_url').val().concat('tax/add'));
        $('#addTaxModel input[name="name"]').val('');
        $('#addTaxModel input[name="rate"]').val('');
        $('#addTaxModel input[name="id"]').val('');
    });

    //********************************************
    //New or Edit Deparmtent Modal ***************
    //********************************************
    $('body').on('click', '.edit-department', function () {
        var ele = $(this);
        $('#addDepartmentModal input[name="name"]').val(ele.data("name"));
        $('#addDepartmentModal input[name="id"]').val(ele.data("id"));
        $('#addDepartmentModal select[name="status"]').val(ele.data("status"));
        $('#addDepartmentModal .modal-title').text($('.lang-edit-department').val());
        $('#addDepartmentModal form').attr('action', $('.site_url').val().concat('department/edit'));
        $('#addDepartmentModal').modal('show');
    });

    $('#addDepartmentModal').on('hidden.bs.modal', function (e) {
        $('#addDepartmentModal .modal-title').text($('.lang-new-department').val());
        $('#addDepartmentModal form').attr('action', $('.site_url').val().concat('department/add'));
        $('#addDepartmentModal input').not( "[name='_token']" ).val('');
    });

    //********************************************
    //New or Edit Expense Modal ******************
    //********************************************
    $('body').on('click', '.edit-expense-type', function () {
        var ele = $(this);
        $('#addExpenseModel input[name="name"]').val(ele.data("name"));
        $('#addExpenseModel textarea[name="description"]').val(ele.data("description"));
        $('#addExpenseModel input[name="id"]').val(ele.data("id"));
        $('#addExpenseModel select[name="status"]').val(ele.data("status"));
        $('#addExpenseModel .modal-title').text($('.lang-edit-expense-type').val());
        $('#addExpenseModel form').attr('action', $('.site_url').val().concat('expensetype/edit'));
        $('#addExpenseModel').modal('show');
    });

    $('#addExpenseModel').on('hidden.bs.modal', function (e) {
        $('#addExpenseModel .modal-title').text($('.lang-new-expense-type').val());
        $('#addExpenseModel form').attr('action', $('.site_url').val().concat('expensetype/add'));
        $('#addExpenseModel input').not( "[name='_token']" ).val('');
        $('#addExpenseModel textarea').val('');
    });

    //********************************************
    //Email Log Page *****************************
    //********************************************
    $('#messageModal').on('show.bs.modal', function () {
        $('#messageModal .log-message').append('<div class="message">'+$('#logview').data("message")+'</div>');
    })
    $('#messageModal').on('hidden.bs.modal', function (e) {
        $('#messageModal .log-message .message').remove();
    });

    //********************************************
    //Note Page **********************************
    //********************************************
    $('body').on('click', '.notes-card .notes', function (e) {
        var notes = $(this).parents('.col-md-3').html();
        $('#note-modal .modal-content').append(notes);
        $('#note-modal').modal('show');
        $('#note-modal').on('hide.bs.modal', function (event) {
            $('#note-modal .modal-content .notes-card').remove();
        });
    });
    if ($('.colorPickSelector').length) {
        $(".colorPickSelector").colorPick({
            'initialColor': $('.note-color').val(),
            'allowRecent': true,
            'recentMax': 5,
            'palette': [ "#FFF", "#FAD154", "#cfc4ff", "#f7bfff", "#61d1ff","#85ebd9","#c4ffed","#def58f", "#d9e8f0", "#ffed7d", "#ffd921", "#ff9c00", "#fa9959", "#b3d9e6", "#bac28a", "#d1ebb8", "#d1d9c9", "#ffdb70", "#ffa8b3", "#e3e3e3", "#abd1c9", "#e8ba2b", "#9cc2d6", "#d4e0e3", "#ffc27d", "#e8b01c", "#57c2ff", "#bfd9c2", "#82d9de", "#abc29e", "#f0c221", "#0096a8", "#ECF0F1", "#BDC3C7", "#95A5A6", "#7F8C8D", "#CFC", "#FFC", "#CCF", "#1ABC9C"],
            'onColorSelected': function() {
                this.element.css({'backgroundColor': this.color, 'color': this.color});
                $('.note-background').val(this.color);
                if (this.color == "#0096A8" || this.color == "#95A5A6" || this.color == "#7F8C8D" || this.color == "#1ABC9C") {
                    $('.note-color').val("#FFFFFF");
                } else {
                    $('.note-color').val("#000000");
                }
            }
        });
    }

    //********************************************
    //Transaction Page ***************************
    //********************************************
    $('.transaction-account').on('change', function () {
        $('.account-currency').text($(this).find('option:selected').data('currency'));
    });

    //********************************************
    //Ticket Page ********************************
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

    //********************************************
    //Left Menu **********************************
    //********************************************
    $('body').on('click', '.menu-close', function () {
        var ele = $(this);
        $('#menu-wrapper').css('width', '60px');
        $('.page-wrapper').css('margin-left', '60px');
        ele.find('i').removeClass('la-hand-point-left');
        ele.find('i').addClass('la-hand-point-right');
        ele.removeClass('menu-close');
        ele.addClass('menu-open');
    });

    $('body').on('click', '.menu-open', function () {
        var ele = $(this);
        $('#menu-wrapper').css('width', '250px');
        $('.page-wrapper').css('margin-left', '250px');
        ele.find('i').removeClass('la-hand-point-right');
        ele.find('i').addClass('la-hand-point-left');
        ele.removeClass('menu-open');
        ele.addClass('menu-close');
    });


    //********************************************
    //Delete & Copy Item From List ***************
    //********************************************
    $('.table-delete').on('click', function () {
        $('.delete-card-button input.delete-id').val($(this).find('input').val());
        $("#delete-card").modal({
            keyboard: true
        });
    });

    $('#delete-card').on('hidden.bs.modal', function (e) {
        $('.delete-card-button input.delete-id').val('');
    });

    $('.payment-delete').on('click', function () {
        $('.delete-card-button input.delete-id').val($(this).find('input').val());
        $("#delete-card").modal({
            keyboard: true
        });
    });

    $('body').on('click', '.table-copy', function () {
        $('.copy-card-form').val($(this).find('input').val());
        $("#copy-card").modal({
            keyboard: true
        });
    });

    $('#copy-card').on('hidden.bs.modal', function (e) {
        $('.copy-card-form').val('');
    });

    //*************************************************
    //Left Side menu  *********************************
    //*************************************************
    $('body').on('click', '.menu-close', function () {
        var ele = $(this);
        $('#main-wrapper').addClass('page-menu-small');
        ele.find('i').removeClass('fa-hand-point-left');
        ele.find('i').addClass('fa-hand-point-right');
        ele.removeClass('menu-close');
        ele.addClass('menu-open');
    });

    $('body').on('click', '.menu-open', function () {
        var ele = $(this);
        $('#main-wrapper').removeClass('page-menu-small');
        ele.find('i').removeClass('fa-hand-point-right');
        ele.find('i').addClass('fa-hand-point-left');
        ele.removeClass('menu-open');
        ele.addClass('menu-close');
    });
    
    //Left side Sub Menu
    $('body').on('click', 'li.has-sub > a', function () {
        var ele = $(this), target = ele.parent('li.has-sub').find('ul.sub-menu:first');
        ele.parent('li.has-sub').siblings('li').find('a .arrow').removeClass('rotate');
        if (target.css('display') === "none") {
            ele.parent('li.has-sub').siblings('li').find('.sub-menu').slideUp(300);
            ele.find('.arrow').addClass('rotate');
            target.slideDown(300);
        } else {
            ele.parent('li.has-sub').find('.arrow').removeClass('rotate');
            ele.parent('li.has-sub').find('ul.sub-menu').slideUp(300);
        }
        return false;
    });

    if ($('.menu-fixed').length > 0 && $('.menu-wrapper').length > 0) {
        new PerfectScrollbar('.menu-fixed .menu-wrapper .menu ul', {
            wheelSpeed: 2,
            minScrollbarLength: 20
        });
    }

    if ($('a.open-pdf').length > 0) {
        $("a.open-pdf").fancybox({
            'frameWidth': 800,
            'frameHeight': 900,
            'overlayShow': true,
            'hideOnContentClick': false,
            'type': 'iframe'
        });
    }

    //********************************************
    //Listing Table ******************************
    //********************************************
    
    var dataTable = $('.datatable-table').DataTable({
        aLengthMenu: [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
        iDisplayLength: 10,
        pagingType: 'full_numbers',
        order: [],
        dom: "<'row align-items-center pb-2'<'col-sm-6 text-left'l><'col-sm-6 text-right'f>><'row'<'col-sm-12'tr>><'row align-items-center pt-2'<'col-sm-12 col-md-4'i><'col-sm-12 col-md-8 text-right dataTables_pager'p>>",
        responsive: true,
        pagingType: 'full_numbers',
        language: {
            "lengthMenu": "_MENU_",
            "zeroRecords": $('#datatable_no_records').val(),
            "info": $('#text_showing_page').val(),
            "infoEmpty": $('#datatable_no_reocrds').val(),
            "infoFiltered": "",
            "search": '<i class="las la-search"></i>',
            "paginate": {
                "first":       '<i class="las la-angle-double-left"></i>',
                "previous":    '<i class="las la-angle-left"></i>',
                "next":        '<i class="las la-angle-right"></i>',
                "last":        '<i class="las la-angle-double-right"></i>'
            },
        },
        buttons: [
        {
            extend: 'print',
            autoPrint: true,
            customize: function (win) {
                $(win.document.body).find('h1').css('text-align','center');
                $(win.document.body).find('h1').css('font-size','20px');
            }
        },
        {
            extend: 'copyHtml5'
        },
        {
            extend: 'excelHtml5'
        },
        {
            extend: 'csvHtml5'
        }
        ],
        language: {
            "paginate": {
                "first":       '<i class="las la-angle-double-left"></i>',
                "previous":    '<i class="las la-angle-left"></i>',
                "next":        '<i class="las la-angle-right"></i>',
                "last":        '<i class="las la-angle-double-right"></i>'
            },
        }
    });

    var countdatatable = $('.datatable-count-table').DataTable({
        aLengthMenu: [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
        iDisplayLength: 10,
        pagingType: 'full_numbers',
        order: [],
        dom: "<'row align-items-center pb-3'<'col-sm-6 text-left'l><'col-sm-6 text-right'f>><'row'<'col-sm-12'tr>><'row align-items-center pt-3'<'col-sm-12 col-md-4'i><'col-sm-12 col-md-8 text-right dataTables_pager'p>>",
        responsive: false,
        buttons: [
        {
            extend: 'print',
            autoPrint: true,
            exportOptions: {
                columns: ':visible'
            },
            customize: function (win) {
                $(win.document.body).find('h1').css('text-align','center');
                $(win.document.body).find('h1').css('font-size','20px');
            }
        },
        {
            extend: 'copyHtml5',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'excelHtml5',
            exportOptions: {
                columns: ':visible'
            }
        },
        {
            extend: 'csvHtml5',
            exportOptions: {
                columns: ':visible'
            }
        }
        ],
        language: {
            "paginate": {
                "first":       '<i class="las la-angle-double-left"></i>',
                "previous":    '<i class="las la-angle-left"></i>',
                "next":        '<i class="las la-angle-right"></i>',
                "last":        '<i class="las la-angle-double-right"></i>'
            },
        },
        footerCallback: function ( row, data, start, end, display ) {

            var api = this.api(), data, column;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                i : 0;
            };

            for (var i = row.childElementCount - 1; i >= 0; i--) {
                if (i > 0) {
                    column = api.column(i).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    column = api.column( i, { page: 'current'} ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                    if (column) {
                        $( api.column(i).footer() ).html($('.common_currency').val()+column.toFixed(2));
                    }
                }

            }
        }
    });

    $('.toggle-button a').on( 'click', function (e) {
        e.preventDefault();
        var ele = $(this);
        var column = countdatatable.column(ele.attr('data-column'));
        if (column.visible()) {
            ele.find('i').removeClass('la-toggle-on');
            ele.find('i').addClass('la-toggle-off');
        } else {
            ele.find('i').addClass('la-toggle-on');
            ele.find('i').removeClass('la-toggle-off');
        }
        column.visible(!column.visible());
    });

    $(".export-button .print").on("click", function(e) {
        e.preventDefault(); dataTable.button(0).trigger()
        countdatatable.button(0).trigger()
    });

    $(".export-button .copy").on("click", function(e) {
        e.preventDefault(); dataTable.button(1).trigger()
        countdatatable.button(1).trigger()
    });

    $(".export-button .excel").on("click", function(e) {
        e.preventDefault(); dataTable.button(2).trigger()
        countdatatable.button(2).trigger()
    });

    $(".export-button .csv").on("click", function(e) {
        e.preventDefault(); dataTable.button(3).trigger()
        countdatatable.button(3).trigger()
    });

    $(".export-button .pdf").on("click", function(e) {
        e.preventDefault(); dataTable.button(4).trigger()
        countdatatable.button(4).trigger()
    });


    //********************************************
    //Image  Uplaod ******************************
    //********************************************
    $('#media-upload').on('show.bs.modal', function (e) {
        var uploaded = $('#media-upload .uploaded');
        if (uploaded.val() === '0') {
            var path = $('.site_url').val().concat('get/media');
            $.ajax({
                type: 'get',
                url: path,
                data: { name: 'media', _token: $('.s_token').val() },
                error: function () {},
                success: function (response) {
                    $('#media-upload .media-all').append(response);
                    uploaded.val('1');
                }
            });
        }
        $('#media-upload .media-all').addClass('media-modal-open');
    });

    $('.image-upload').click(function () {
        $(this).parent().addClass('image-upload-progress');
        $("#media-upload").modal('show');
    });

    $("#media-upload").on('hidden.bs.modal', function () {
        $(this).parent().find('.image-upload-progress').removeClass('image-upload-progress');
        $('#media-upload .media-all').removeClass('media-modal-open');
    });

    //Dropzone.autoDiscover = false;
    $("#media-dropzone").dropzone({
        addRemoveLinks: false,
        acceptedFiles: "image/*",
        maxFilesize: 5,
        autoProcessQueue: true,
        dictDefaultMessage: 'Drop files here or click here to upload <br /><br /> Only Image',
        init: function() {
            var reportDropzone = this;
            reportDropzone.on("sending", function(file, xhr, formData) {
                formData.append("_token", $('.s_token').val());
            });

            reportDropzone.on("success", function(file, xhr) {
                var response = JSON.parse(xhr);
                if (response.error === false) {
                    $('.media-all').prepend(response.media);
                    toastr.success('Uploaded Succefully', 'Report uploaded Succefully.');
                } else {
                    toastr.error('Error', response.message);
                }
                reportDropzone.removeFile(file);
            });

            reportDropzone.on("error", function(file, message) { 
                toastr.error('Error', message);
                reportDropzone.removeFile(file); 
            });
        },
    });

    $('#media-upload').on('click', '.media-modal-open .picture', function () {
        var image = $(this).find('input').val();
        $('.image-upload-progress .saved-picture').append('<img src="public/uploads/' + image + '" alt="">');
        $('.image-upload-progress .saved-picture input[type=hidden]').val(image);
        $('.image-upload-progress .saved-picture').show();
        $('.image-upload-progress .image-upload').hide();
        $('.image-upload-progress .saved-picture-delete').show();
        $('.content-input').removeClass('image-upload-progress');
        $('#media-upload').modal('hide');
    });

    //Image Delete 
    $('.media-all').on('click', '.block .remove', function () {
        var ele = $(this), ele_par = ele.parent(),
        media = ele_par.find('.picture input').val(),
        id = ele_par.find('.block-id').val();
        $.ajax({
            method: "POST",
            url: path.concat('media/delete'),
            data: { page: 'media', name: media, id: id, _token: $('.s_token').val() },
            error: function () {
                alert('Sorry Try Again!');
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.error === false) {
                    ele.parents('.block').remove();
                    toastr.success('Deleted', 'File deleted Succefully.');
                } else {
                    toastr.success('Wanrning', 'File could not be deleted!.');
                }
            }
        });
    });

    $('.saved-picture-delete').click(function () {
        $(this).siblings('.saved-picture').find('img').remove();
        $(this).siblings('.saved-picture').find('input').val('');
        $(this).siblings('.saved-picture').hide();
        $(this).siblings('.image-upload').show();
        $(this).hide();
    });


    //attachment 
    $("#attach-file-upload").dropzone({
        addRemoveLinks: true,
        acceptedFiles: "image/*,application/pdf",
        maxFilesize: 2,
        dictDefaultMessage: 'Drop files here or click here to upload.<br /><br /> Only Image and PDF allowed.',
        init: function() {
            var attachmentDropzone = this;
            this.on("success", function(file, xhr){
                var response = JSON.parse(xhr);
                if (response.error === false) {
                    $('.attachment-container').append(response.media);
                    toastr.success('File uploaded successfully.', 'Success');
                    $('#attach-file').modal('hide');
                } else {
                    toastr.error(response.message, 'Error');
                }
                attachmentDropzone.removeFile(file);
            });
        }
    });

    $('.attachment-container').on('click', '.attachment-delete a', function () {
        var ele = $(this),
        name = ele.parents('.attachment-image').find('input').val(),
        id = $('#attach-file #attach-file-upload').find('input[name=id]').val(),
        type = $('#attach-file #attach-file-upload').find('input[name=type]').val();
        $.ajax({
            type: 'POST',
            url: 'index.php?route=attach/documents/delete',
            data: {name: name, type: type, id: id, _token: $('.s_token').val()},
            error: function() {
                toastr.error('File could not be deleted', 'Server Error');
            },
            success: function(response) {
                response = JSON.parse(response);
                if (response.error === false) {
                    ele.parents('.attachment-image').remove();
                    toastr.success(response.message, 'Success');
                } else {
                    toastr.error(response.message, 'Error');
                }
            }
        });
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

    //Contact/Customer Search
    if ($('.page-search input').length) {
        $(".page-search input").autocomplete({
            source: path.concat('contact/search'),
            minLength: 2,
            focus: function() {
                return false;
            },
            select: function(event, ui) {
                window.location.href = $('.site_url').val().concat('contact/view&id='+ui.item.id);
                return false;
            }
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
            .append('<div>' + item.label + '<div class="font-12"> ( ' + item.company + ' )</div></div>')
            .appendTo( ul );
        };
    }

    if ($('.customer-name').length) {
        $(".customer-name").autocomplete({
            source: path.concat('contact/search'),
            minLength: 2,
            focus: function() {return false;},
            select: function( event, ui ) {
                $('.customer-id').val(ui.item.id);
                $('.customer-name').val(ui.item.label);
                $('.customer-mail').val(ui.item.email);
                $('.customer-mobile').val(ui.item.mobile);
                return false;
            }
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
            .append('<div>' + item.label + '<div class="font-12"> ( ' + item.email + ' )</div><div class="font-12"> ( ' + item.mobile + ' )</div></div>')
            .appendTo( ul );
        };
    }

    var active = $('.page-active').val();
    $('#'+active+'-li').addClass('active');
    // $('#'+active+'-li.active').focus({preventScroll:false});
});




