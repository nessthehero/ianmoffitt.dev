// 1.0.0
// 
// Datepicker [from functions/include/util.php]
(function($) {
	$(function() {

		// Check to make sure the input box exists
		if( 0 < $('#job_position_start_date').length ) {
			$('#job_position_start_date').datepicker({
				// defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 1,
				onClose: function( selectedDate ) {
					$( "#job_position_end_date" ).datepicker( "option", "minDate", selectedDate );
				}
			});
		} // end if

		// Check to make sure the input box exists
		if( 0 < $('#job_position_end_date').length ) {
			$('#job_position_end_date').datepicker({
				// defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 1,
				onClose: function( selectedDate ) {
					$( "#job_position_start_date" ).datepicker( "option", "maxDate", selectedDate );
				}
			});
		} // end if
 
	});
}(jQuery));