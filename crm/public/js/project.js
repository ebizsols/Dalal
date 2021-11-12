/**
* Project JS - Project js for Client Manager theme
* @version v3.1.0
* @copyright 2020 Pepdev.
*/
$(document).ready(function () {
	"use strict";
	var path = $('.site_url').val(),
	staff = $('input.staff-list').val(),
	staff_html = ''; 
	if (staff !== undefined && staff !== '' ) {
		staff = JSON.parse(staff);
		$.each(staff, function( index, value ) {
			var temp = '<option value="'+value.user_id+'" data-email="'+value.email+'">'+value.name+'<small>('+value.email+')</small></option>';
			staff_html += temp;
		});
	}

	$('#project-staff').on('click', '.add-staff', function () {
		var count = $('#project-staff table tr:last select').attr('name').split('[')[2];
		count = parseInt(count.split(']')[0]) + 1;
		$('#project-staff table tbody').append('<tr>'+
			'<td>'+
			'<select class="selectpicker" name="project[staff]['+count+'][name]" data-width="100%" data-live-search="true" title="'+$('.lang-staff').val()+'"></select>'+
			'</td>'+
			'<td>'+
			'<input type="text" class="form-transparent" name="project[staff]['+count+'][hours]" value="">'+
			'</td>'+
			'<td>'+
			'<input type="text" class="form-transparent" name="project[staff]['+count+'][rate]" value="">'+
			'</td>'+
			'<td class="text-center">'+
			'<a href="#" class="delete font-20 mt-3" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash text-danger text-danger p-1 m-1"></i></a>'+
			'</td></tr>');
		$('#project-staff table tr:last select').append(staff_html);
		$('#project-staff table tr:last select').selectpicker('refresh');

		return false;
	});

	$('#project-staff').on('click', '.delete', function () {
		$(this).parents('tr').remove();
	})

	$('body').on('change', '.billing-method', function() {
		var val = $(this).val();
		$('.budget-cost, .project-hours, .rate-hour').hide();
		if (val === "1") {
			$('.budget-cost').show();
		} else if (val === "2") {
			$('.project-hours').show();
			$('.rate-hour').show();
		} else if (val === "3") {

		} else if (val === "4") {

		}
	});

	if ($('.billing-method option:selected').val() === '1') {
		$('.rate-hour, .project-hours').hide(); $('.budget-cost').show();    
	} else if ($('.billing-method option:selected').val() === '2') {
		$('.budget-cost').hide(); $('.rate-hour, .project-hours').show();
	} else if ($('.billing-method option:selected').val() === '3') {
		$('.budget-cost').hide();
	} else if ($('.billing-method option:selected').val() === '4') {
		$('.budget-cost').hide();
	}

    //********************************************
    //Add Task ***********************************
    //********************************************
    $('#project-task').on('click', '.add-task', function () {
    	var count = $('#project-task table tr:last input').attr('name').split('[')[2];
    	count = parseInt(count.split(']')[0]) + 1;
    	$('#project-task table tbody').append('<tr><td>'+
    		'<input type="text" name="project[task]['+count+'][name]" class="form-transparent">'+
    		'</td>'+
    		'<td>'+
    		'<input type="text" name="project[task]['+count+'][description]" class="form-transparent">'+
    		'</td>'+
    		'<td>'+
    		'<input type="text" name="project[task]['+count+'][ratehour]" class="form-transparent">'+
    		'</td>'+
    		'<td>'+
    		'<input type="text" name="project[task]['+count+'][budgethour]" class="form-transparent">'+
    		'</td>'+
    		'<td>'+
    		'<select name="project[task]['+count+'][status]" class="custom-select">'+
    		'<option>'+$('.lang-status').val()+'</option>'+
    		'<option value="1">'+$('.lang-started').val()+'</option>'+
    		'<option value="2">'+$('.lang-in-process').val()+'</option>'+
    		'<option value="3">'+$('.lang-testing').val()+'</option>'+
    		'<option value="4">'+$('.lang-completed').val()+'</option>'+
    		'</select>'+
    		'</td>'+
    		'<td class="text-center">'+
    		'<a href="#" class="delete font-20 mt-3" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash text-danger text-danger p-1 m-1"></i></a>'+
    		'</td></tr>');

    	return false;
    });

    $('#project-task').on('click', '.delete', function () {
    	$(this).parents('tr').remove();
    });
    //********************************************
    //Comment ************************************
    //********************************************
    $('body').on('click', '#comment-submit', function () {
        var id = $('input[name="id"]').val(), comment = $('#project-comment').val(), comment_to = 'project';
        $.ajax({
            method: "POST",
            url: path+'project/comment',
            data: { id: id, comment : comment, comment_to : comment_to, _token: $('.s_token').val() },
            error: function () {
                toastr.error('Comment could not added', 'Server Error');
            },
            success: function (response) {
                $('#project-comment').val('');
                $('.timeline').prepend('<li>'+
                    '<div class="time"><small>Now</small></div>'+
                    '<div class="timeline-container">'+
                    '<div class="arrow"></div>'+
                    '<div class="description">'+comment+'</div>'+
                    '<div class="author">'+$('.menu-user-info p:nth-child(1)').text()+'</div>'+
                    '</div>'+
                    '</li>');
                toastr.success('Comment added Succefully', 'Success');
            }
        });
    });
});