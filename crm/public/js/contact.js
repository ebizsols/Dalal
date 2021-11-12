/**
* Contact JS - Contact js for Client Manager theme
* @version v3.1.0
* @copyright 2020 Pepdev.
*/

Dropzone.autoDiscover = false;
$(document).ready(function () {
	"use strict";
	var path = $('.site_url').val();

	$("#contact-import").dropzone({
		addRemoveLinks: true,
		acceptedFiles: '.csv',
		dictInvalidFileType: $('.lang-csv-allowed-file').val(),
		maxFiles:1,
		init: function() {
			this.on("sending", function(file, xhr, formData) {
				formData.append("_token", $('.s_token').val());
			});

			this.on("maxfilesexceeded", function(file) {
				this.removeAllFiles();
				this.addFile(file);
			});
		},
		success: function (file, response) {
			if (response == '1') {
				toastr.success('Success');
				location.reload();
			} else {
				toastr.warning('Error');
			}

		}
	});

	//*************************************************
    //Add Contact Persons  ****************************
    //************************************************* 
    $('#contact-person').on('click', '.add-person', function () {
    	var count = $('#contact-person table tr:last select').attr('name').split('[')[2];
    	count = parseInt(count.split(']')[0]) + 1;
    	$('#contact-person table tbody').append('<tr><td>'+
    		'<select class="form-control form-transparent" name="contact[person]['+count+'][salutation]">'+
    		'<option>Salutation</option>'+
    		'<option value="'+$('.lang-mr').val()+'">'+$('.lang-mr').val()+'</option>'+
    		'<option value="'+$('.lang-mrs').val()+'">'+$('.lang-mrs').val()+'</option>'+
    		'<option value="'+$('.lang-ms').val()+'">'+$('.lang-ms').val()+'</option>'+
    		'<option value="'+$('.lang-dr').val()+'">'+$('.lang-dr').val()+'</option>'+
    		'</select>'+
    		'</td>'+
    		'<td><input type="text" class="form-transparent" name="contact[person]['+count+'][name]"></td>'+
    		'<td><input type="text" class="form-transparent" name="contact[person]['+count+'][email]"></td>'+
    		'<td><input type="text" class="form-transparent" name="contact[person]['+count+'][mobile]"></td>'+
    		'<td><input type="text" class="form-transparent" name="contact[person]['+count+'][skype]"></td>'+
    		'<td><input type="text" class="form-transparent" name="contact[person]['+count+'][designation]"></td>'+
    		'<td class="text-center">'+
    		'<a href="#" class="delete" data-toggle="tooltip" data-placement="top" title="Delete"><i class="las la-trash text-danger text-danger p-1 m-1"></i></a>'+
    		'</td></tr>');

    	return false;
    });

    $('#contact-person').on('click', '.delete', function () {
    	$(this).parents('tr').remove();
    });

});