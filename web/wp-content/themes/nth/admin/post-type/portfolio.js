// 1.0.0
// 
// Datepicker [from functions/include/util.php]
(function($) {
	$(function() {

		// Check to make sure the input box exists
		if( 0 < $('#portfolio_start_date').length ) {
			$('#portfolio_start_date').datepicker({
				// defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 1,
				onClose: function( selectedDate ) {
					$( "#portfolio_end_date" ).datepicker( "option", "minDate", selectedDate );
				}
			});
		} // end if

		// Check to make sure the input box exists
		if( 0 < $('#portfolio_end_date').length ) {
			$('#portfolio_end_date').datepicker({
				// defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 1,
				onClose: function( selectedDate ) {
					$( "#portfolio_start_date" ).datepicker( "option", "maxDate", selectedDate );
				}
			});
		} // end if

		$('.portfolio_upload_image_button').click(function() {
			formfield = $(this).siblings('.portfolio_upload_image');
			preview = $(this).siblings('.portfolio_preview_image');
			tb_show('', 'media-upload.php?type=image&TB_iframe=true');
			window.send_to_editor = function(html) {
				imgurl = $('img',html).attr('src');
				classes = $('img', html).attr('class');
				id = classes.replace(/(.*?)wp-image-/, '');
				formfield.val(id);
				preview.attr('src', imgurl);
				tb_remove();
			}
			return false;
		});
		
		$('.portfolio_clear_image_button').click(function() {
			var defaultImage = $(this).parent().siblings('.portfolio_default_image').text();
			$(this).parent().siblings('.portfolio_upload_image').val('');
			$(this).parent().siblings('.portfolio_preview_image').attr('src', defaultImage);
			return false;
		});
 
	});
}(jQuery));