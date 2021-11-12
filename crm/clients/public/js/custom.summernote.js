$(document).ready(function() {
	"use strict";
	$('.summernote').summernote({
		disableDragAndDrop: true,
		dialogsFade: true,
		height: 150,
		emptyPara: '',
		toolbar: [
		['style', ['style']],
		['font', ['bold', 'underline', 'clear']],
		['fontname', ['fontname']],
		['color', ['color']],
		['para', ['ul', 'ol', 'paragraph']],
		['table', ['table']],
		['insert', ['link', 'image', 'video']],
		['view', ['fullscreen', 'codeview', 'help']]
		],
		buttons: {
			image: function() {
					// create button
					var button = $.summernote.ui.button({
						contents: '<i class="note-icon-picture" />',
						//tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
						click: function () {
							$("#media-upload").on('show.bs.modal', function () {
								var path = $('input[name=absolute-upload-path]').val();
								JSON.parse($('input[name=media_all]').val()).forEach(function (element) { 
									$('#media-upload .media-all').append(
										'<div class="media-all-block">'+
										'<div>'+
										'<a data-toggle="tooltip" data-placement="top" title="Remove">'+
										'<i class="fa fa-trash-o"></i>'+
										'</a>'+
										'<img src="'+path.concat(element)+'" title="'+element+'">'+
										'<input type="radio" name="media-select" id="media-'+element+'" value="'+element+'">'+
										'<label for="media-'+element+'" title="'+element+'">'+element+'</label>'+
										'</div></div>');		
								});
							}).modal('show');
							$('.media-all').on('click', '.media-all-block div img', function(e) {
								e.preventDefault();
								$('.summernote').summernote('insertImage', $(this).attr('src'));
								$('#media-upload').modal('hide');
							});
						}
					});
					return button.render();
				}
			}
		});
});